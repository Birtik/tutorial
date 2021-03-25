<?php

namespace App\Form;

use App\Model\RegisterUserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
            ->add('lastName', TextType::class)
            ->add(
                'legalAgreement',
                CheckboxType::class,
                [
                    'label' => 'Akceptuję Regulamin sklepu i zapoznałem się z Polityką Prywatności',
                    'required' => true
                ]
            )
            ->add(
                'newsletterAgreement',
                CheckboxType::class,
                [
                    'label' => 'Chcę otrzymywać newsletter i komunikację marketingową od TutorialSklep',
                    'required' => false
                ]
            );
        $builder->add('Zarejestruj', SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => RegisterUserModel::class,
            ]
        );
    }
}
