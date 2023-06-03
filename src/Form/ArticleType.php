<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                "class" => Category::class,
                "choice_label" => "name",
                "label" => "Catégorie",
                "placeholder" => "-------",
                "required" => true,
            ])
            ->add('title', TextType::class, [
                "label" => "Titre de l'article:",
                "required" => true,
                "constraints" => [
                    new Length([
                        "min" => 10,
                        "minMessage" => "Le titre doit comprendre au moins {{ limit }} caractères.",
                        "max" => 60,
                        "maxMessage" => "Le titre doit comprendre au maximum {{ limit }} caractères.",
                    ]),
                    new NotBlank([
                        "message" => "Ce champs ne peut être laisser vide."
                    ]),
                    new Regex([
                        "pattern" => "/^([A-Z])(?=.*?[a-zA-Z]{9,60})$/",
                        "message" => "Format invalide! Le titre doit commencer par une majuscule et ne peut contenir que des lettres.",
                    ])
                ],
            ])
            ->add('subtitle', TextType::class, [
                "label" => "Sous-titre de l'article:",
                "required" => true,
                "constraints" => [
                    new Length([
                        "min" => 4,
                        "minMessage" => "Le sous-titre doit comprendre au moins {{ limit }} caractères.",
                        "max" => 20,
                        "maxMessage" => "Le sous-titre doit comprendre au maximum {{ limit }} caractères.",
                    ]),
                    new NotBlank([
                        "message" => "Ce champs ne peut être laisser vide."
                    ]),
                    new Regex([
                        "pattern" => "/^[A-Za-z]{4,20}(?: [A-Za-z]{4,20})?$/",
                        "message" => "Format invalide! Le sous-titre doit contenir 2 mots maximum, aucuns chiffres, 20 caractères maximum",
                    ])
                ],
            ])
            ->add('image', FileType::class, [
                "data_class" => null,
                "label" => "Image de présentation:",
                "required" => true,
                "constraints" => [
                    new Image([
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png",
                            "image/webp",
                        ],
                        "mimeTypesMessage" => "Seuls les formats .PNG, .JPEG et .WEBP sont acceptés.",
                        "maxSize" => "20480k",
                        "maxSizeMessage" => "La taille du fichier ne peut pas dépasser 20mo.",
                        "extensions" => ["jpg", "jpeg", "png", "webp"],
                        "extensionsMessage" => "Seules les extensions .jpg, .jpeg, .png et .webp sont autorisées."
                    ])
                ],
            ])
            ->add('content', TextareaType::class, [
                "label" => "Contenu:",
                "required" => true,
                "constaints" => [
                    new Length([
                        "min" => 1000,
                        "minMessage" => "Le contenu doit comprendre au moins {{ limit }} caractères. (Environ 200 mots)",
                        "max" => 10000,
                        "maxMessage" => "Le contenu doit comprendre au maximum {{ limit }} caractères. (Environ 2000 mots)",
                    ]),
                    new NotBlank([
                        "message" => "Ce champs ne peut être laisser vide."
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
