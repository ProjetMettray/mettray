<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\User1Type;
use App\Form\UserPasswordType;
use App\Entity\AssociationUser;
use App\Form\AssociationUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class UserController extends AbstractController

{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    private EntityManagerInterface $em;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper, EntityManagerInterface $em)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->em = $em;
    }
    /**
     * @Route("/user", name="user_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function addUser(Request $request, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encodeur, MailerInterface $mailer)
    {
        $user = new User();
        $addUserForm = $this->createForm(UserType::class, $user);
        $addUserForm->handleRequest($request);

        if ($addUserForm->isSubmitted() && $addUserForm->isValid()) {
            $user = $addUserForm->getData();       
            $user->setPassword($encodeur->encodePassword($user,$user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();
            try {
                $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            } catch (ResetPasswordExceptionInterface $e) {
                // If you want to tell the user why a reset email was not sent, uncomment
                // the lines below and change the redirect to 'app_forgot_password_request'.
                // Caution: This may reveal if a user is registered or not.
                //
                // $this->addFlash('reset_password_error', sprintf(
                //     '%s - %s',
                //     $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
                //     $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
                // ));

                return $this->redirectToRoute('app_check_email');
            }
            $email = (new TemplatedEmail())
                ->from('mairie@mettray.com')
                ->to($addUserForm->get('email')->getData())
                ->subject('Merci de vous être inscrit !')
        
            // path of the Twig template to render
                ->htmlTemplate('user/email.html.twig')
        
            // pass variables (name => value) to the template
                ->context([
                    'firstname' =>$addUserForm->get('firstname')->getData(),
                    'lastname' =>$addUserForm->get('lastname')->getData(),
                    'resetToken' => $resetToken,
                ]);
            $mailer->send($email);
            $this->setTokenObjectInSession($resetToken);

            $this->addFlash('message', 'Création du compte réussi');
            return $this->redirectToRoute('user_add_association', ['id'=> $user->getId()]);
        }

        return $this->render('user/new.html.twig', [
            'addUserForm' => $addUserForm->createView()
        ]);
    }
    /**
     * @Route("/user/{id}/addassociation", name="user_add_association")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(User $id): Response
    {
        $this->denyAccessUnlessGranted('USER_DELETE',$id);
        $this->em->remove($id);
        $this->em->flush();
        return $this->redirectToRoute('user_index');
    }
    
}
