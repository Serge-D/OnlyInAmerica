<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /*
     * Premiere etape de la commande : choix de l'adresse de livraison
     */
    #[Route('/order/livraison', name: 'app_order')]
    public function orderLivraison ()
    {
        $addresses = $this->getUser()->getAddresses();
        //dd(count($addresses));

        // si l'utilisateur n'a pas d'adresse, redirection vers le formulaire pour enregistrer une adresse
        if(count($addresses) == 0){
            return $this->redirectToRoute('app_user_address_form');
        }

        // deuxieme parametre null car il concerne le mapping avec une entité et orderType n'est pas relié à une entité
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary') // permet au formulaire d'utiliser une autre route
        ]);


        return $this -> render('order/livraison.html.twig', [
            'deliveryForm' => $form
        ]);
    }

    /*
     * deuxieme etape de la commande : récapitulatif de la commande
     * insertion en base de données
     * (v2 préparation du paiement vers stripe)
     */
    #[Route('/order/summary', name: 'app_order_summary')]
    public function orderRecapitulatif (Request $request, Cart $cart)
    {
        // permet de rediriger l'utilisateur si il n'a pas soumis le formulaire comme par exemple en rejouant l'url (cadre d'une visite de page)
        if($request->getMethod() != 'POST' ){
            return $this->redirectToRoute('app_cart');
        }

        // J'indique à symfony qu'il y a un form à écouter sur cette route
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //dd($form->getData());

        }

        return $this -> render('order/recapitulatif.html.twig', [
            'choices' => $form->getData(), //envoi de la variable choices au template
            'cart' => $cart->getCart(),
            'totalwt' => $cart->getTotalWt()
        ]);
    }
}