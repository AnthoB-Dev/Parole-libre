<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom de la catégorie: ",
                "required" => true,
                "constraints" => [
                    new NotBlank([
                        "message" => "Ne pas laisser ce champs vide."
                    ]),
                    new Regex([
                        "pattern" => "/^[A-Z][a-z]{3,}$/",
                        "message" => "Format invalide! L'intitulé doit commencer par une majuscule et ne peut contenir que des lettres. Il doit avoir une longueur minimale de 4 caractères.",
                    ])
                ],
            ])
            ->add("slug", TextType::class, [
                "label" => "Définir le slug de la catégorie (ce sera la route à afficher dans l'url ex: /parole-libre.",
                "required" => true,
                "constraints" => [
                    new NotBlank([
                        "message" => "Ne pas laisser ce champs vide."
                    ]),
                    new Regex([
                        "pattern" => "/^[a-z]+(?:-[a-z]+)*$/",
                        "message" => "Format invalide ! Le slug doit être écrit en minuscule, peut contenir des tirets '-' au besoin et doit correspondre au nom de la catégorie.",
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
