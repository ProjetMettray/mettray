<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Repository\RoomRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\AssociationRepository;
use App\Repository\AssociationUserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;

class RegisterType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Mairie' => 'ROLE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Droits d\'accès'
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
                'class' => AssociationUser::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'association'
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
