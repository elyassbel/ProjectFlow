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
                'label' => 'Lastname',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Firstname',
            ])
            ->add('averageDailyRate', NumberType::class, [
                'label' => 'Average Daily Rate',
                'attr' => [
                    'min' => 0,
                ],
                'required' => false,
            ])
            ->add('locale', ChoiceType::class, [
                'choices' => [
                    'English' => 'en',
                    'Français' => 'fr',
                ],
            ])
            ->add('currency', ChoiceType::class, [
                'choices' => [
                    '€' => '€',
                    '$' => '$',
                    '£' => '£',
                ],
            ])
            ->add('avatarFile', VichImageType::class, [
                'label' => 'Avatar',
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
        ]);
    }
}
