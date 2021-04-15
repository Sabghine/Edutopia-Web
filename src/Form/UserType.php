<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role')
            ->add('name')
            ->add('lastName')
            ->add('cin')
            ->add('email')
            ->add('phoneNumber')
            ->add('birthDate')
            ->add('createdDate')
            ->add('lastUpdatedDate')
            ->add('archivedDate')
            ->add('classe')
            ->add('password')
            ->add('status')
            ->add('subjects')
            ->add('nbasbsece')
            ->add('mailParent')
            ->add('archivedBy')
            ->add('createdBy')
            ->add('lastUpdatedBy')
            ->add('depid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
