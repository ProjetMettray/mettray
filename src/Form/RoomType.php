<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Location;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Repository\RoomRepository;


class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la salle',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])

            ->add('nbPlace', NumberType::class, [
                'label' => 'Nombre de place',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la salle',
                'required' => false,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            ->add('visibility', ChoiceType::class, [
                'choices' => [
                    'Public' => 1,
                    'Privé' => 0
                ],
                'label' => 'Visibilité'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
