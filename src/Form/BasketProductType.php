<?php

namespace App\Form;

use App\Model\FormBasketProductModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BasketProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $limit = $options['limit'];
        $selectChoices = [];

        for($i=1;$i<=$limit;$i++)
        {
            $selectChoices[$i] = $i;
        }

        $builder
            ->add('count', ChoiceType::class, [
                'choices' => [
                    'Ilość' =>
                        $selectChoices
                ]
            ]) //select z limitem pobieranym z opcji
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'limit' => 0,
                'data_class' => FormBasketProductModel::class,
            ]
        );
    }
}
