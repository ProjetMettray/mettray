<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Booking;
use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainController extends AbstractController
{
    protected $auth;

    public function __construct(AuthorizationCheckerInterface $auth, EntityManagerInterface $em)
    {
        $this->auth = $auth;
        $this->em = $em;
    }

    private EntityManagerInterface $em;

    /**
     * @Route("/", name="main")
     */
    public function index(LocationRepository $location): Response
    {
        $asso = $this->em->getRepository(Association::class)->findAll();
        $room = $this->em->getRepository(Room::class)->findAll();


        $locations = $location->findAll();

        return $this->render('main/index.html.twig', [

            'locations' => $locations,
            'asso' => $asso,
            'room' => $room,

        ]);
    }
    /**
     * @Route("/main/{id}", name="main_room")
     */
    public function showRoom(Room $id)
    {
        $asso = $this->em->getRepository(Association::class)->findAll();
        $room = $this->em->getRepository(Room::class)->findAll();

        $bookings = $this->em->getRepository(Room::class)->findBy(['id' => $id]);

        $userId = $this->getUser()->getId();
        $userAssociations = [];
        $bookingsForUser = [];
        $bookingsIdForUser = [];

        $userHasAssociations = $this->em->getRepository(AssociationUser::class)->findByUser($userId);
        foreach ($userHasAssociations as $userHasAssociation) {
            $userAssociations[$userHasAssociation->getAssociation()->getId()] = $userHasAssociation->getAssociation()->getName();
        }

        foreach ($userAssociations as $associationId => $associationName) {
            $bookingsForUser[] = $this->em->getRepository(Booking::class)->findByAssociation($associationId);
        }

        foreach ($bookingsForUser as $bookingArrayForUser) {
            foreach ($bookingArrayForUser as $bookingsForUser) {
                $bookingsIdForUser[] = $bookingsForUser->getId();
            }
        }

        $bookings = $bookings[0]->getBookings();

        $rdvs = [];
        foreach ($bookings as $booking) {
            if ($this->auth->isGranted('ROLE_ADMIN') || in_array($booking->getId(), $bookingsIdForUser)) {
                $rdvs[] = [
                    'id' => $booking->getId(),
                    'start' => $booking->getStartAt(),
                    'end' => $booking->getEndAt(),
                    'title' => $booking->getTitle(),
                    'roomId' => $booking->getRoom()->getId(),
                    'asso' => $booking->getAssociation()->getName(),
                    'phone' => $booking->getAssociation()->getTelephone(),
                    'email' => $booking->getAssociation()->getEmail(),
                    // 'lastname' => $booking->getAssociation()->getAssociationUsers()->getUser()->getLastName(),
                    // 'firstname' => $booking->getAssociation()->getAssociationUsers()->getUser()->getFirstName(),
                ];
            } else {
                $rdvs[] = [
                    'title' => 'Informations indisponible',
                    'asso' => $booking->getAssociation()->getName(),
                    'id' => $booking->getId(),
                    'start' => $booking->getStartAt(),
                    'end' => $booking->getEndAt(),
                    'backgroundColor' => 'grey',
                    'textColor' => 'grey',
                    'phone' => $booking->getAssociation()->getTelephone(),
                    'email' => $booking->getAssociation()->getEmail(),
                ];
            }
                
        }
        $data = json_encode($rdvs);

        return $this->render('main/room.html.twig', [
            'data' => $data,
            'roomid' => $id,
            'asso' => $asso,
            'room' => $room
        ]);
    }
}
