<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class,[
                'label'=>'Nom'
            ])
            ->add('firstName', TextType::class,[
                'label'=>'Prénom'
            ])
            ->add('email', EmailType::class,[
                'label'=>'Email'
            ])
            ->add('password', PasswordType::class,[
                'label'=>'Mot de passe'
            ])
            ->add('address', TextType::class,[
                'label'=>'Adresse'
            ])
            ->add('zipCode', TextType::class,[
                'label'=>'Code Postal'
            ])
            ->add('country', CountryType::class,[
                'label'=>'Pays'
            ])
            ->add('city', TextType::class,[
                'label'=>'Ville'
            ])
            ->add('phone', TelType::class,[
                'label'=>'Téléphone'
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
