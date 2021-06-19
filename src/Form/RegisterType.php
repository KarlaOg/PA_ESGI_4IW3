<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Influencer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\CallbackTransformer;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class RegisterType extends AbstractType
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
                'label' => 'Date de Naissance',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Vous êtes',
                'choices' => array(
                    'Selectionnez votre profil' => "",
                    'Marque' => "ROLE_MARQUE",
                    'Influenceur' => 'ROLE_INFLUENCEUR',
                ),
                'required' => true,
                'multiple' => false,
                'expanded' => false
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
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer mot de passe'],
                'constraints' => [new Length(['min' => 8])]
            ])
            ->add('isAdmin', HiddenType::class, [
                'empty_data' => 0
            ]) ;


            $formModifier = function (FormInterface $form, User $user = null) {
                $roles = null === $user? [] : $user->getRoles();
    
                $form->add('brand', BrandType::class);
                $form->add('influencer', InfluencerType::class);
            };
            
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();

                    $formModifier($event->getForm(), $data->getBrand());
     
                }
            );

            $builder->get('roles')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    // It's important here to fetch $event->getForm()->getData(), as
                    // $event->getData() will get you the client data (that is, the ID)
                    $user = $event->getForm()->getData();
    
                    // since we've added the listener to the child, we'll have to pass on
                    // the parent to the callback functions!
                    $formModifier($event->getForm()->getParent(), $user);
                }
            );

            $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
