<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Booking;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de l\'évenement',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-form'
                ],
            ])
            ->add('start_at', DateTimeType::class, array(
                'label' => 'Date de début',
                'input' => 'datetime_immutable',
                'date_widget' => 'single_text',
            ))
            ->add('end_at', DateTimeType::class, array(
                'label' => 'Date de fin',
                'input' => 'datetime_immutable',
                'date_widget' => 'single_text',
            ))
            // ->add('user_id')
            ->add('room_id', EntityType::class, [
                'class' => Room::class,
                'label' => 'Selectionnez une salle',
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
                'by_reference' => false,
                //'query_builder' => function (EntityRepository $er) {
                //    return $er->createQueryBuilder('r')
                //        ->where('r.id' == $this->getId());
                //}
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'mt-2 btn btn-secondary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
