<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\PasswordUserType;
use App\Form\UserType;
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

    #[Route('/user/favorite')]
    public function userFavorite ()
    {
        return $this -> render('user/userFavorite.html.twig');
    }

    #[Route('/user/inscription')]
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


}