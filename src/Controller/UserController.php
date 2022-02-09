<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Association;
use App\Entity\AssociationUser;
use App\Form\UserType;
use App\Form\User1Type;
use App\Form\AssociationUserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController

{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/user", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository ,AssociationRepository $association): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'associations'=>$association->findAll(),
        ]);
    }

    /**
     * @Route("/user/add", name="user_add")
     * 
     */
    public function addUser(Request $request, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encodeur)
    {
        $user = new User();
        $addUserForm = $this->createForm(UserType::class, $user);
        $addUserForm->handleRequest($request);

        if ($addUserForm->isSubmitted() && $addUserForm->isValid()) {
            $user = $addUserForm->getData();       
            $user->setPassword($encodeur->encodePassword($user,$user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_add_association', ['id'=> $user->getId()]);
        }

        return $this->render('user/new.html.twig', [
            'addUserForm' => $addUserForm->createView()
        ]);
    }
    /**
     * @Route("/user/{id}/addassociation", name="user_add_association")
     * 
     */
    public function addAssociation(User $id, Request $request, EntityManagerInterface $entityManager)
    {
        $association = New AssociationUser();
        $user = $this->em->getRepository(User::class)->findOneById($id);
        $addAssociationUserForm = $this->createForm(AssociationUserType::class, $association);
        $addAssociationUserForm->handleRequest($request);

        if ($addAssociationUserForm->isSubmitted() && $addAssociationUserForm->isValid()) {
            $association = $addAssociationUserForm->getData();
            $association->setUser($user);
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/addAssociation.html.twig', [
            'addAssociationUserForm' => $addAssociationUserForm->createView()
        ]);
    }
    /**
     * @Route("/user/password/{id}", name="user_password", methods={"GET","POST"})
     */
    public function register(User $id,EntityManagerInterface $entityManager,Request $request,UserPasswordEncoderInterface $encodeur): Response
    {
        $updatePassWordForm = $this->createForm(UserPasswordType::class, $id);
        $updatePassWordForm->handleRequest($request);
        if($updatePassWordForm->isSubmitted() && $updatePassWordForm->isValid()) {
            $password = $updatePassWordForm->getData();

            $password->setPassword($encodeur->encodePassword($password,$password->getPassword()));


            $entityManager->persist($password);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/password.html.twig',[
            'update_password_form' => $updatePassWordForm->createView()
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('USER_SHOW',$user);
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit")
     */
    public function edit(Request $request, User $id): Response
    {
        $this->denyAccessUnlessGranted('USER_EDIT',$id);
        $form = $this->createForm(User1Type::class, $id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $id,
            'UserUpdateForm' => $form,
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function delete(User $id): Response
    {
        $this->denyAccessUnlessGranted('USER_DELETE',$id);
        $this->em->remove($id);
        $this->em->flush();
        return $this->redirectToRoute('user_index');
    }
    
}
