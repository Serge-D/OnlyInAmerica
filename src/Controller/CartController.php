<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/monpanier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'totalwt' => $cart->getTotalWt()
        ]);
    }

    // function pour ajouter des produits aux paniers
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {

        //on vient chercher l'objet product pour avoir toutes les infos sur le produit grâce à l'id du produit
        $product = $productRepository->findOneById($id);

        $cart->add($product);

        $this->addFlash(
            'success',
            'Produit correctement ajouté à votre panier'
        );

        //redirection vers la dernière url visitée
        return $this->redirect($request->headers->get('referer'));
    }

    //function pour diminuer la quantité des produits au panier
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {

        $cart->decrease($id);

        $this->addFlash(
            'success',
            'Produit correctement supprimé de votre panier'
        );

        //redirection vers le panier
        return $this->redirectToRoute('app_cart');
    }

    //function pour supprimer tous les produits du panier
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('app_default_home');
    }
}
