<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Association;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            //->add('user_has_association')
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'mt-2 btn btn-secondary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
