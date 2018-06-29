<?php

namespace App\User;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstName', TextType::class, [
                'label' => 'Saisissez votre Prénom',
                'attr'  => [
                    'placeholder' => 'Saisissez votre Prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Saisissez votre Nom',
                'attr'  => [
                    'placeholder' => 'Saisissez votre Nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Saisissez votre Email',
                'attr'  => [
                    'placeholder' => 'Saisissez votre Email'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Saisissez votre Mot de Passe',
                'attr'  => [
                    'placeholder' => 'Saisissez entre 8 et 20 caractères.'
                ]
            ])
            ->add('conditions', CheckboxType::class,[
                'label' => "J'accepte les Conditions Générales d'Utilisation",
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Je m'inscris"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' =>  UserRequest::class
        ]);
    }

}