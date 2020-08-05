<?php

namespace App\Form\Type;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('checkIn', null,
                [
                    'widget' => 'single_text',
                    'attr' => [
                        'readonly' => true,
                    ],
                ]
            )
            ->add('checkOut', null,
                [
                    'widget' => 'single_text',
                    'attr' => [
                        'readonly' => true,
                    ],
                ]
            )
            ->add('guests')
            ->add('price', null,
                [
                    'attr' => ['readonly' => true]
                ]
            )
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('mail', null,
                [
                    'attr' => ['placeholder' => 'test@test.com']
                ]
            )
            ->add('message', TextareaType::class)
            ->add('rooms')
            ->add('policy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
