<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
     /**
     * @Route("/room", name="room")
     */
    public function index(): Response
    {
        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
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

        if($addRoomForm->isSubmitted() && $addRoomForm->isValid()) {
            $room = $addRoomForm->getData();

            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('room_show', [
                'room' => $room->getId()
            ]);
        }

        return $this->render('room/add.html.twig', [
            'addRoomForm' => $addRoomForm->createView()
        ]);
    }

    /**
     * @Route("/rooms/{room}/update", name="room_update")
     */
    public function updateRoom(Room $room, Request $request, EntityManagerInterface $entityManager)
    {
        $updateRoomForm = $this->createForm(RoomType::class, $room);

        $updateRoomForm->handleRequest($request);

        if($updateRoomForm->isSubmitted() && $updateRoomForm->isValid()) {
            $entityManager->flush();
        }

        return $this->render('room/update.html.twig', [
            'updateRoomForm' => $updateRoomForm->createView(),
            'roomName' => $room->getName()
        ]);
    }

    /**
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

    /**
     * @Route("/rooms/{room}", name="room_show")
     */
    public function showRoom(Room $room)
    {
        return $this->render('room/show.html.twig', [
            'room' => $room
        ]);
    }
}
