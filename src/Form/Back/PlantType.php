<?php

namespace App\Form\Back;

use App\Entity\Exposure;
use App\Entity\Location;
use App\Entity\Tag;
use App\Entity\Plant;
use App\Entity\Soil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PlantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latin_name', TextType::class, [
                'label' => 'Nom latin'
            ])
            ->add('name',  TextType::class, [
                'label' => 'Nom commun'
            ])
            ->add('description',  TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('amount_of_water',  NumberType::class, [
                'label' => 'Quantité d\'eau'
            ])
            ->add('frequency_of_watering',  TextType::class, [
                'label' => 'Fréquence d\'arrosage'
            ])
            ->add('pruning',  TextType::class, [
                'label' => 'Taille'
            ])
            ->add('origin',  TextType::class, [
                'label' => 'Origine'
            ])
            ->add('location', EntityType::class, [
                'label' => 'Zone de culture',
                'class' => Location::class,
                'choice_label' => 'zone',
                'placeholder' => 'Choose a location',
                'required' => true,
            ])

            ->add('exposure',  EntityType::class, [
                'label' => 'Exposition',
                'class' => Exposure::class,
                'choice_label' => 'name',
                'placeholder' => 'choisiser une exposure',
                'required' => true,

            ])
            ->add('soil',  EntityType::class, [
                'label' => 'Type de sol',
                'class' => Soil::class,
                'choice_label' => 'type',
                'multiple' => false,
                'expanded' => true,
                'required' => true,
            ])
            ->add('tags',  EntityType::class, [
                'label' => 'Catégories',
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plant::class,
        ]);
    }
}
