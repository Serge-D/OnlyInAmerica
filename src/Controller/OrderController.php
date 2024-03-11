<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/reservation')]
    public function reservation ()
    {
        return $this -> render('order/reservation.html.twig');
    }
}