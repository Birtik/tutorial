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
        $selectChoices = $this->generateSelectChoices($options['limit']);
        if (count($selectChoices) > 0) {
            $builder
                ->add(
                    'amount',
                    ChoiceType::class,
                    [
                        'choices' => [
                            'Ilość' =>
                                $selectChoices,
                        ],
                    ]
                )->add('submit', SubmitType::class);
        }
    }

    public function generateSelectChoices(int $limit): array
    {
        $selectChoices = [];
        for ($i = 1; $i <= $limit; $i++) {
            $selectChoices[$i] = $i;
        }

        return $selectChoices;
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
