<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {

    }

    /*
     * add()
     * fonction permettant l'ajout d'un produit dans le panier
     */
    public function add($product)
    {
        // Appeler la session Cart de symfony
        $cart = $this->getCart();

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

    /*
     * decrease()
     * fonction permettant la suppression d'un produit dans le panier
     */
    public function decrease($id)
    {
        $cart = $this->getCart();

        if($cart[$id]['quantity'] > 1){
            $cart[$id]['quantity'] = $cart[$id]['quantity']-1;
        }else{
            unset($cart[$id]);
        }

        //Maj de la session cart
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
     * fullquantity()
     * fonction retournant le nombre total du produit au panier
     */
    public function fullQuantity()
    {
        $cart = $this->getCart();
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

    /*
     * getTotalWt()
     * fonction retournant le prix total des produits au panier
     */
    public function getTotalWt()
    {
        $cart = $this->getCart();
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


    /*
     * remove()
     * fonction permettant de supprimer totalement le panier
     */
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    /*
     * getCart()
     * fonction retournant le panier
     */
    public function getCart()
    {
        //session en cours du panier
        return $this->requestStack->getSession()->get('cart');
    }
}