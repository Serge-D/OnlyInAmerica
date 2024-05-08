<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderContent;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function orderRecapitulatif (Request $request, Cart $cart, EntityManagerInterface $entityManager) //Injection de dépendance
    {
        // permet de rediriger l'utilisateur si il n'a pas soumis le formulaire comme par exemple en rejouant l'url (cadre d'une visite de page)
        if($request->getMethod() != 'POST' ){
            return $this->redirectToRoute('app_cart');
        }

        //création de la variable products pour afficher tous les produits de notre panier
        $products = $cart->getCart();

        // J'indique à symfony qu'il y a un form à écouter sur cette route
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //dd($form->getData());

            //Création de la chaine de texte adresse
            $addressObj = $form->get('addresses')->getData();

            $address = $addressObj->getFirstname().' '.$addressObj->getLastname().'<br />';
            $address .= $addressObj->getAddress().'<br />';
            $address .= $addressObj->getZipcode().' '.$addressObj->getCity().'<br />';
            $address .= $addressObj->getCountry().'<br />';
            $address .= $addressObj->getPhone();

            //stocker les informations en bdd
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setAddressDelivery($address);

            foreach ($products as $product){
                //dd($product);
                $orderContent = new OrderContent();
                $orderContent->setProductName($product['object']->getName());
                $orderContent->setProductImage($product['object']->getImage());
                $orderContent->setProductPrice($product['object']->getPrice());
                $orderContent->setProductTva($product['object']->getTva());
                $orderContent->setProductQuantity($product['quantity']);
                $order->addOrderContent($orderContent); // pour faire la relation entre l'entité order et orderContent (propriété myOrder)
            }

            $entityManager->persist($order);
            $entityManager->flush();

        }

        return $this -> render('order/recapitulatif.html.twig', [
            'choices' => $form->getData(), //envoi de la variable choices au template
            'cart' => $products,
            'totalwt' => $cart->getTotalWt()
        ]);
    }
}