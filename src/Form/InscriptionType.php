<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('username')
            ->add('firstname')
            ->add('lastname')
            ->add('age', BirthdayType::class, [
                'placeholder' => 'Select a value',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'h-full-width',
                    "placeholder" => "Email de confirmation vous sera envoyer"
                ],
                'label' => "Email"
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    "class" => "h-full-width"
                ],
                'label' => "Mot de passe"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
