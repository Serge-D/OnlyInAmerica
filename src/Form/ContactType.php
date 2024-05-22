<?php

namespace App\Form;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
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
                'attr' => [
                    'placeholder'=>'Veuillez saisir votre nom et prénom'
                ],
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre nom et prénom'
                    ])
                ]
            ])
            ->add('email', EmailType::class,[
                'label'=>'Adresse mail',
                'attr' => [
                    'placeholder'=>'Veuillez saisir votre adresse mail'
                ],
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre adresse mail'
                    ])
                ]
            ])
            ->add('message', TextareaType::class,[
                'label'=>'Message',
                'attr' => [
                    'placeholder'=>'En quoi pouvons nous vous aider ?'
                ],
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Veuillez saisir votre message'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Envoyer',
                'attr' => [
                    'class'=>'btn-success'
                ],
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
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
