<?php

namespace App\Form\Type;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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
            ->add('guests', null,
                [
                    'attr' => ['readonly' => true]
                ]
            )
            ->add('tempPrice', null,
                [
                    'mapped' => false,
                    'attr' => ['readonly' => true, ]
                ]
            )
            ->add('price', null,
                [
                    'attr' => ['readonly' => true]
                ]
            )
            ->add('firstName')
            ->add('lastName')
            ->add('phone', TelType::class)
            ->add('mail', EmailType::class)
            ->add('message', TextareaType::class, ['required' => false])
            ->add('rooms', null, ['label' => false, 'attr' => ['style' => 'display:none']])
            ->add('policy', CheckboxType::class, ['required' => true])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
