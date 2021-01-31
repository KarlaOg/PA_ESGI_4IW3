<?php
 

namespace App\Form;
 

use App\Entity\Offer;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class OfferType extends AbstractType

{

    public function buildForm(FormBuilderInterface $builder, array $options)

    {

        $builder

            ->add('name')

            ->add('decription',  TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])

            ->add('brandId')

            ->add('dateStart', DateType::class, array(
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')+10),
                ))

            ->add('dateEnd', DateType::class, array(
                    'widget' => 'choice',
                    'years' => range(date('Y'), date('Y')+10),
                    ))

            ->add('status', HiddenType::class, [
                'data' => 'abcdef',
            ])

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