<?php


namespace App\Form;


use App\Entity\Offer;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class OfferType extends AbstractType

{

    public function buildForm(FormBuilderInterface $builder, array $options)

    {

        $builder

            ->add('name', TextType::class, [
                'label' => 'Nom de l\'offre',
                'required' => true,
            ])

            ->add('decription',  TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'tinymce'],
            ])

            ->add('brandId', HiddenType::class, [
                'label' => 'Id de l\'offre',
            ])

            ->add('dateStart', DateType::class, array(
                'label' => "Date de commencement",
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y') + 10),
            ))

            ->add('dateEnd', DateType::class, array(
                'label' => "Date de fin",
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y') + 10),
            ))

            ->add('status', HiddenType::class, [
                'data' => 'Libre',
            ])

            ->add('application');
    }


    public function configureOptions(OptionsResolver $resolver)

    {

        $resolver->setDefaults([

            'data_class' => Offer::class,

        ]);
    }
}
