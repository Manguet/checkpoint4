<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Booking;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hours = array_combine(range(8,20),range(8,20));

        $builder
            ->add('animals', ChoiceType::class, [
                'label'        => 'Choisir l\'animal dont vous voulez vous occuper',
                'choice_label' => 'name',
                'choices'      => $options['animals'],
            ])
            ->add('atDate', DateType::class, [
                'widget' => 'single_text',
                'label'  => 'Sélectionnez une date',
                'attr' => array(
                    'min' => date('Y-m-d')
                )
            ])
            ->add('hour', ChoiceType::class, [
                'label'   => 'Choisissez une heure',
                'choices' => $hours,
            ])
            ->add('title', TextType::class, [
                'label' => 'Dites-nous pourquoi'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'animals'    => null,
        ]);
    }
}
