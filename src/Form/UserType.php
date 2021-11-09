<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Association;
use App\Repository\RoomRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\AssociationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Collaborateur et Candidat' => 'ROLE_USER',
                    'Commerial' => 'ROLE_ADMIN',
                    'Administrateur' => 'ROLE_SUPER_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles' 
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control'
            ],
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
            ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
            ],
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
            ],
            ])
            ->add('associations', EntityType::class, [
                'class' => Association::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name',
                'query_builder' => function (AssociationRepository $er) {
                    return $er->createQueryBuilder('u')                 
                    ->orderBy('u.name', 'ASC');
                }
            ])
            ->add('rooms', EntityType::class, [
                'class' => Room::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name',
                'query_builder' => function (RoomRepository $er) {
                    return $er->createQueryBuilder('u')                 
                    ->orderBy('u.name', 'ASC');
                }
            ])
            ->add('save', ButtonType::class, [
                'attr' => ['class' => 'save'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
