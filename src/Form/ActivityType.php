<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('deadline')
            ->add('workTodo')
            ->add('status')
            ->add('ceatedDate')
            ->add('lastUpdatedDate')
            ->add('archivedDate')
            ->add('archivedBy')
            ->add('createdBy')
            ->add('idCourse')
            ->add('lastUpdatedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
