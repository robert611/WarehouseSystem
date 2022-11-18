<?php

namespace App\Warehouse\Form;

use App\Warehouse\Entity\WarehouseDimension;
use App\Warehouse\Entity\WarehouseLeafSettings;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WarehouseLeafSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('capacity', NumberType::class, [
                'label' => 'Pojemność',
            ])
            ->add('dimension', EntityType::class, [
                'label' => 'Gabaryt',
                'class' => WarehouseDimension::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WarehouseLeafSettings::class,
        ]);
    }
}
