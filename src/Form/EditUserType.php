<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Brand;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('firstname')
            ->add('lastname')

            ->add('age', BirthdayType::class, [
                'label' => 'Date de Naissance',
            ])
            ->add('isAdmin', ChoiceType::class, [
                'label' => 'Admin ?',
                'choices' => [
                    'Oui' => "1",
                    'Non' => "0",

                ],

                'required' => true,
                'expanded' => true,

            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
