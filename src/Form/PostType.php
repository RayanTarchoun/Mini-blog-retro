<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'nes-input', 'placeholder' => 'Titre de l\'article...'],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['class' => 'nes-textarea', 'rows' => 10, 'placeholder' => 'Écrivez votre article ici...'],
            ])
            ->add('picture', UrlType::class, [
                'label' => 'URL de l\'image',
                'required' => false,
                'attr' => ['class' => 'nes-input', 'placeholder' => 'https://example.com/image.jpg'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'attr' => ['class' => 'nes-select'],
                'placeholder' => 'Choisir une catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
