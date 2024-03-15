<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('admin/dashboard')]
    public function dashboard()
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('admin/usermanagement')]
    public function userManagement()
    {
        return $this->render('admin/userManagement.html.twig');
    }

    #[Route('admin/productmanagement')]
    public function productManagement()
    {
        return $this->render('admin/productManagement.html.twig');
    }

    #[Route('admin/reservationmanagement')]
    public function reservationManagement()
    {
        return $this->render('admin/reservationManagement.html.twig');
    }

    #[Route('admin/commentmanagement')]
    public function commentManagement()
    {
        return $this->render('admin/commentManagement.html.twig');
    }

    // Partie surement à supprimer
    #[Route('admin/useradd')]
    public function userAdd()
    {
        return $this->render('admin/userAdd.html.twig');
    }

    #[Route('admin/productadd')]
    public function productAdd(Request $request, EntityManagerInterface $manager)
    {
        #Création d'un Product vide
        $product = new Product();

        #Création du formulaire
        $form = $this->createForm(ProductType::class, $product);

        #passage de la requete au formulaire pour traitement
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            #sauvegarde dans la bdd
            $manager->persist($product);
            $manager->flush();

            #redirection page backoffice
            return $this->redirectToRoute('app_admin_backoffice');

        }

        return $this->render('admin/productAdd.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('admin/commentadd')]
    public function commentAdd()
    {
        return $this->render('admin/commentAdd.html.twig');
    }


    #[Route('admin/reservationadd')]
    public function reservationAdd()
    {
        return $this->render('admin/reservationAdd.html.twig');
    }
}