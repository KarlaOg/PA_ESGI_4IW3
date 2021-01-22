<?php
 

namespace App\Form;
 

use App\Entity\Offer;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
 
use Symfony\Component\Form\Extension\Core\Type\DateType;

class OfferType extends AbstractType

{

    public function buildForm(FormBuilderInterface $builder, array $options)

    {

        $builder

            ->add('name')

            ->add('decription')

            ->add('brandId')

            ->add('dateStart', DateType::class)

            ->add('dateEnd', DateType::class)

            ->add('status')

            ->add('application')

        ;

    }
 

    public function configureOptions(OptionsResolver $resolver)

    {

        $resolver->setDefaults([

            'data_class' => Offer::class,

        ]);

    }

}