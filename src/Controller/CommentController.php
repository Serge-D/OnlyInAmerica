<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('comment/commentadd/{slug}', name: 'app_comment_add')]
    public function commentAdd ($slug, Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        //récuperer le produit concerné par le commentaire
        $product = $productRepository->findOneBySlug($slug);

        //Vérifier si le produit existe
        if(!$product){
            $this->redirectToRoute('app_product_productlist');
        }

        //creation d'un commentaire vide
        $comment = new Comment();
        $comment->setCreatedAt(new \DateTimeImmutable());

        //récuperation de l'utilisateur connecté
        $user = $this->getUser();
        $comment->setUser($user);

        //indication du produit pour le commentaire
        $comment->setProduct($product);

        //création du formulaire
        $form = $this->createForm(CommentType::class, $comment);

        //passer la requete au formulaire pour traitement
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success','Votre commentaire à bien été ajouté.');
            // mettre un redirect to route sur la page du produit
            return $this->redirectToRoute('app_product_productdetails',['slug'=> $product->getSlug()]);
        }

        return $this -> render('comment/commentAdd.html.twig',[
            'form'=> $form
        ]);
    }

    #[Route('comment/commentremove')]
    public function commentRemove ()
    {
        return $this -> render('comment/commentRemove.html.twig');
    }

    #[Route('comment/commentmodify')]
    public function commentModify ()
    {
        return $this -> render('comment/commentModify.html.twig');
    }
}