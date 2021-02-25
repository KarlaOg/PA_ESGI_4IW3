<?php

namespace App\Form;

use App\Entity\Influencer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class InfluencerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('siret', TextType::class, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Selectionner votre domaine',
                'required' => false,
                'choices' => array(
                    'Gamer' => "Gamer",
                    'Mode' => 'Mode',
                    'Instagramer' => 'Instagramer',
                    'Youtubeur' => 'Youtubeur',
                    'Blogueur' => 'Blogueur',
                    'LifeStyle' => 'LifeStyle',
                    'Humour' => 'Humour',
                ),
                'multiple'  => true,
                'required' => true,
            ])
            ->add('website', TextType::class, [
                'label' => 'Website',
                'property_path' => 'socialNetwork[Website]',
                'required' => false,
            ])
            ->add('instagram', TextType::class, [
                'label' => 'Instagram',
                'property_path' => 'socialNetwork[Instagram]',
                'required' => false,
            ])
            ->add('tiktok', TextType::class, [
                'label' => 'Tiktok',
                'property_path' => 'socialNetwork[Tiktok]',
                'required' => false,
            ])
            ->add('facebook', TextType::class, [
                'label' => 'Facebook',
                'property_path' => 'socialNetwork[Facebook]',
                'required' => false,
            ])
            ->add('youtube', TextType::class, [
                'label' => 'Youtube',
                'property_path' => 'socialNetwork[Youtube]',
                'required' => false,

            ])
            ->add('twitter', TextType::class, [
                'label' => 'Twitter',
                'property_path' => 'socialNetwork[Twitter]',
                'required' => false,

            ])
            ->add('twitch', TextType::class, [
                'label' => 'Twitch',
                'property_path' => 'socialNetwork[Twitch]',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Influencer::class,
        ]);
    }
}
