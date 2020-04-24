<?php

namespace App\Form\User;

use App\Entity\UserProfile;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;

class UserProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('lastNamePrefix')
            ->add('birthdate');

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
