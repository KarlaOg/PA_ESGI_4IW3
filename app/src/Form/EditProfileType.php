<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
Use Symfony\Component\Form\TheChoiceType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => array(
                    'Youtubeur' => 'Youtubeur',
                    'Influenceur' => 'Influenceur',
                    'Gamer' => 'Gamer',
                ),
                'multiple'  => true,
                'expanded' => true,
            ]);
    }

}
