<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
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
            'location' => $locationRepository->findAll()
        ]);
    }

    /**
     * @Route("/location_new", name="location_new", methods={"GET", "POST"})
     */
    public function addLocation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = $this->getLocation();
        
        $location = new Location();
        $form =$this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location');
        }

        return $this->renderForm('location/index.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }
}
