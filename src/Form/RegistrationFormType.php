<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => "Email",
                "required" => true,
            ])
            ->add('pseudo', TextType::class, [
                "label" => "Pseudo",
                "required" => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                "type" => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                "required" => true,
                "first_options" => [
                    "label" => "Mot de passe",   
                ], 
                "second_options" => [
                    "label" => "Répetez le mot de passe",   
                ], 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez un mot de passe.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                    new Regex([
                        "pattern" => "/^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/",
                        "message" => "Minimum 8 caractères, au moins une lettre, un chiffre et un caractère spécial.",
                    ])
                ],
            ])
            ->add('isWriter', CheckboxType::class, [
                "label" => "Vous enregistrer en tant qu'auteur ?",
                "required" => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
