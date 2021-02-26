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
use Symfony\Component\Validator\Constraints as Assert;

use Vich\UploaderBundle\Form\Type\VichImageType;

class EditProfileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)

            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true, //rajouter pr suprimer limage
                'download_uri' => true, //rajouter un download
                'image_uri' => true,
            ]);
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Users::class,
    //     ]);
    // }
}
