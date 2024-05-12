<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Form\UserType;
use App\Repository\AddressRepository;
use App\Repository\CommentsRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/profile')]
    public function userProfile ()
    {
        // Récupérer l'utilisateur connecté à partir du token de sécurité
        $user = $this->getUser();


        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non connecté');
        }

        // Vous pouvez maintenant passer l'utilisateur à votre template Twig pour l'affichage
        return $this->render('user/userProfile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route ('user/modifier-mot-de-passe')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {

        // Récupérer l'utilisateur connecté à partir du token de sécurité
        $user = $this->getUser();

        //on créé le form et on lui envoie les infos de l'user et le passwordhasher pour comparer les mdp
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->addFlash(
                'success',
                'Votre mot de passe est mis à jour'
            );

            $entityManager->flush(); //pas besoin de persist() car maj
        }

        return $this->render('user/changepwd.html.twig', [
            'modifyPwd'=> $form->createView()
        ]);
    }

    #[Route('/user/reservation')]
    public function userReservation ()
    {
        return $this -> render('user/userReservation.html.twig');
    }

    #[Route('/inscription', name: 'app_register')]
    public function userRegister (Request $request,UserPasswordHasherInterface $hasher ,EntityManagerInterface $manager)
    {
        #Creation d'un User vide
        $user = new User();
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());
        #Création du formulaire
        $form = $this->createForm(UserType::class, $user);

        #passer la requête au formulaire pour traitement
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            #Encryptage du mdp
            # $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            # $user->setPassword($hashedPassword);

            #Sauvegarder dans la BDD
            $manager->persist($user); #persist = enregistrer / dire au manager d'enregistrer dans la bdd
            $manager->flush(); #flush équivalent d'execute / requete Insert

            #Notification
            $this->addFlash('success','Félicitations, vous pouvez vous connecter.');

            #Envoi d'un email de confirmation d'inscription
            $mail = new Mail();
            $vars = [
                'firstname' => $user->getFirstName()
            ];
            $mail->send($user->getEmail(),$user->getFirstName().' '.$user->getFirstName(),'Bienvenue sur OnlyInAmerica','welcome.html', $vars);

            #Redirection page d'accueil
            return $this->redirectToRoute('app_login');

        }

        #Passage du formulaire à la vue
        return $this -> render('user/userRegister.html.twig', [
            'registerForm'=>$form
        ]);
    }

    #[Route('/user/addresses', name: 'app_user_addresses')]
    public function addresses()
    {
        return $this -> render('user/addresses.html.twig');
    }

    #[Route('/user/address/delete/{id}', name: 'app_user_address_delete')]
    public function addressDelete($id, AddressRepository $addressRepository, EntityManagerInterface $entityManager)
    {
        //Même principe pour le changement d'adresse, on vient récuperer l'adresse avec l'id et on check si elle existe et si elle correspond à l'user
        $address = $addressRepository->findOneById($id);
        if(!$address OR $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_user_addresses');
        }
        $this->addFlash(
            'success',
            'Votre adresse a bien été supprimée.'
        );

        $entityManager->remove($address);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_addresses');
    }


    //Utilisation de la même route pour ajouter ou modifier une adresse
    //Pour cela, on va passer en argument un id optionnel et on le met dans defaults (du tableau Route) à null
    //Si l'id existe on execute un traitement, sinon on créé un nouvel objet address
    #[Route('/user/address/add/{id}', name: 'app_user_address_form', defaults: ['id'=> null ])]
    public function addressForm(Request $request, EntityManagerInterface $entityManager, $id, AddressRepository $addressRepository, Cart $cart)
    {
        if($id){
            $address = $addressRepository->findOneById($id);
            // Sécurisation du changement d'adresse => est ce que l'adresse appartient à l'user en cours et si elle existe bien
            if(!$address OR $address->getUser() != $this->getUser()){
                return $this->redirectToRoute('app_user_addresses');
            }
        }else{
            $address = new Address();
            $address->setUser($this->getUser());
        }


        $form = $this->createForm(AddressUserType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre adresse est sauvegardée.'
            );

            // permet de revenir sur la page app_order si le panier contient des éléments
            if($cart->fullQuantity() > 0){
                return $this->redirectToRoute('app_order');
            }

            // si le panier est vide on retourne sur la page de profil/adresses
            return $this->redirectToRoute('app_user_addresses');
        }

        return $this -> render('user/addressForm.html.twig', [
            'adressForm' => $form
        ]);
    }


    #[Route('/user/favorite')]
    public function userFavorite ()
    {
        return $this -> render('user/userFavorite.html.twig');
    }
    #[Route('/user/favorite/add/{id}' , name: 'app_user_addfavorite')]
    public function addFavorite($id, ProductRepository $productRepository, EntityManagerInterface $entityManager, Request $request)
    {
        //1. récuperer l'objet du produit souhaité
        $product = $productRepository->findOneById($id);

        //2. si produit existant, ajouter le produit aux favoris
        if($product){
            $this->getUser()->addProduct($product);
            //3. sauvergarder en bdd
            $entityManager->flush();
        }

        $this->addFlash('success','Produit ajouté à vos favoris');

        //redirection vers la dernière url visitée
        return $this->redirect($request->headers->get('referer'));

    }
    #[Route('/user/favorite/remove/{id}' , name: 'app_user_removefavorite')]
    public function removeFavorite($id, ProductRepository $productRepository, EntityManagerInterface $entityManager, Request $request)
    {
        //1. récuperer l'objet du produit à supprimer
        $product = $productRepository->findOneById($id);

        //2. si produit existant, supprimer le produit aux favoris
        if($product){
            $this->addFlash('success','Produit supprimé de vos favoris');

            $this->getUser()->removeProduct($product);
            //3. sauvergarder en bdd
            $entityManager->flush();
        }else{
            $this->addFlash('danger','Produit introuvable');
        }

        return $this->redirect($request->headers->get('referer'));
    }
    #[Route('/user/comment', name: 'app_user_comment')]
    public function userComment(CommentsRepository $commentsRepository)
    {
        $user = $this->getUser();
        $comments = $commentsRepository->findBy(['user'=>$user]);


        return $this->render('user/userComment.html.twig',[
            'user' => $user,
            'comments' => $comments
        ]);
    }
    #[Route('/user/order', name: 'app_user_order')]
    public function userOrder(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->findBy([
           'user' => $this->getUser(),
            'state' => [2, 3] //pour rechercher les commandes dont le state est supérieur à 1
        ]);

        return $this->render('user/userOrders.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/user/ordercontent/{id_order}', name: 'app_user_ordercontent')]
    public function userOrderContent($id_order, OrderRepository $orderRepository)
    {
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);

        if(!$order){
            return $this->redirectToRoute('app_default_home');
        }

        return $this->render('user/userOrderContent.html.twig', [
            'order' => $order
        ]);
    }
}