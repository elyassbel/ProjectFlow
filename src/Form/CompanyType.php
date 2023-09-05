<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'company.name.label',
                'attr' => [
                    'placeholder' => 'company.name.placeholder',
                ],
            ])
            ->add('address', AddressType::class, [
                'label' => 'company.address.label',
            ])
            ->add('website', UrlType::class, [
                'label' => 'company.website.label',
                'attr' => [
                    'placeholder' => 'company.website.placeholder',
                ],
                'required' => false,
            ])
            ->add('comments', TextareaType::class, [
                'label' => 'company.comments.label',
                'attr' => [
                    'placeholder' => 'company.comments.placeholder',
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'translation_domain' => 'forms',
        ]);
    }
}
