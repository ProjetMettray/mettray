<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Entity\Association;
use App\Repository\RoomRepository;
use App\Repository\BookingRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Math;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @IsGranted("ROLE_USER")
     * 
     */
    public function index(BookingRepository $BookingRepository, RoomRepository $RoomRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $BookingRepository->findAll(),
            'rooms' => $RoomRepository->findAll()
        ]);
    }

    /**
     * @Route("/booking/show_all_by_user", name="booking_show_all_by_user")
     * @IsGranted("ROLE_USER")
     */
    public function showAllByUser(): Response
    {
        $currentUserId = $this->getUser()->getId();
        $userAssociations = [];
        $associationsOfUser = [];
        $bookingsByAssociation = [];

        $userAssociations = $this->em->getRepository(Association::class)->findByUserId($currentUserId);
        foreach ($userAssociations as $userAssociation) {
            $associationsOfUser[$userAssociation->getId()] = $userAssociation->getName();
        }
        //dd($associationsOfUser);

        foreach ($associationsOfUser as $associationId => $associationName) {
            $bookingsByAssociation[$associationName] = $this->em->getRepository(Booking::class)->findByAssociation($associationId);
        }

        return $this->render('booking/show_all_by_user.html.twig', [
            'userId' => $currentUserId,
            'userAssociations' => $userAssociations,
            'bookingsByAssociation' => $bookingsByAssociation
        ]);
    }

    /**
     * @Route("/booking/new/{room}", name="booking_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, Room $room): ?Response
    {
        $booking = new Booking();

        $userId = $this->getUser()->getId();
        $userAssociations = [];

        $userHasAssociations = $this->em->getRepository(Association::class);
        foreach ($userHasAssociations as $userHasAssociation) {
            $userAssociations[] = $this->em->getRepository(Association::class)->findById($userHasAssociation->getAssociation());
        }

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formObject = $form->getData();
            $startDateForm = $formObject->getStartAt();
            $endDateForm = $formObject->getEndAt();
            

            $bookingDayStart = date("w", $startDateForm->getTimestamp());
            $bookingDayEnd = date("w", $endDateForm->getTimestamp());


            if ($startDateForm === $endDateForm) {
                $booking->setDays([$bookingDayStart]);
            } else if ((($endDateForm->getTimestamp() - $startDateForm->getTimestamp()) / (60 * 60 * 24)) <= 7 ) {
                
                if ($booking->getDays() === []) {
                    $bookingDays = [];
                    $j = 0;
                    for ($i=0; $i<(($endDateForm->getTimestamp() - $startDateForm->getTimestamp()) / (60 * 60 * 24)); $i++) { 
                        if($bookingDayStart + $i < 7) {
                            array_push($bookingDays, $bookingDayStart + $i);
                        } else {
                            array_push($bookingDays, $j);
                            $j++;
                        }
                    }
                    $booking->setDays($bookingDays);
                }
            } else {
                //dd($booking->getDays());
                if ($booking->getDays() === []) {
                    $booking->setDays([0, 1, 2, 3, 4, 5, 6]);
                }
            }

            $booking->setRoom($room);
            $booking->setStatus('En attente');

            $roomForm = $formObject->getRoom();
            $bookingsForRoom = $this->em->getRepository(Booking::class)->findByRoom($roomForm);

            $sendForm = true;
            $errorSend = false;

            foreach ($bookingsForRoom as $bookingForRoom) {
                if (($startDateForm >= $bookingForRoom->getStartAt() && $startDateForm < $bookingForRoom->getEndAt()) || ($endDateForm > $bookingForRoom->getStartAt() && $endDateForm <= $bookingForRoom->getEndAt()) || ($startDateForm < $bookingForRoom->getStartAt() && $endDateForm > $bookingForRoom->getEndAt())) {
                    if (array_intersect($bookingForRoom->getDays(), $booking->getDays()) !== []) {
                        
                        if (($formObject->getStarttime() >= $bookingForRoom->getStarttime() && $formObject->getStarttime() < $bookingForRoom->getEndtime()) || ($formObject->getEndtime() > $bookingForRoom->getStarttime() && $formObject->getEndtime() <= $bookingForRoom->getEndtime()) || ($formObject->getStarttime() < $bookingForRoom->getStartTime() && $formObject->getEndtime() > $bookingForRoom->getEndTime())) {
                            
                            if (!$errorSend) {
                                $form->get('start_at')->addError(new FormError('Ce créneau de dates est déjà pris!'));
                                $sendForm = false;
                                $errorSend = true;
                            } 
                        }  
                    } else {
                        
                        if (!$errorSend) {
                            if (($formObject->getStarttime() >= $bookingForRoom->getStarttime() && $formObject->getStarttime() < $bookingForRoom->getEndtime()) || ($formObject->getEndtime() > $bookingForRoom->getStarttime() && $formObject->getEndtime() <= $bookingForRoom->getEndtime()) || ($formObject->getStarttime() < $bookingForRoom->getStartTime() && $formObject->getEndtime() > $bookingForRoom->getEndTime())) {
                                $form->get('start_at')->addError(new
                                
                                FormError('Ce créneau de dates est déjà pris!'));
                                $sendForm = false;
                                $errorSend = true;
                            }
                        }
                    }                  
                }  
            }

            if ($booking->getEndAt() < $booking->getStartAt()) {
                $form->get('start_at')->addError(new FormError('La date de fin doit être supérieure à la date de début!'));
                $sendForm = false;
            }
            
            if ($sendForm) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($booking);
                $entityManager->flush();

                return $this->redirectToRoute('main', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('booking/new.html.twig', [
            'room' => $room,
            'booking' => $booking,
            'bookingForm' => $form
        ]);
    }

    /**
     * @Route("/booking/edit/{id}", name="booking_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
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
            'room' => $booking->getRoom(),
            'booking' => $booking,
            'bookingForm' => $form,
            'bookingName' => $booking->getTitle(),
        ]);
    }

    /**
     * @Route("/booking/delete/{id}", name="booking_delete", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Booking $booking): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($booking);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
