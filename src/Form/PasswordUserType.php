<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => 'Votre mot de passe actuel',
                'attr' => [
                    'placeholder' => 'Indiquez votre mot de passe actuel'
                ],
                'mapped' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => '4',
                        'max' => '30'
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+<>,.?])(?=.*[0-9a-z]).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, un caractère spécial et des chiffres ou des lettres minuscules.',
                    ])
                ],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'hash_property_path' => 'password',
                    'attr' => [
                        'placeholder' => 'Indiquez votre nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre nouveau mot de passe'
                    ]
                ],
                'mapped' => false
            ])
            ->add('submit',SubmitType::class, [
                'label' => 'Modifiez votre mot de passe',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                //récupération du formulaire
                $form = $event->getForm();

                //récupération des data de l'user (on a pu voir ces infos avec dd($form)
                $user = $form->getConfig()->getOptions()['data'];


                //récupération du passwordHasher
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];


                //Checker si le plaintext password correspond au user password
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );

                //Si c'est différent envoyer une erreur
                if(!$isValid){
                    $form->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme"));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
