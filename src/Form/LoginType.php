<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'label'=>'Adresse Mail',
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre adresse mail'
                    ])
                ]
            ])
            ->add('password', PasswordType::class,[
                'label'=>'Mot de passe',
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre mot de passe'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Connexion'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
