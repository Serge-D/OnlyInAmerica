<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}