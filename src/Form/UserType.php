<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userProfile', UserProfileType::class)
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Administrateur - Peut changer d\'utilisateur' => 'ROLE_ALLOWED_TO_SWITCH',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('enabled', ChoiceType::class, [
                'label' => 'Enabled',
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true,
            ])
            ->add('verified', ChoiceType::class, [
                'label' => 'Verified',
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
