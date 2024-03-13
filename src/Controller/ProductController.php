<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    #[Route('/product/category')]
    public function category ()
    {
        return $this -> render ('product/category.html.twig');
    }

    #[Route('/product/productlist')]
    public function productList ()
    {
        return $this -> render ('product/productList.html.twig');
    }#[Route('/product/productpage')]
    public function productPage ()
    {
        return $this -> render ('product/productPage.html.twig');
    }
}