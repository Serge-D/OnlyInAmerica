<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            -> setEntityLabelInPlural('Produits')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom')->setHelp('Nom du produit'),
            SlugField::new('slug')->setTargetFieldName('name')->setLabel('URL')->setHelp('URL de votre catégorie'),
            TextEditorField::new('description')->setLabel('Description')->setHelp('Description du produit'),
            ImageField::new('image')->setLabel('Image')->setHelp('Image du produit')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')->setBasePath('/uploads')->setUploadDir('/public/uploads'),
            AssociationField::new('category','Catégorie associée'),
            NumberField::new('price')->setLabel('Prix HT')->setHelp('Prix HT du produit sans le sigle €'),
            ChoiceField::new('tva')->setLabel('Taux de TVA')->setChoices([
                '5,5%' => '5.5',
                '10%' => '10',
                '20%' => '20'
            ])
            /*
             * pour le imagefield le setbasepath on parle d'affichage pour le setuploadDir on est en php donc obligé d'indiqué la racine
             */
        ];
    }

}
