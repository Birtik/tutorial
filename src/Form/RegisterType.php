<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class)
            ->add(
                'password',
                RepeatedType::class,
                [
                    'first_options' => ['label' => 'Hasło'],
                    'second_options' => ['label' => 'Powtórz Hasło'],
                    'invalid_message' => 'Wpisane hasła nie są jednakowe',
                    'type' => PasswordType::class,
                ]
            )
            ->add('firstName', TextType::class)
            ->add('LastName', TextType::class)
            ->add('Zajerestruj', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class
            ]
        );
    }
}
