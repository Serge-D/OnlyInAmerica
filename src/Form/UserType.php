<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class,[
                'label'=>'Nom',
                'constraints' => [
                    new Length([
                        'min' => '2',
                        'max' => '50'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre nom'
                ]
            ])
            ->add('firstName', TextType::class,[
                'label'=>'Prénom',
                'constraints' => [
                    new Length([
                        'min' => '2',
                        'max' => '50'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Indiquez votre prénom'
                ]
            ])
            ->add('email', EmailType::class,[
                'label'=>'Email',
                'attr' => [
                    'placeholder' => 'Indiquez votre adresse mail'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => '12',
                        'max' => '30'
                    ])
                ],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'hash_property_path' => 'password',
                    'attr' => [
                        'placeholder' => 'Indiquez votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe'
                    ]
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Envoyer',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email'
                ])
            ],
            'data_class' => User::class,
        ]);
    }
}
