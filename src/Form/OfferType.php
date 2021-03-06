<?php


namespace App\Form;


use App\Entity\Brand;

use App\Entity\Offer;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OfferType extends AbstractType

{

    public function buildForm(FormBuilderInterface $builder, array $options)

    {

        $builder

            ->add('name', TextType::class, [
                'label' => 'Nom de l\'offre',
                'required' => true,
            ])

            ->add('description',  TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('field', ChoiceType::class, [
                'label' => 'Selectionner votre domaine',
                'required' => false,
                'choices' => array(
                    'Gaming' => 'Gaming',
                    'Beaute' => 'Beaute',
                    'Lifestyle' => 'Lifestyle',
                    'Streaming' => 'Streaming',
                    'Humour' => 'Humour',
                    'Horreur' => 'Horreur',
                    'Education' => 'Education',
                    'Exploration' => 'Exploration',
                    'Autre' => 'Autre',
                ),
                'multiple'  => true,
                'required' => false,
                'expanded' => true
            ])

            ->add('brandId', EntityType::class, [
                'class' => Brand::class
            ])

            ->add('dateStart', DateType::class, array(
                'label' => "Date de commencement",
                'widget' => 'choice',
                'data' => new \DateTime('now +1 day')
            ))

            ->add('dateEnd', DateType::class, array(
                'label' => "Date de fin",
                'widget' => 'choice',
                'data' => new \DateTime('now +2 day')
            ));
    }


    public function configureOptions(OptionsResolver $resolver)

    {

        $resolver->setDefaults([

            'data_class' => Offer::class,

        ]);
    }
}
