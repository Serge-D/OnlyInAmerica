<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            -> setEntityLabelInPlural('Commandes')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        //creation d'une action pour voir le detail d'une commande : creation d'une variable qui contient une nouvelle action
        //dont le titre visible pour mon admin est Afficher qui fait le lien vers une fonction qui se nomme show
        $showOrderContent = Action::new('Afficher')->linkToCrudAction('show');

        //retrait des action Créer, supprimer et éditer une commande pour que l'administrateur ne puisse pas intéragir avec les commandes
        return $actions
            ->add(Crud::PAGE_INDEX, $showOrderContent)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX,Action::EDIT);
    }

    public function show(AdminContext $adminContext)
    {
        $order = $adminContext->getEntity()->getInstance();

        return $this->render('admin/order.html.twig',[
            'order' => $order
        ]);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date'),
            NumberField::new('state')->setLabel('Statut')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user')->setLabel('Utilisateur'),
            NumberField::new('totalTva')->setLabel('Prix total TVA'),
            NumberField::new('totalWt')->setLabel('Prix total TTC')
        ];
    }

}
