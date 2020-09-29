<?php

namespace App\Form\User;

use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use verzeilberg\UploadImagesBundle\Form\Image\Upload;

class UserProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if (!$options["useOnlyImageUpload"]) {
            $builder
                ->add('firstName')
                ->add('lastName')
                ->add('lastNamePrefix')
                ->add('birthday', BirthdayType::class, [
                    'widget' => 'choice',
                    'placeholder' => [
                        'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    ]
                ]);
        }

        $builder->add('image', Upload::class);

        $builder->add('save', SubmitType::class, [
            'label' => 'Save',
            'attr' => ['class' => 'btn-custom']
        ]);

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
            'useImageUpload' => true,
            'useOnlyImageUpload' => false
        ]);
    }
}
