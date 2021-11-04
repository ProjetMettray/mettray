<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(LocationRepository $location): Response
    {
        $locations = $location->findAll();
        $events = $location->findAll();

        // $rdvs = [];
        // foreach ($events as $event) {
        //     $rdvs[] = [
        //         'id' => $event->getId(),
        //         'start' => $event->getStartAt(),
        //         'end' => $event->getEndAt(),
        //         'title' => $event->getTitle(),
        //     ];
        // }

        // $data = json_encode($rdvs);
        return $this->render('main/index.html.twig', [
            // 'data' => $data,
            'locations' => $locations
        ]);
    }
}
