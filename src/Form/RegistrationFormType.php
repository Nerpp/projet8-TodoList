<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
        ->add('displayName',TextType::class,[
            'attr' => [
            'placeholder' => 'Nom d\'affichage de l\'utilisateur'
            ]
        ])
        ->add('roles',ChoiceType::class,[
            'required' => true,
            'multiple' => false,
            'expanded' => false,
            'choices'  => [
              'ROLE_USER' => 'ROLE_USER',
              'ROLE_ADMIN' => 'ROLE_ADMIN'
            ]
           
        ])
        ->add('password',RepeatedType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'type' => PasswordType::class,
            'empty_data' => "",
            'invalid_message' => 'La confirmation du mot de passe doit être identique à sa confirmation.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Le mot de passe doit être de 8 caractéres min avec des caractéres spéciaux'
                ],
            ],
            'second_options' => [
                'label' => 'Repeat Password',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Le mot de passe doit être de 8 caractéres min avec des caractéres spéciaux'

                ],
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Entrer un mot de passe s\'il vous plait',
                ]),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Votre mot de passe doit contenir {{ limit }} caractéres minimum',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        ->add('email',TextType::class,[
            'attr' => [
            'placeholder' => 'email@exemple.com'
            ]
        ])
    ;

         // Data transformer
         $builder->get('roles')
         ->addModelTransformer(new CallbackTransformer(
             function ($rolesArray) {
                  // transform the array to a string
                  return count($rolesArray)? $rolesArray[0]: null;
             },
             function ($rolesString) {
                  // transform the string back to an array
                  return [$rolesString];
             }
     ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}