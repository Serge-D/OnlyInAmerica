<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/category/{slug}')]
    public function category ($slug)
    {
        return $this -> render ('product/category.html.twig', ['slug' => $slug]);
    }

    #[Route('/product/productlist')]
    public function productList ()
    {
        return $this -> render ('product/productList.html.twig');
    }
}