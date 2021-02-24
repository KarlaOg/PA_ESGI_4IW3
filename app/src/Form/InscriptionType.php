<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new Length(['min' => 3]),
                    new Regex('/^[a-zA-Z]+$/i')

                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'constraints' => [
                    new Length(['min' => 2]),
                    new Regex('/^[a-zA-Z]+$/i')
                ]
            ])
            ->add('age', BirthdayType::class, [
                'label' => 'Age',
                'placeholder' => 'Selectionner une valeur',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Vous êtes',
                'choices' => array(
                    'Marque' => "ROLE_MARQUE",
                    'Influenceur' => 'ROLE_INFLUENCEUR',

                ),
                'multiple'  => true,
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'h-full-width',
                ],
                'label' => "Email",
            ])
            ->add('password', RepeatedType::class, [
                'label' => 'Mot de passe',
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'constraints' => [new Length(['min' => 8])]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
