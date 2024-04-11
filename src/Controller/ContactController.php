<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Merci de nous avoir contacté. Vous recevrez une réponse dans les meilleurs délais.');

            $mail = new Mail();
            $vars = [
                'user' => $form->getData()['fullname'],
                'mail' => $form->getData()['email'],
                'message' => $form->getData()['message']
            ];
            $mail->send('serge.dingreville.d@gmail.com','Admin','Mail de la section contact','contactMail.html',$vars);

            return $this->redirectToRoute('app_default_home');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
