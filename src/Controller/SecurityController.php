<?php

namespace App\Controller;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();
        $publicRooms = [];
        foreach ($rooms as $room) {
            if ($room->getVisibility() == 1) {
                array_push($publicRooms, $room);
            }
        }
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'rooms' => $publicRooms
        ]);
    }

    /**
     * @Route("/calendar/{id}", name="calendar_login")
     */
    public function loginRooms(AuthenticationUtils $authenticationUtils, RoomRepository $roomRepository, ?Room $room): Response
    {
        $rooms = $roomRepository->findAll();
        $rdvs = [];
        $publicRooms = [];
        foreach ($rooms as $roo) {
            if ($roo->getVisibility() == 1) {
                array_push($publicRooms, $roo);
            }
        }
        if($room){
            foreach ($room->getBookings() as $booking) {
                if ($room->getVisibility() == 1) {
                    $rdvs[] = [
                        'id' => $booking->getId(),
                        'start' => $booking->getStartAt(),
                        'end' => $booking->getEndAt(),
                        'title' => 'Informations indisponible',
                        'roomId' => $booking->getRoom()->getId(),
                        'phone' => "",
                        'email' => ""
                    ];
                }
            }
        }

        $data = json_encode($rdvs);
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('app_logout');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login_room.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'data' => $data,
            'rooms' => $publicRooms,
            'roomid' => $room
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
