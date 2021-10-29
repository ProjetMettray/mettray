<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
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
     * @Route("/location/add", name="location_add")
     * 
     */
    public function addLocation(Request $request, EntityManagerInterface $entityManager)
    {
        $location = new Location();
        $addLocationForm = $this->createForm(LocationType::class, $location);

        $addLocationForm->handleRequest($request);

        if($addLocationForm->isSubmitted() && $addLocationForm->isValid()) {
            $location = $addLocationForm->getData();

            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location_show', [
                'location' => $location->getId()
            ]);
        }

        return $this->render('location/add.html.twig', [
            'addLocationForm' => $addLocationForm->createView()
        ]);
    }

    /**
     * @Route("/locations/{location}/update", name="location_update")
     */
    public function updateLocation(Location $location, Request $request, EntityManagerInterface $entityManager)
    {
        $updateLocationForm = $this->createForm(LocationType::class, $location);

        $updateLocationForm->handleRequest($request);

        if($updateLocationForm->isSubmitted() && $updateLocationForm->isValid()) {
            $entityManager->flush();
        }

        return $this->render('location/update.html.twig', [
            'updateLocationForm' => $updateLocationForm->createView(),
            'locationName' => $location->getName()
        ]);
    }

    /**
     * @Route("/locations/{location}/delete", name="location_delete")
     */
    public function deleteLocation(Location $location, EntityManagerInterface $entityManager)
    {
        $deleteMessage = $location->getName() . ' a bien été supprimé !';
        $entityManager->remove($location);
        $entityManager->flush();

        $this->addFlash('success', $deleteMessage);

        return $this->redirectToRoute('location');
    }

    /**
     * @Route("/locations/{location}", name="location_show")
     */
    public function showLocation(Location $location)
    {
        return $this->render('location/show.html.twig', [
            'location' => $location
        ]);
    }
}
