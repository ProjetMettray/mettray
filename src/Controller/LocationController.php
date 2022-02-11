<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;




class LocationController extends AbstractController
{
     /**
      * @Route("/location", name="location")
      *
     */
    public function index(LocationRepository $locationRepository): Response
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locationRepository->findAll()
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

        if ($addLocationForm->isSubmitted() && $addLocationForm->isValid()) {
            $location = $addLocationForm->getData();

            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location_show', [
                'id' => $location->getId()
            ]);
        }

        return $this->render('location/add.html.twig', [
            'addLocationForm' => $addLocationForm->createView()
        ]);
    }

    /**
     * @Route("/locations/{id}/update", name="location_update")
     */
    public function updateLocation(Location $location, Request $request, EntityManagerInterface $entityManager)
    {
        $updateLocationForm = $this->createForm(LocationType::class, $location);

        $updateLocationForm->handleRequest($request);

        if ($updateLocationForm->isSubmitted() && $updateLocationForm->isValid()) {
            $entityManager->flush();
        }

        return $this->render('location/update.html.twig', [
            'updateLocationForm' => $updateLocationForm->createView(),
            'locationName' => $location->getName(),
            'locationId' => $location->getId()
        ]);
    }

    /**
     * @Route("/locations/{id}/delete", name="location_delete")
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
     * @Route("/locations/{id}", name="location_show")
     */
    public function showLocation(Location $location)
    {
        return $this->render('location/show.html.twig', [
            'location' => $location
        ]);
    }
}
