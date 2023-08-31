<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'placeholder' => 'change_password.plainPassword.first_options.placeholder'
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'change_password.plainPassword.not_blank',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'change_password.plainPassword.length',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'change_password.plainPassword.first_options.label',
                ],
                'second_options' => [
                    'label' => 'change_password.plainPassword.second_options.label',
                    'attr' => [
                        'placeholder' => 'change_password.plainPassword.second_options.placeholder'
                    ]
                ],
                'invalid_message' => 'change_password.plainPassword.invalid_message',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['translation_domain' => 'forms']);
    }
}
