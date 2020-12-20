<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label'    =>'Image (JPG or PNG file)',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'imagine_pattern' => 'squared_thumbnail_small'
            ])
            ->add('title',TextType::class)
            ->add('description',TextareaType::class)
            ->add('createdAt', DateTimeType::class, [
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'd-print-none'
                ]
            ])
            ->add('updatedAt', DateTimeType::class,[
                'data' => new \DateTime(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
