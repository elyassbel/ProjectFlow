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
                'label' => 'user.email.label',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'user.roles.label',
                'choices' => [
                    'user.roles.choices.role_user' => 'ROLE_USER',
                    'user.roles.choices.role_admin' => 'ROLE_ADMIN',
                    'user.roles.choices.role_allowed_to_switch' => 'ROLE_ALLOWED_TO_SWITCH',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => true,
            ])
            ->add('enabled', ChoiceType::class, [
                'label' => 'user.enabled.label',
                'choices' => [
                    'app.choices.yes' => true,
                    'app.choices.no' => false,
                ],
                'expanded' => true,
            ])
            ->add('verified', ChoiceType::class, [
                'label' => 'user.verified.label',
                'choices' => [
                    'app.choices.yes' => true,
                    'app.choices.no' => false,
                ],
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms',
        ]);
    }
}
