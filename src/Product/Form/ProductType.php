<?php

namespace App\Product\Form;

use App\Product\Entity\Product;
use App\Product\Model\Enum\SaleTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('auctionPrice', NumberType::class, [
                'attr' => [
                    'step' => 0.1,
                ],
            ])
            ->add('buyNowPrice', NumberType::class, [
                'attr' => [
                    'step' => 0.1,
                ],
            ])
            ->add('saleType', EnumType::class, [
                'class' => SaleTypeEnum::class,
            ])
            ->add('parameters', CollectionType::class, [
                'entry_type' => ProductParameterType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
