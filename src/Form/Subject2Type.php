<?php

namespace App\Form;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Subject2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idSubject')
//            ->add('courses')
//            ->add('createdDate')
//            ->add('updateDate')
//            ->add('archivedDate')
//            ->add('status')
//            ->add('archivedBy')
//            ->add('createdBy')
//            ->add('updateBy')
            ->add('idTeacher')
//            ->add('idClass')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
