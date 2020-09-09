<?php

namespace App\Form;

use App\Entity\Product\Product;
use App\Entity\Product\ProductGroup;
use App\Entity\Product\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('unit', EntityType::class, [
                'class'        => Unit::class,
                'choice_label' => 'name',
                'label'        => 'Unit'
            ])
            ->add('carbohydrates')
            ->add('productGroup', EntityType::class, [
                'class'        => ProductGroup::class,
                'choice_label' => 'name',
                'label'        => 'Productgroup'
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn-custom']
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
