<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content')
            ->add('status')
            ->add('likes')
            ->add('dislike')
            ->add('createdDate')
            ->add('lastUpdatedDate')
            ->add('archivedDate')
            ->add('archivedBy')
            ->add('createdBy')
            ->add('idForum')
            ->add('lastUpdatedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
