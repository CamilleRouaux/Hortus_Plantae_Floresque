<?php

namespace App\Form\Back;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * this class is used for the modification of a user acsesibility:
 * 
 * role   modification (the role admin is not a role you can modify.)
 * status modification
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices' => [
                    'ROLE_REDACTOR' => 'ROLE_REDACTOR',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Specifies the associated data class (User entity)
        ]);
    }
}
