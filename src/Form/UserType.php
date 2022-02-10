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



class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => "ROLE_USER",
                    'Mairie' => "ROLE_ADMIN",
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles' 
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Email'
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',      
                ],
                'label' => 'Nom'
                
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prénom'
            ])
            ->add('password',RepeatedType::class,[

                'type' => PasswordType::class,
                'invalid_message'=> "Les mdp sont pas identique",
                'first_options'=> [
                    'label'=>'Votre mot de passe',

                ],
                'second_options' => [
                    'label'=>'Votre mot de passe a nouveau ' ,
                ],
            ])
            ->add('phone', TelType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Téléphone'
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
