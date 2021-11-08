<?php

namespace App\Controller;

use App\Entity\Room;
use DateTimeImmutable;
use App\Entity\Booking;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Booking $booking, Request $request, RoomRepository $roomRepository )
    {
        $donnees = json_decode($request->getContent());

        $startDateForm = $donnees->start;
        $endDateForm = $donnees->end;

        $room = $roomRepository->findBy(['id' => $donnees->roomId]);

        $bookingsForRoom = $this->em->getRepository(Booking::class)->findBy(['room' => $room]);

        $sendForm = true;

        foreach ($bookingsForRoom as $bookingForRoom) {
            if (($startDateForm >= $bookingForRoom->getStartAt() && $startDateForm < $bookingForRoom->getEndAt()) || ($endDateForm > $bookingForRoom->getStartAt() && $endDateForm <= $bookingForRoom->getEndAt())) {
                $sendForm = false;
            }
        }
        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) &&
            isset($donnees->roomId) && !empty($donnees->roomId)
             &&
            $sendForm
        ) {
            $code = 200;

            if (!$booking) {
                $booking = new Booking;

                $code = 201;
            }

            $booking->setTitle($donnees->title);
            $booking->setStartAt(new DateTimeImmutable($donnees->start));
            $booking->setEndAt(new DateTimeImmutable($donnees->end));

            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            return new Response('Ok', $code);
        } else {
            return new Response('Mauvaise demande', 404);
        }
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
