<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'user_profile.lastName.label',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'user_profile.firstName.label',
            ])
            ->add('averageDailyRate', NumberType::class, [
                'label' => 'user_profile.averageDailyRate.label',
                'attr' => [
                    'min' => 0,
                ],
                'required' => false,
            ])
            ->add('locale', ChoiceType::class, [ //todo: add Locale Entity
                'label' => 'user_profile.locale.label',
                'choices' => [
                    'user_profile.locale.choices.french' => 'FR',
                ],
            ])
            ->add('currency', ChoiceType::class, [  //todo: add Currency Entity
                'label' => 'user_profile.currency.label',
                'choices' => [
                    'user_profile.currency.choices.euro' => '€',
                    'user_profile.currency.choices.dollar' => '$',
                    'user_profile.currency.choices.pound' => '£',
                ],
            ])
            ->add('avatarFile', VichImageType::class, [
                'label' => 'user_profile.avatarFile.label',
                'required' => false,
                'download_uri' => false,
                'imagine_pattern' => 'image120x120',
                'constraints' => [
                    new File([
                         'maxSize' => '1024k',
                         'maxSizeMessage' => 'File must be under 1024k',
                     ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
            'translation_domain' => 'forms'
        ]);
    }
}
