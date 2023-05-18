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
            ])
            ->add('subtitle', TextType::class, [
                "label" => "Sous-titre de l'article:",
                "required" => true,
            ])
            ->add('image', FileType::class, [
                "data_class" => null,
                "label" => "Image de présentation:",
                "required" => true,
            ])
            ->add('content', TextareaType::class, [
                "label" => "Contenu:",
                "required" => true,
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
