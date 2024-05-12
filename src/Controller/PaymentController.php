<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/order/payment/{id_order}', name: 'app_payment')]
    public function payment($id_order, OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        //permet à symfony d'aller chercher l'id de la commande (pour avoir les détails) et également l'user à laquelle elle est rataché comme ca
        // si manipulation de l'url alors pas de risque au niveau de la sécurité car pas possible d'avoir de commande d'autres users
        $order = $orderRepository->findOneBy([
           'id' => $id_order,
           'user' => $this->getUser()
        ]);


        // permet de proteger contre des personnes qui voudraient acceder à des commandes qui ne sont pas les leurs
        if(!$order){
            return $this->redirectToRoute('app_default_home');
        }

        $products_for_stripe = [];

        foreach ($order->getOrderContents() as $product){

            $products_for_stripe[] = [
                'price_data'=> [
                    'currency' => 'eur',
                    'unit_amount'=>number_format($product->getProductPriceWt() *100, 0,'',''), // on fait *100 car Stripe a besoin de nombre sans décimal car il procede lui même à la mise en forme 1000 = 10 pour stripe
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [
                            $_ENV['DOMAIN'].'/uploads/'.$product->getProductImage()
                        ]
                    ]
                ],
                'quantity'=> $product->getProductQuantity()

            ];
        }


        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [[
                $products_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN'] . '/order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN'] . '/monpanier/annulation',
        ]);

        // permet dans un premier temps de ne pas faire circuler l'id order de page en page et accentue la sécurité avec une grosse chaine de caracteres pour l'url
        // permet dans un second temps à l'admin de se retrouver plus facilement si il y a un problème entre une commande et un paiement
        // on va mettre cet id en parametre dans la route success
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
    }

    #[Route('/order/success/{stripe_session_id}', name: 'app_payment_success')]
    public function success($stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $entityManager, Cart $cart): Response
    {
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);

        if(!$order){
            return $this->redirectToRoute('app_default_home');
        }

        if($order->getState() == 1){
            $order->setState(2);
            $cart->remove();
            $entityManager->flush();
        }

        return $this->render('payment/success.html.twig',[
           'order' => $order
        ]);
    }
}
