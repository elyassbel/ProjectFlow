<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street1', TextType::class, [
                'label' => 'address.street1.label',
            ])
            ->add('street2', TextType::class, [
                'label' => 'address.street2.label',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'address.postalCode.label',
                'required' => false,
                'constraints' => [
                    new Length([
                       'max' => 10,
                       'maxMessage' => 'common.length.max',
                   ]),
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'address.city.label',
            ])
            ->add('country', CountryType::class, [
                'label' => 'address.country.label',
                'preferred_choices' => ['FR'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'translation_domain' => 'forms',
        ]);
    }
}
