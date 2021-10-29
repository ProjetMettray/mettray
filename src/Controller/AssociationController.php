<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AssociationController extends AbstractController
{
     /**
     * @Route("/association", name="association")
     */
    public function index(): Response
    {
        return $this->render('association/index.html.twig', [
            'controller_name' => 'AssociationController',
        ]);
    }

    /**
     * @Route("/association/add", name="association_add")
     * 
     */
    public function addAssociation(Request $request, EntityManagerInterface $entityManager)
    {
        $association = new Association();
        $addAssociationForm = $this->createForm(AssociationType::class, $association);

        $addAssociationForm->handleRequest($request);

        if($addAssociationForm->isSubmitted() && $addAssociationForm->isValid()) {
            $association = $addAssociationForm->getData();

            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('association_show', [
                'association' => $association->getId()
            ]);
        }

        return $this->render('association/add.html.twig', [
            'addAssociationForm' => $addAssociationForm->createView()
        ]);
    }

    /**
     * @Route("/associations/{association}/update", name="association_update")
     */
    public function updateAssociation(Association $association, Request $request, EntityManagerInterface $entityManager)
    {
        $updateAssociationForm = $this->createForm(AssociationType::class, $association);

        $updateAssociationForm->handleRequest($request);

        if($updateAssociationForm->isSubmitted() && $updateAssociationForm->isValid()) {
            $entityManager->flush();
        }

        return $this->render('association/update.html.twig', [
            'updateAssociationForm' => $updateAssociationForm->createView(),
            'associationName' => $association->getName()
        ]);
    }

    /**
     * @Route("/associations/{association}/delete", name="association_delete")
     */
    public function deleteAssociation(Association $association, EntityManagerInterface $entityManager)
    {
        $deleteMessage = $association->getName() . ' a bien été supprimé !';
        $entityManager->remove($association);
        $entityManager->flush();

        $this->addFlash('success', $deleteMessage);

        return $this->redirectToRoute('association');
    }

    /**
     * @Route("/associations/{association}", name="association_show")
     */
    public function showAssociation(Association $association)
    {
        return $this->render('association/show.html.twig', [
            'association' => $association
        ]);
    }
}
