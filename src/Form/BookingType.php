<?php

namespace App\Form;

use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Entity\Room;
use App\Entity\Booking;
use App\Repository\AssociationRepository;
use App\Repository\AssociationUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Security\Core\Security;

class BookingType extends AbstractType
{

    private $associationUserRepository;
    private $security;

    public function __construct(Security $security, AssociationUserRepository $associationUserRepository)
    {
        $this->security = $security;
        $this->associationUserRepository = $associationUserRepository;
    }

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
            ->add('association', EntityType::class, [
                'class' => Association::class,
                'label' => 'Association',
                'choice_label' => 'name',
                'query_builder' => function(AssociationRepository $er) {
                    if($this->security->isGranted('ROLE_ADMIN')){
                        return $er->createQueryBuilder('s');
                    } else {
                        return $er->queryOwnedBy($this->security->getUser()->getId());
                    }
                },
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'mt-2 btn btn-secondary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'userAssociations' => null
        ]);
    }
}
