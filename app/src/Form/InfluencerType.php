<?php

namespace App\Form;

use App\Entity\Influencer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InfluencerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('siret', TextType::class, [
                'required' => false,
            ])
            ->add('username', TextType::class, [
                'required' => false,
            ])
            ->add('instagram', TextType::class, [
                'label' => 'instagram',
                'property_path' => 'socialNetwork[instagram]',
                'required' => false,
            ])
            ->add('tiktok', TextType::class, [
                'label' => 'tiktok',
                'property_path' => 'socialNetwork[tiktok]',
                'required' => false,
            ])
            ->add('facebook', TextType::class, [
                'label' => 'facebook',
                'property_path' => 'socialNetwork[facebook]',
                'required' => false,

            ])
            ->add('youtube', TextType::class, [
                'label' => 'youtube',
                'property_path' => 'socialNetwork[youtube]',
                'required' => false,

            ])
            ->add('twitter', TextType::class, [
                'label' => 'twitter',
                'property_path' => 'socialNetwork[twitter]',
                'required' => false,

            ])
            ->add('twitch', TextType::class, [
                'label' => 'twitch',
                'property_path' => 'socialNetwork[twitch]',
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
