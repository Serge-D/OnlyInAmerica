<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/productlist')]
    public function productList (ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render('product/productList.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/productcategory/{slug}')]
    public function productCategory($slug, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOneBy(['slug'=>$slug]);
        return $this -> render('product/productCategory.html.twig',[
            'category' => $category
        ]);
    }

    #[Route('/productdetails/{slug}')]
    public function productDetails($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(['slug'=>$slug]);
        return $this -> render('product/productDetails.html.twig',[
            'product' => $product
        ]);
    }

}