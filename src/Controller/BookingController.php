<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Entity\Booking;
use App\Entity\User;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BookingController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * 
     * @Route("/booking", name="booking")
     * 
     */
    public function index(BookingRepository $BookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $BookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/booking/show_all_by_user", name="booking_show_all_by_user")
     */
    public function showAllByUser(): Response
    {
        $userId = $this->getUser()->getId();
        $userAssociations = [];
        $bookingsByAssociation = [];

        $userHasAssociations = $this->em->getRepository(AssociationUser::class)->findByUser($userId);
        foreach ($userHasAssociations as $userHasAssociation) {
            $userAssociations[$userHasAssociation->getAssociation()->getId()] = $userHasAssociation->getAssociation()->getName();
        }

        foreach ($userAssociations as $associationId => $associationName) {
            $bookingsByAssociation[$associationName] = $this->em->getRepository(Booking::class)->findByAssociation($associationId);
        }

        return $this->render('booking/show_all_by_user.html.twig', [
            'userId' => $userId,
            'userAssociations' => $userAssociations,
            'bookingsByAssociation' => $bookingsByAssociation
        ]);
    }

    /**
     * @Route("/booking/new", name="booking_new", methods={"GET","POST"})
     */
    public function new(Request $request): ?Response
    {
        $booking = new Booking();

        $userId = $this->getUser()->getId();
        $userAssociations = [];

        $userHasAssociations = $this->em->getRepository(AssociationUser::class)->findByUser($userId);
        foreach ($userHasAssociations as $userHasAssociation) {
            $userAssociations[] = $this->em->getRepository(Association::class)->findById($userHasAssociation->getAssociation());
        }

        $form = $this->createForm(BookingType::class, $booking
        //, array(
        //    'userAssociations' => $userAssociations
        //)
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $booking->setStatus('En attente');

            $formObject = $form->getData();
            $startDateForm = $formObject->getStartAt();
            $endDateForm = $formObject->getEndAt();
            $roomForm = $formObject->getRoom();

            $bookingsForRoom = $this->em->getRepository(Booking::class)->findByRoom($roomForm);

            $sendForm = true;

            foreach ($bookingsForRoom as $bookingForRoom) {
                if (($startDateForm >= $bookingForRoom->getStartAt() && $startDateForm < $bookingForRoom->getEndAt()) || ($endDateForm > $bookingForRoom->getStartAt() && $endDateForm <= $bookingForRoom->getEndAt())) {
                    $form->get('start_at')->addError(new FormError('Ce créneau de dates est déjà pris!'));
                    $sendForm = false;
                }
                if ($bookingForRoom->getEndAt() < $bookingForRoom->getStartAt()) {
                    $form->get('start_at')->addError(new FormError('La date de fin doit être supérieure à la date de début!'));
                    $sendForm = false;
                }
            }

            if ($sendForm) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($booking);
                $entityManager->flush();

                return $this->redirectToRoute('main', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('booking/new.html.twig', [
            'booking' => $booking,
            'bookingForm' => $form
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/booking/edit/{id}", name="booking_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/edit.html.twig', [
            'booking' => $booking,
            'bookingForm' => $form
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_delete", methods={"POST"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking', [], Response::HTTP_SEE_OTHER);
    }
}
