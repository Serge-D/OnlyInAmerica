<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/')]
    public function home(ProductRepository $productRepository): Response
    {
        return $this -> render('default/home.html.twig', [
            'productsInHomepage' => $productRepository->findByIsHomepage(true)
        ]);
    }

    #[Route('/apropos')]
    public function aPropos (): Response
    {
        return $this -> render('default/aPropos.html.twig');
    }
}