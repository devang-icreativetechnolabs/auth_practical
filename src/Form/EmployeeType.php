<?php

namespace App\Form;

use App\Entity\Employee;
use App\Enum\EmployeeRole;
use App\Enum\Gender;
use App\Enum\Hobby;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'First Name'])
            ->add('lastName', TextType::class, ['label' => 'Last Name'])
            ->add('age')
            ->add('hobby', ChoiceType::class, [
                'choices'  => [
                    'Reading' => Hobby::READING,
                    'Sports' => Hobby::SPORTS,
                    'Music' => Hobby::MUSIC,
                    'Gaming' => Hobby::GAMING,
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => Gender::MALE,
                    'Female' => Gender::FEMALE,
                    'Other' => Gender::OTHERS,
                ]
            ])
            ->add('aboutMe')
            ->add('salary')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Employee' => EmployeeRole::EMPLOYEE,
                    'Manager' => EmployeeRole::MANAGER,
                    'HR' => EmployeeRole::HR,
                ],
                'multiple' => false,
            ])
            ->add('city')
            ->add('profile_image', FileType::class, [
                'label' => 'Profile Image',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPG, JPEG or PNG)',
                    ])
                ],
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'form-control'],

            'data_class' => Employee::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
