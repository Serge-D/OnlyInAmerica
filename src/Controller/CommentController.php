<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    // est ce qu'il faudrait tout regrouper en un seul bloc ou un comment add et le reste regroupé
    #[Route('comment/commentadd')]
    public function commentAdd ()
    {
        return $this -> render('comment/commentAdd.html.twig');
    }

    #[Route('comment/commentremove')]
    public function commentRemove ()
    {
        return $this -> render('comment/commentRemove.html.twig');
    }

    #[Route('comment/commentmodify')]
    public function commentModify ()
    {
        return $this -> render('comment/commentModify.html.twig');
    }
}