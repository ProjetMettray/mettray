<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
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

        $bookings = $bookings[0]->getBookings();

        $rdvs = [];
        foreach ($bookings as $booking) {
            $rdvs[] = [
                'id' => $booking->getId(),
                'start' => $booking->getStartAt(),
                'end' => $booking->getEndAt(),
                'title' => $booking->getTitle(),
            ];
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