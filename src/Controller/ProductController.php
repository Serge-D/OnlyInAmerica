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

    #[Route('/productcategory/{id}')]
    public function productCategory($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);
        return $this -> render('product/productCategory.html.twig',[
            'category' => $category
        ]);
    }

    #[Route('/productdetails/{name}')]
    public function productDetails($name, ProductRepository $productRepository)
    {
        $product = $productRepository->find($name);
        return $this -> render('product/productDetails.html.twig',[
            'product' => $product
        ]);
    }

}