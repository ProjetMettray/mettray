<?php

namespace App\Form;

use App\Entity\Room;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RoomType extends AbstractType
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

            ->add('nbPlace',NumberType::class, [
                'label' => 'Nombre de place',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            ->add('location', ChoiceType::class, [
                'group_by' => ChoiceList::groupBy($this, 'name'),
            ])
            //->add('room_has_user')
            ->add('room')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
