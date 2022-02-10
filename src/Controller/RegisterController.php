<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid()) {
            $user = $registerForm->getData();

            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'registerForm' => $registerForm->createView()
        ]);
    }

    /**
     * @Route("/users/{user}/update", name="user_update")
     */
    public function update(User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $updateUserForm = $this->createForm(RegisterType::class, $user);

        $updateUserForm->handleRequest($request);

        if($updateUserForm->isSubmitted() && $updateUserForm->isValid()) {
            $user = $updateUserForm->getData();

            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profil');
        }

        return $this->render('register/update.html.twig', [
            'updateUserForm' => $updateUserForm->createView(),
            'userLastname' => $user->getLastname()
        ]);
    }
}
