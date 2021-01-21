<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{

    const YOUTUBER = 'Youtuber';
    const INFLUENCER = 'Influencer';
    const GAMER = 'Gamer';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => array(
                    'Youtuber' => self::YOUTUBER,
                    'Influencer' => self::INFLUENCER,
                    'Gamer' => self::GAMER,
                ),
                'multiple'  => true,
                'expanded' => true,
            ])
            ->add('Valider', SubmitType::class);
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Users::class,
    //     ]);
    // }
}