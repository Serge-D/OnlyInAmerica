<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class,[
                'label'=>'Nom & Prénom',
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre nom'
                    ])
                ]
            ])
            ->add('email', EmailType::class,[
                'label'=>'Adresse mail',
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre adresse mail'
                    ])
                ]
            ])
            ->add('message', TextareaType::class,[
                'label'=>'Message',
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre message'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Envoyer'
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
