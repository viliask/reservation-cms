<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roomType', ChoiceType::class,
                [
                    'choices' =>
                        [
                            'Single' => 'single',
                            'Double' => 'double',
                            'Suite'  => 'suite',
                        ],
                ]
            )
            ->add('checkInDate', Type\DateType::class,
                [
                    'label'  => '',
                    'widget' => 'single_text',
                    'html5'  => false,
                    'attr'   => ['class' => 'date js-datepicker', 'placeholder' => 'DD-MM-YYYY'],
                ]
            )
            ->add('checkOutDate', Type\DateType::class,
                [
                    'label'  => '',
                    'widget' => 'single_text',
                    'html5'  => false,
                    'attr'   => ['class' => 'date js-datepicker', 'placeholder' => 'DD-MM-YYYY'],
                ]
            )
            ->add('adults', ChoiceType::class,
                [
                    'choices' =>
                        [
                            '1' => 1,
                            '2' => 2,
                            '3' => 3,
                            '4' => 4,
                            '5' => 5,
                        ],
                ]
            )
            ->add('children', ChoiceType::class,
                [
                    'choices' =>
                        [
                            '1' => 1,
                            '2' => 3,
                            '3' => 3,
                            '4' => 4,
                            '5' => 5,
                        ],
                ]
            )
            ->add('submit', Type\SubmitType::class, ['label' => 'Check availability']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('csrf_protection', false);
    }
}
