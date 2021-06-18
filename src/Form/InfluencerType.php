<?php

namespace App\Form;

use App\Entity\Influencer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class InfluencerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true, //rajouter pr suprimer limage
                'download_uri' => true, //rajouter un download
                'image_uri' => true,
                'label' => 'Photo',

            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'trim' => true,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,

            ])
            ->add('siret', IntegerType::class, [
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
                'required' => false,
                'expanded' => true

            ])
            ->add('website', UrlType::class, [
                'label' => 'Site web',
                'property_path' => 'socialNetwork[Website]',
                'required' => false,
            ])
            ->add('instagram', UrlType::class, [
                'label' => 'Instagram',
                'property_path' => 'socialNetwork[Instagram]',
                'required' => false,
            ])
            ->add('tiktok', UrlType::class, [
                'label' => 'Tiktok',
                'property_path' => 'socialNetwork[Tiktok]',
                'required' => false,
            ])
            ->add('facebook', UrlType::class, [
                'label' => 'Facebook',
                'property_path' => 'socialNetwork[Facebook]',
                'required' => false,
            ])
            ->add('youtube', UrlType::class, [
                'label' => 'Youtube',
                'property_path' => 'socialNetwork[Youtube]',
                'required' => false,

            ])
            ->add('twitter', UrlType::class, [
                'label' => 'Twitter',
                'property_path' => 'socialNetwork[Twitter]',
                'required' => false,

            ])
            ->add('twitch', UrlType::class, [
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
