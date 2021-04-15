<?php

namespace App\Form;

use App\Entity\WorkDone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkDoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('workFile')
            ->add('status')
            ->add('score')
            ->add('uploadedDate')
            ->add('lastUpdatedDate')
            ->add('archivedDate')
            ->add('idActivity')
            ->add('archivedBy')
            ->add('lastUpdatedBy')
            ->add('uploadedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkDone::class,
        ]);
    }
}
