<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\AssociationUser;
use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\AssociationRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AssociationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('association', EntityType::class, [
            'class' => Association::class,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'name',
            'query_builder' => function (AssociationRepository $er) {
                return $er->createQueryBuilder('u')                 
                ->orderBy('u.name', 'ASC');
            }
        ])
        ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AssociationUser::class,
        ]);
    }
}
