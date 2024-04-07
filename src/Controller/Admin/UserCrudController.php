<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
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


    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_NEW === $pageName) {
            return [
                TextField::new('lastName')
                    ->setLabel('Nom'),
                TextField::new('firstName')
                    ->setLabel('Prénom'),
                EmailField::new('email')
                    ->setLabel('Email'),
                TextField::new('address')
                    ->setLabel('Adresse'),
                TextField::new('zipCode')
                    ->setLabel('Code Postal'),
                TextField::new('city')
                    ->setLabel('Ville'),
                CountryField::new('country')
                    ->setLabel('Pays'),
                TelephoneField::new('phone')
                    ->setLabel('Téléphone'),
                TextField::new('password')
                    ->setLabel('Mot de passe'),
                ChoiceField::new('roles', 'Roles')
                    ->allowMultipleChoices()
                    ->setChoices([
                        'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN',
                    ])
                    ->renderAsBadges(),
                #DateTimeField::new('createdAt')
                #    ->setLabel('Créé le')
                #    ->hideOnIndex(),
                #DateTimeField::new('updatedAt')
                #    ->setLabel('Mis à jour le')
                #    ->hideOnIndex(),
            ];
        }

        if (Crud::PAGE_EDIT === $pageName) {
            return [
                TextField::new('lastName')
                    ->setLabel('Nom'),
                TextField::new('firstName')
                    ->setLabel('Prénom'),
                TextField::new('address')
                    ->setLabel('Adresse'),
                TextField::new('zipCode')
                    ->setLabel('Code Postal'),
                TextField::new('city')
                    ->setLabel('Ville'),
                CountryField::new('country')
                    ->setLabel('Pays'),
                TelephoneField::new('phone')
                    ->setLabel('Téléphone'),
                #DateTimeField::new('updatedAt')
                #    ->setLabel('Mis à jour le')
                #    ->hideOnIndex(),
            ];
        }

        return [
            TextField::new('lastName')
                ->setLabel('Nom'),
            TextField::new('firstName')
                ->setLabel('Prénom'),
            EmailField::new('email')
                ->setLabel('Email'),
            TextField::new('address')
                ->setLabel('Adresse'),
            TextField::new('zipCode')
                ->setLabel('Code Postal'),
            TextField::new('city')
                ->setLabel('Ville'),
            CountryField::new('country')
                ->setLabel('Pays'),
            TelephoneField::new('phone')
                ->setLabel('Téléphone'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User && $entityInstance->getPassword()) {
            // Hachage du mot de passe avant la persistance
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
        }
        if ($entityInstance instanceof User) {
            $entityInstance->setCreatedAt(new \DateTimeImmutable());
            $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityManager instanceof User){
            $entityInstance->setUpdateAt(new \DateTimeImmutable());
        }
        parent::updateEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }

}
