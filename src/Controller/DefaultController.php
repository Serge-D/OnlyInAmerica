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

    #[Route('/conditions-generales-de-ventes', name: 'app_default_cgv')]
    public function cgv (): Response
    {
        return $this -> render('default/cgv.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_default_mentionsLegales')]
    public function mentionsLegales (): Response
    {
        return $this -> render('default/mentionsLegales.html.twig');
    }

    #[Route('/politique-de-confidentialite', name: 'app_default_politiqueDeConfidentialite')]
    public function politiqueDeConfidentialite (): Response
    {
        return $this -> render('default/politiqueDeConfidentialite.html.twig');
    }
}