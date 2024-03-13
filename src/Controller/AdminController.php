<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('admin/backoffice')]
    public function backoffice ()
    {
        return $this -> render('admin/backoffice.html.twig');
    }

    #[Route('admin/usermanagement')]
    public function userManagement ()
    {
        return $this -> render('admin/userManagement.html.twig');
    }
    #[Route('admin/productmanagement')]
    public function productManagement ()
    {
        return $this -> render('admin/productManagement.html.twig');
    }
    #[Route('admin/reservationmanagement')]
    public function reservationManagement ()
    {
        return $this -> render('admin/reservationManagement.html.twig');
    }
    #[Route('admin/commentmanagement')]
    public function commentManagement ()
    {
        return $this -> render('admin/commentManagement.html.twig');
    }
    // Partie surement à supprimer
    #[Route('admin/useradd')]
    public function userAdd ()
    {
        return $this -> render('admin/userAdd.html.twig');
    }

    #[Route('admin/userremove')]
    public function userRemove ()
    {
        return $this -> render('admin/userRemove.html.twig');
    }

    #[Route('admin/usermodify')]
    public function userModify ()
    {
        return $this -> render('admin/userModify.html.twig');
    }

    #[Route('admin/productadd')]
    public function productAdd ()
    {
        return $this -> render('admin/productAdd.html.twig');
    }

    #[Route('admin/productremove')]
    public function productRemove ()
    {
        return $this -> render('admin/productRemove.html.twig');
    }

    #[Route('admin/productmodify')]
    public function productModify ()
    {
        return $this -> render('admin/productModify.html.twig');
    }

    #[Route('admin/commentadd')]
    public function commentAdd ()
    {
        return $this -> render('admin/commentAdd.html.twig');
    }

    #[Route('admin/commentremove')]
    public function commentRemove ()
    {
        return $this -> render('admin/commentRemove.html.twig');
    }

    #[Route('admin/commentmodify')]
    public function commentModify ()
    {
        return $this -> render('admin/commentModify.html.twig');
    }

    #[Route('admin/reservationadd')]
    public function reservationAdd ()
    {
        return $this -> render('admin/reservationAdd.html.twig');
    }

    #[Route('admin/reservationremove')]
    public function reservationRemove ()
    {
        return $this -> render('admin/reservationRemove.html.twig');
    }

    #[Route('admin/reservationmodify')]
    public function reservationModify ()
    {
        return $this -> render('admin/reservationModify.html.twig');
    }
}