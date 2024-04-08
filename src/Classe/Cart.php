<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {

    }

    //function pour ajouter des éléments au panier
    public function add($product)
    {
        // Appeler la session Cart de symfony
        $cart = $this->requestStack->getSession()->get('cart');

        // Ajouter une quantity +1 à mon produit
        if(isset($cart[$product->getId()])){
            $cart[$product->getId()] = [
              'object' => $product,
              'quantity' => $cart[$product->getId()]['quantity'] + 1
            ];
        }else{
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => 1
            ];
        }

        // Créér / Maj de la session cart
        $this->requestStack->getSession()->set('cart', $cart);

    }

    //function pour supprimer des élèments du panier
    public function decrease($id)
    {
        $cart = $this->requestStack->getSession()->get('cart');

        if($cart[$id]['quantity'] > 1){
            $cart[$id]['quantity'] = $cart[$id]['quantity']-1;
        }else{
            unset($cart[$id]);
        }

        //Maj de la session cart
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /// function pour avoir la quantité totale du panier
    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $quantity = 0;

        //si le panier est vide
        if(!isset($cart)){
            return $quantity;
        }

        //si le panier contient des articles, on boucle sur chaque élément pour avoir la quantité par produit et ensuite la quantité totale
        foreach ($cart as $product){
            //dump($product);
            $quantity = $quantity + $product['quantity'];
        }

        return $quantity;
    }

    // fonction pour avoir le prix total avec taxe du panier
    public function getTotalWt()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $price = 0;

        //si le panier est vide
        if(!isset($cart)){
            return $price;
        }

        //si le panier contient des articles on vient boucler sur chaque élément pour avoir le prix et on fait un total des prix
        foreach ($cart as $product){
            $price = $price + $product['object']->getPriceWt() * $product['quantity'];
        }

        return $price;
    }


    // function pour supprimer tous les éléments du panier
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    // function pour avoir le panier de la session en cours
    public function getCart()
    {
        //session en cours du panier
        return $this->requestStack->getSession()->get('cart');
    }
}