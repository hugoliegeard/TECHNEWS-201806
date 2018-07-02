<?php

namespace App\Article;

use App\Entity\Category;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->image_url = $options['image_url'];
        $this->slug      = $options['slug'];

        $builder
            # Champ TITLE
            ->add('title', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => "Titre de l'Article"
                ]
            ])
        ;

        if ($this->slug) {

            $builder
                ->add('slug', TextType::class, [
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Slug de l'Article"
                    ]
                ])
            ;

        }

        $builder
            # Champ CATEGORY
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'label' => false
            ])
            # Champ CONTENT
            ->add('content', CKEditorType::class, [
                'required' => true,
                'label' => false
            ])
            # Champ FEATUREDIMAGE
            ->add('featuredImage', FileType::class, [
                'required' => false,
                'label' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'dropify',
                    'data-default-file' => $this->image_url
                ]
            ])
            # Champs SPECIAL & SPOTLIGHT
            ->add('special', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ]
            ])
            ->add('spotlight', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ]
            ])
            # Bouton Submit
            ->add('submit', SubmitType::class, [
                'label' => 'Publier mon Article'
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Article::class
            'data_class' => ArticleRequest::class,
            'image_url' => null,
            'slug' => null
        ]);
    }

}