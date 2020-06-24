<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roomType', null, ['label' => ''])
            ->add('checkInDate', null, ['label' => ''])
            ->add('checkOutDate', null, ['label' => ''])
            ->add('adults', Type\NumberType::class, ['label' => ''])
            ->add('children', Type\NumberType::class, ['label' => ''])
            ->add('submit', Type\SubmitType::class, ['label' => 'Submit']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('csrf_protection', false);
    }
}
