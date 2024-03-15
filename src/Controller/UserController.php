<?php

namespace App\Controller;

use App\Entity\User;
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
        return $this -> render('user/userProfile.html.twig');
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

        #Création du formulaire
        $form = $this->createForm(UserType::class, $user);

        #passer la requête au formulaire pour traitement
        $form->handleRequest($request);

        if($form->isSubmitted()){

            #Encryptage du mot de passe
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            #Sauvegarder dans la BDD
            $manager->persist($user); #persist = enregistrer / le manager enregistre dans la bdd
            $manager->flush(); #flush équivaklent d'execute

            #Notification
            $this->addFlash('success','Félicitations, vous pouvez vous connecter.');

            #Redirection page d'accueil
            return $this->redirectToRoute('app_default_home');

        }

        #Passage du formulaire à la vue
        return $this -> render('user/userRegister.html.twig', [
            'form'=>$form
        ]);
    }

    #[Route('/user/connexion')]
    public function userConnection ()
    {
        return $this -> render('user/userConnection.html.twig');
    }
}