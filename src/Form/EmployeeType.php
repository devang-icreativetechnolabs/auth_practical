<?php

namespace App\Form;

use App\Entity\Employee;
use App\Enum\EmployeeRole;
use App\Enum\Gender;
use App\Enum\Hobby;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'profile_image',
                FileType::class,
                [
                    'label' => 'Profile Image',
                    'required' => false,
                    'attr' => [
                        'accept' => 'image/*'
                    ],
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
                ]
            )
            ->add(
                'first_name',
                TextType::class,
                [
                    'label' => 'First Name',
                    'constraints' => [
                        new Constraints\NotBlank(['message' => 'Please enter a first name']),
                    ]
                ]
            )
            ->add(
                'last_name',
                TextType::class,
                [
                    'label' => 'Last Name',
                    'constraints' => [
                        new Constraints\NotBlank(['message' => 'Please enter a last name']),
                    ]
                ]
            )
            ->add(
                'age',
                null,
                [
                    'constraints' => [
                        new Constraints\NotBlank(['message' => 'Please enter an age']),
                        new Constraints\Positive(['message' => 'Please enter a valid age'])
                    ]
                ]
            )->add(
                'hobby',
                ChoiceType::class,
                [
                    'choices'  => [
                        'Reading' => Hobby::READING,
                        'Sports' => Hobby::SPORTS,
                        'Music' => Hobby::MUSIC,
                        'Gaming' => Hobby::GAMING,
                    ],
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
            ->add(
                'gender',
                ChoiceType::class,
                [
                    'choices' => [
                        "Select an option" => null,
                        'Male' => Gender::MALE,
                        'Female' => Gender::FEMALE,
                        'Other' => Gender::OTHERS,
                    ],
                    'constraints' => [
                        new Constraints\NotBlank(['message' => 'Please select a gender']),
                    ]
                ]
            )
            ->add('aboutMe')
            ->add(
                'salary',
                null,
                [
                    'constraints' => [
                        new Constraints\NotBlank(['message' => 'Please enter an salary']),
                        new Constraints\Positive(['message' => 'Please enter a valid salary']),
                    ]
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => [
                        "Select an option" => null,
                        'Employee' => EmployeeRole::EMPLOYEE,
                        'Manager' => EmployeeRole::MANAGER,
                        'HR' => EmployeeRole::HR,
                    ],
                    'multiple' => false,
                    'constraints' => [
                        new Constraints\NotBlank(['message' => 'Please select a role']),
                    ]
                ]
            )
            ->add('city');
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
