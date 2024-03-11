<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/')]
    public function home()
    {
        return $this -> render('default/home.html.twig');
    }

    #[Route('/contact')]
    public function contact ()
    {
        return $this -> render('default/contact.html.twig');
    }

    #[Route('/login')]
    public function logIn ()
    {
        return $this -> render('default/logIn.html.twig');
    }

    #[Route('/signin')]
    public function signIn ()
    {
        return $this -> render('default/signIn.html.twig');
    }

    #[Route('/apropos')]
    public function aPropos ()
    {
        return $this -> render('default/aPropos.html.twig');
    }
}