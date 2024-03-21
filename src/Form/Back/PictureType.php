<?php

namespace App\Form\Back;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;



class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('picture', FileType::class, [
                'label' => 'Choose picture',
                'mapped' => false, // Set to false to handle the file upload manually
                'required' => false, // Make the file field optional
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/pjpeg',  // Additional MIME type for JPEG
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG image',
                    ]),
                ],
            ])
            // ->add('plant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
