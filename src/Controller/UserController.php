<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    /**
     * @Route("/liste", name="liste" ,  methods={"GET"} )
     */

    public function GetUsers(UserRepository $repository, SerializerInterface $serializerInterface)
    {
        $users = $repository->findAll();
        $json = $serializerInterface->serialize($users,'json',['Groups'=>'users']);
        dump($json);
        die;

    }
    /**
     * @Route("/add" ,name="add_user")
     */

    public function addUser (Request $request,SerializerInterface  $serializer,EntityManagerInterface $em)
    {
        $content=$request->getContent();
        $data=$serializer->deserialize($content,User::class,'json');
         $em->persist($data);
         $em->flush();
         return new Response('User added successfully');



    }
    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, $name = null, \Swift_Mailer $mailer, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mail($name = null, $mailer, $userRepository);

            $user->setPassword($encoder->encodePassword($user, $form->get('password')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/email", name="email",  methods={"GET","POST"})
     */
    public function mail($name = null, \Swift_Mailer $mailer, UserRepository $userRepository)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('sabrine.mokhtar@esprit.tn')
            ->setTo('sabrine.mokhtar@esprit.tn')
            ->setBody(
                ' Welcome to your new E_learning platform "Edutopia" , your account has been created with success,Please check with the adminstration for more details  '
            );

        $mailer->send($message);
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }



}
