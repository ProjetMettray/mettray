<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(BookingRepository $booking): Response
    {
        $events = $booking->findAll();

        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStartAt(),
                'end' => $event->getEndAt(),
                'title' => $event->getTitle(),
            ];
        }

        $data = json_encode($rdvs);
        return $this->render('main/index.html.twig', [
            'data' => $data
        ]);
    }
}
