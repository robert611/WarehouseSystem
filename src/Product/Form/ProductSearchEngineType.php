<?php

namespace App\Product\Form;

use App\Product\Form\DTO\ProductSearchEngineDTO;
use App\Product\Model\Enum\SaleTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchEngineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa',
            ])
            ->add('saleType', EnumType::class, [
                'class' => SaleTypeEnum::class,
            ])
            ->add('createdAtFrom', DateTimeType::class)
            ->add('createdAtTo', DateTimeType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductSearchEngineDTO::class,
        ]);
    }
}