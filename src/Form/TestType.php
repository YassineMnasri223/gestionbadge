<?php

namespace App\Form;

use App\Entity\Test;
use App\Entity\Badge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    
                    new NotBlank([
                        'message' => 'Le champ name ne doit pas être vide.',
                    ]),
                ],
                'empty_data' =>'',

            ])
            ->add('type', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ type ne doit pas être vide.',
                    ]),
                ],
                'empty_data' => '',
            ])
            ->add('testbadge', EntityType::class, [
                'class' => Badge::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a badge',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
            
        ]);
    }
}
