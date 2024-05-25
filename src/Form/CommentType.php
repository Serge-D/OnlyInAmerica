<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment_content', TextareaType::class,[
                'label' => 'Votre commentaire',
                'attr' => [
                    'placeholder' => 'Veuillez indiquer votre commentaire ici'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\w\s\.,!?()-]*$/',
                        'message' => 'Votre commentaire ne peut contenir que des lettres, des chiffres et certains caractères spéciaux (,.!?-)',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Envoyer',
                'attr' => [
                    'class'=>'btn-success'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
