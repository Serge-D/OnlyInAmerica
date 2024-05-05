<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commentaire')
            ->setEntityLabelInPlural('Commentaires')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('comment_content')
                ->setLabel('Contenu du commentaire'),
            AssociationField::new('product')
                ->setLabel('Produit associé')
                ->formatValue(function ($value, $entity){
                    $productName =$value ? $value->getName() : "N/A";

                    return $productName;
                }),
            AssociationField::new('user')
                ->setLabel('Utilisateur associé')
                ->formatValue(function ($value, $entity){
                    $userFullName =$value ? $value->getFirstname() . ' ' . $value->getLastname() : "N/A";

                    return $userFullName;
                }),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Comment) {
            $entityInstance->setCreatedAt(new \DateTimeImmutable());
            // Ajoutez d'autres logiques de traitement si nécessaire
        }

        parent::persistEntity($entityManager, $entityInstance);
    }


}
