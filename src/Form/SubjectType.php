<?php

namespace App\Form;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idSubject')
            ->add('courses')
            ->add('idClass')
            ->add('createdDate')
            ->add('updateDate')
            ->add('archivedDate')
            ->add('status')
            ->add('archivedBy')
            ->add('createdBy')
            ->add('updateBy')
            ->add('idTeacher')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
