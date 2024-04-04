<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            -> setEntityLabelInPlural('Utilisateurs')
            ;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        // Champs que l'administrateur peut modifier
        return [
            TextField::new('lastName')->setLabel('Nom'),
            TextField::new('firstName')->setLabel('Prénom'),
            TextField::new('email')->setLabel('Email')->onlyOnIndex(),
            TextField::new('address')->setLabel('Adresse'),
            TextField::new('zipCode')->setLabel('Code Postal'),
            TextField::new('city')->setLabel('Ville'),
            CountryField::new('country')->setLabel('Pays'),
            TelephoneField::new('phone')->setLabel('Téléphone'),
        ];
    }
    */
}
