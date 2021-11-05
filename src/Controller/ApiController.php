<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Booking;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
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
    public function majEvent(?Booking $booking, Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$booking){
                // On instancie un rendez-vous
                $booking = new Booking;

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $booking->setTitle($donnees->title);
            // $booking->setDescription($donnees->description);
            $booking->setStartAt(new DateTimeImmutable($donnees->start));
            $booking->setEndAt(new DateTimeImmutable($donnees->end));

            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
