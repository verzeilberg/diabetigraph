<?php

namespace App\Form\User;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
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

class UserFormType extends AbstractType
{

    private $roleRepository;
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->add('userName')
            ->add('email')
            ->add('isActive')
            ->add('authRoles', EntityType::class, [ // add this
                'class' => Role::class,
                'choice_label' => function (Role $role) {
                    return $role->getName();
                },
                'choice_value' => 'id', // $category->getProperty()
                'multiple' => true,
            ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($builder) {
                $user = $event->getData();
                if (!$user || null === $user->getId()) {
                    $event->getForm()->add('password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'The password fields must match.',
                        'options' => ['attr' => ['class' => 'password-field']],
                        'required' => true,
                        'first_options' => ['label' => 'Password'],
                        'second_options' => ['label' => 'Repeat password'],
                    ]);
                } else {
                    $event->getForm()->add('password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'The password fields must match.',
                        'options' => ['attr' => ['class' => 'password-field']],
                        'required' => false,
                        'first_options' => ['label' => 'Password'],
                        'second_options' => ['label' => 'Repeat password'],
                    ]);

                }
            }
        );

        $builder->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn-custom']
            ]
        );

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
