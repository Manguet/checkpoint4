<?php

namespace App\Form;

use App\Entity\Collaborator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CollaboratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'label'  => 'Sélectionnez une date',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('role', TextType::class, [
                'label' => 'Role à la Wild'
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
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
            'data_class' => Collaborator::class,
        ]);
    }
}
