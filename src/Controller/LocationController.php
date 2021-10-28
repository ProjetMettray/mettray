<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    /**
     * @Route("/location", name="location")
     */
    public function index(LocationRepository $locationRepository): Response
    {
        return $this->render('location/index.html.twig', [
            'location' => $locationRepository->findAll(),
        ]);
    }

    public function addLocation(Request $request): Response
    {
        $location = $this->getLocation();
        
        $location = new Location();
        $form =$this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute()
        }

        return $this->renderForm('location/index.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }
}
