<?php

namespace App\Form;

use App\Entity\CompanyContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'company_contact.lastName.label',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'company_contact.firstName.label',
            ])
            ->add('email', TextType::class, [
                'label' => 'company_contact.email.label',
            ])
            ->add('phone1', TextType::class, [
                'label' => 'company_contact.phone1.label',
                'attr' => [
                    'placeholder' => 'company_contact.phone1.placeholder',
                ],
                'required' => false,
            ])
            ->add('phone2', TextType::class, [
                'label' => 'company_contact.phone2.label',
                'attr' => [
                    'placeholder' => 'company_contact.phone1.placeholder',
                ],
                'required' => false,
            ])
            ->add('main', CheckboxType::class, [
                'label' => 'company_contact.main.label',
                'required' => false,
            ])
            ->add('comments', TextareaType::class, [
                'label' => 'company_contact.comments.label',
                'attr' => [
                    'placeholder' => 'company_contact.comments.placeholder',
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyContact::class,
            'translation_domain' => 'forms',
        ]);
    }
}
