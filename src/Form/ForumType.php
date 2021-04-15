<?php

namespace App\Form;

use App\Entity\Forum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject')
            ->add('status')
            ->add('createdDate')
            ->add('lastUpdatedDate')
            ->add('archivedDate')
            ->add('archivedBy')
            ->add('createdBy')
            ->add('lastUpdatedBy')
            ->add('idCourse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Forum::class,
        ]);
    }
}
