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
            'locations' => $locations,
            'asso' => $asso

        ]);
    }
}
