<?php

namespace App\Form;

use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('ownername')
            ->add('ownerlastname')
            ->add('createdDate')
            ->add('lastUpdatedDate')
            ->add('archivedDate')
            ->add('status')
            ->add('specialties')
            ->add('ownerid')
            ->add('archivedBy')
            ->add('createdBy')
            ->add('lastUpdatedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
