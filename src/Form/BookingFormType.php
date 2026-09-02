<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('purpose', TextareaType::class, [
                'label' => 'Objectif du rendez-vous'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}