<?php

namespace App\Product\Form;

use App\Product\Entity\ProductPicture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Product\Entity\ProductPictureType as ProductPictureTypeEntity;

class ProductPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('path', FileType::class)
            ->add('type', EntityType::class, [
                'class' => ProductPictureTypeEntity::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductPicture::class,
        ]);
    }
}
