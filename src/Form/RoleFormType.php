<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Route;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class RoleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('roleId')
            ->add('description')
            ->add('loginPath', EntityType::class, [
                'class'        => Route::class,
                'choice_label' => 'route',
                'label'        => 'Login path'
            ])
            ->add('save', SubmitType::class, [
                    'label' => 'Save',
                    'attr' => ['class' => 'btn-custom']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
