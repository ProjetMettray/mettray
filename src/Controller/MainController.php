<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="m ain")
     */
    public function index(BookingRepository $repositoryBooking): Response
    {
        $events = $repositoryBooking->findAll();

        $rdvs = [];
        foreach($events as $event)
        {
            $rdvs[] = [
            'id' => $event->getId(),
            'start' => $event->getStartAt(),
            'end' => $event->getEndAt(),
            'title' => $event->getTitle(),
            'options' => $event->getOptions(),
            'status' => $event->getStatus(),
            'room' => $event->getRoomId()->getName(),
            'backgroundColor' => 'red',
            'textColor' => 'white',
            ];
        }

        $data=json_encode($rdvs);
        return $this->render('main/index.html.twig', [
            'data' => $data
        ]);
    }
}
