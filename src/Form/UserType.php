<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Repository\RoomRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\AssociationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Mairie' => 'ROLE_ADMIN',
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
            ->add('associationUsers', EntityType::class, [
                'class' => Association::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'name',
                'query_builder' => function (AssociationRepository $er) {
                    return $er->createQueryBuilder('u')                 
                    ->orderBy('u.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
