<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Booking;
use App\Entity\Association;
use App\Entity\User;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/main", name="main")
     * @IsGranted("ROLE_USER")
     */
    public function index(LocationRepository $location): Response
    {
        $asso = $this->em->getRepository(Association::class)->findAll();
        $room = $this->em->getRepository(Room::class)->findAll();
        $currentUserId = $this->getUser()->getId();

        $associations = $this->em->getRepository(Association::class)->findByUserId($currentUserId);
        $locations = $location->findAll();

        return $this->render('main/index.html.twig', [

            'locations' => $locations,
            'asso' => $asso,
            'associations' => $associations,
            'room' => $room,

        ]);
    }
    /**
     * @Route("/main/{id}", name="main_room")
     * @IsGranted("ROLE_USER")
     */
    public function showRoom(Room $id)
    {
        $asso = $this->em->getRepository(Association::class)->findAll();
        $room = $this->em->getRepository(Room::class)->findAll();
        $currentUserId = $this->getUser()->getId();

        $userAssociations = $this->em->getRepository(Association::class)->findByUserId($currentUserId);

        $bookings = $this->em->getRepository(Room::class)->findBy(['id' => $id]);
        $bookingsForUser = [];
        $bookingsIdForUser = [];

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
                    'starttime' => $booking->getStarttime()->format('H:i:s'),
                    'endtime' => $booking->getEndtime()->format('H:i:s'),
                    'days' => $booking->getDays(),
                    'title' => $booking->getTitle(),
                    'roomId' => $booking->getRoom()->getId(),
                    'asso' => $booking->getAssociation() ? $booking->getAssociation()->getName() : '',
                    'phone' => $booking->getAssociation() ? $booking->getAssociation()->getTelephone() : '',
                    'email' => $booking->getAssociation() ? $booking->getAssociation()->getEmail() : '',
                ];
            } else {
                $rdvs[] = [
                    'title' => 'Informations indisponible',
                    'asso' => $booking->getAssociation()->getName(),
                    'id' => $booking->getId(),
                    'start' => $booking->getStartAt(),
                    'end' => $booking->getEndAt(),
                    'starttime' => $booking->getStarttime()->format('H:i:s'),
                    'endtime' => $booking->getEndtime()->format('H:i:s'),
                    'days' => $booking->getDays(),
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
