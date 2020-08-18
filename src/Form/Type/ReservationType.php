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
            ->add('checkInDate', Type\DateType::class,
                [
                    'label'  => '',
                    'widget' => 'single_text',
                    'html5'  => false,
                    'attr'   => ['class' => 'date js-datepicker form-control', 'placeholder' => 'DD-MM-YYYY'],
                ]
            )
            ->add('checkOutDate', Type\DateType::class,
                [
                    'label'  => '',
                    'widget' => 'single_text',
                    'html5'  => false,
                    'attr'   => ['class' => 'date js-datepicker form-control', 'placeholder' => 'DD-MM-YYYY'],
                ]
            )
            ->add('guests', ChoiceType::class,
                [
                    'choices' =>
                        [
                            '1' => 1,
                            '2' => 2,
                            '3' => 3,
                            '4' => 4,
                            '5' => 5,
                        ],
                    'choice_attr' => function() {
                        return ['class' => 'choice-input'];
                    },
                    'attr' => ['class' => 'choice-input']
                ]
            )
            ->add('submit', Type\SubmitType::class,
                ['attr' => ['class' => 'btn btn-primary btn-block']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('csrf_protection', false);
    }
}
