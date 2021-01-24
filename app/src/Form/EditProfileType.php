<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('image', FileType::class, [
                'label' => 'image (PDF file)',

                // non mappé signifie que ce champ n'est associé à aucune propriété d'entité
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => array(
                    'Youtubeur' => 'Youtubeur',
                    'Influenceur' => 'Influenceur',
                    'Gamer' => 'Gamer',
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

