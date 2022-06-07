<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Form\AssociationUserType;
use App\Repository\RoomRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\AssociationRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;



class UsersType extends AbstractType
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
                return $er->createQueryBuilder('a');
            },
            'label' => 'Associations'
        ])
        ->add('user', EntityType::class, [
            'class' => User::class,
            'expanded' => false,
            'multiple' => false,
            'choice_label' => 'lastname',
            'query_builder' => function (UserRepository $er) {
                return $er->createQueryBuilder('u')                 
                ->orderBy('u.lastname', 'ASC');
            },
            'label' => 'Utilisateurs'
        ])
        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'mt-2 btn fc-button-primary'],
            'label'=> 'Ajouter'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AssociationUser::class
        ]);
    }
}