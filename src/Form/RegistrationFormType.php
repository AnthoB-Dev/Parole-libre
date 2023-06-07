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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => "Email",
                "required" => true,
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez remplir ce champs."
                    ]),
                    new Email([
                        "message" => "Saisissez une adresse email valide.",
                    ]),
                    // new Regex([
                    //     "pattern" => "/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/"
                    // ])
                ]
            ])
            ->add('pseudo', TextType::class, [
                "label" => "Pseudo",
                "required" => true,
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez remplir ce champs.",
                    ]),
                    // new Regex([
                    //     "pattern" => "^[a-z0-9_-]{4,20}$/",
                    //     "message" => "Format invalide! Votre pseudo peut contenir des lettres, chiffres et être séparé par des tirets '-' .",
                    // ]),
                    new Length([
                        "min" => 4,
                        "minMessage" => "Votre pseudo doit contenir au moins {{ limit }} caractères.",
                        "max" => 20,
                        "maxMessage" => "Merci de ne pas dépasser {{ limit }} caractères.",
                    ])
                ]
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
                        'message' => 'Veuillez remplir ce champs.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                    new Regex([
                        "pattern" => "/^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/",
                        "message" => "Format invalide! Votre mot de passe doit contenir au moins une lettre, un chiffre et un caractère spécial.",
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