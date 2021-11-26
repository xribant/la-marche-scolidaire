<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre',
                    'class' => 'form-control form-control-lg form-control-a'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => true,
                'label' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'attr' => [
                    'placeholder' => 'jpeg/png Max 2Mb',
                    'class' => 'form-control form-control-lg form-control-a'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
