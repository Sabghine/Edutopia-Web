<?php

namespace App\Form;

use App\Entity\Specialties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialtiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('name')
//            ->add('lastname')
            ->add('specialty')
            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    '1ére' => '1ére',
                    '2émé' => '2émé',
                    '3éme' => '3éme',
                    '4éme' => '4éme',
                    '5éme' => '5éme'
                ]
            ])
//            ->add('createdDate')
//            ->add('updateDate')
//            ->add('archivedDate')
//            ->add('status')
//            ->add('createdBy')
//            ->add('archivedBy')
//            ->add('updateBy')
            ->add('idteacher')
            ->add('idDep')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Specialties::class,
        ]);
    }
}
