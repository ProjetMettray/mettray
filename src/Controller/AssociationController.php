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

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/association/add", name="association_add")
     * @IsGranted("ROLE_ADMIN")
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

            return $this->redirectToRoute('user_index', [
                'association' => $association->getId()
            ]);
        }

        return $this->render('association/add.html.twig', [
            'associationForm' => $addAssociationForm->createView()
        ]);
    }

    /**
     * @Route("/associations/update/{association}", name="association_update")
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateAssociation(Association $association, Request $request, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ASSOCIATION_EDIT',$association);
        $updateAssociationForm = $this->createForm(AssociationType::class, $association);

        $updateAssociationForm->handleRequest($request);

        if($updateAssociationForm->isSubmitted() && $updateAssociationForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('association/update.html.twig', [
            'associationForm' => $updateAssociationForm->createView(),
            'associationName' => $association->getName(),
            "associationId" => $association->getId(),
        ]);
    }

    /**
     * @Route("/associations/delete/{association}", name="association_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteAssociation(Association $association, EntityManagerInterface $entityManager)
    {
        $deleteMessage = $association->getName() . ' a bien été supprimé !';
        $entityManager->remove($association);
        $entityManager->flush();

        $this->addFlash('success', $deleteMessage);

        return $this->redirectToRoute('user_index');
    }
}
