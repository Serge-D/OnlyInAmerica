<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Indiquez votre prénom ...'
                ],
                'constraints' => [
                    new Length([
                        'min' => '2',
                        'max' => '50'
                    ])
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Indiquez votre nom ...'
                ],
                'constraints' => [
                    new Length([
                        'min' => '2',
                        'max' => '50'
                    ])
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => 'Indiquez votre adresse ...'
                ],
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre adresse'
                    ])
                ]
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Votre code postal',
                'attr' => [
                    'placeholder' => 'Indiquez votre code postal ...'
                ],
                'constraints'=>[
                    new NotBlank([
                    'message'=>'Veuillez saisir votre code postal'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Indiquez votre ville ...'
                ],
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre ville'
                    ])
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Votre numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Indiquez votre numéro de téléphone ...'
                ],
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre numéro de téléphone'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder votre adresse',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
