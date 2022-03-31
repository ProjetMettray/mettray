<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RoomController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/room", name="room")
     * @IsGranted("ROLE_USER")
     *
     */
    public function index(RoomRepository $roomRepository,LocationRepository $locationRepository): Response
    {
        $location = $locationRepository->findAll();
        $rooms = $roomRepository->findAll();
        return $this->render('room/index.html.twig', [
            'location'=>$location,
            'rooms' => $rooms,
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/rooms/{id}", name="room_show")
     */
    public function showRoom(Room $id)
    {
        return $this->render('room/showOne.html.twig', [
            'room' => $id
        ]);
    }
    /**
     * @Route("/room/add", name="room_add")
     * 
     */
    public function addRoom(Request $request, EntityManagerInterface $entityManager)
    {
        $room = new Room();
        $addRoomForm = $this->createForm(RoomType::class, $room);

        $addRoomForm->handleRequest($request);

        if ($addRoomForm->isSubmitted() && $addRoomForm->isValid()) {
            $room = $addRoomForm->getData();

            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('room');
        }

        return $this->render('room/add.html.twig', [
            'form' => $addRoomForm->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/rooms/{room}/update", name="room_update")
     */
    public function updateRoom(Room $room, Request $request, EntityManagerInterface $entityManager)
    {
        $updateRoomForm = $this->createForm(RoomType::class, $room);

        $updateRoomForm->handleRequest($request);

        if ($updateRoomForm->isSubmitted() && $updateRoomForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('room', [
                'id' => $room->getId()
            ]);
        }

        return $this->render('room/update.html.twig', [
            'form' => $updateRoomForm->createView(),
            'roomName' => $room->getName()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/rooms/{room}/delete", name="room_delete")
     */
    public function deleteRoom(Room $room, EntityManagerInterface $entityManager)
    {
        $deleteMessage = $room->getName() . ' a bien été supprimé !';
        $entityManager->remove($room);
        $entityManager->flush();

        $this->addFlash('success', $deleteMessage);

        return $this->redirectToRoute('room');
    }
}
