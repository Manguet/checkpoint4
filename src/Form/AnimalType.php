<?php

namespace App\Form;

use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'animal',
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'label'  => 'Sélectionnez une date',
                ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('localisation', TextType::class, [
                'label' => 'localisation'
            ])
            ->add('specie', TextType::class, [
                'label' => 'Espèce',
            ])
            ->add('race', TextType::class, [
                'label' => 'Race',
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Sexe',
                'choices' => [
                    'Mâle' => 'Mâle',
                    'Femelle' => 'Femelle',
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image Principale',
                'constraints' => [
                    new File([
                        'maxSize' => '3000k'])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
