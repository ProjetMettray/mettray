<?php

namespace App\Form;

use App\Entity\Association;
use App\Entity\Room;
use App\Entity\User;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
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
                'label' => "Nom de l'association",
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail',
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
            ->add('rooms', EntityType::class, [
                'label' => "Salles liées à l'association",
                'attr' => [
                    'class' => ''
                ],
                'class' => Room::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'name',
                'query_builder' => function (RoomRepository $er) {
                    return $er->createQueryBuilder('u')                 
                    ->orderBy('u.name', 'ASC');
                }
            ])
            ->add('users', EntityType::class, [
                'label' => "Personnes de l'association",
                'attr' => [
                    'class' => ''
                ],
                'class' => User::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'lastname',
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lastname', 'ASC');
                }
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'mt-2 btn fc-button-primary'],
                'label'=> 'Envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
