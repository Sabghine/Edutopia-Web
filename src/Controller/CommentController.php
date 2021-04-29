<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Forum;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Expalmer\PhpBadWords\PhpBadWords as BadWords;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/index/{idForum}", name="comment_index", methods={"GET"},requirements={"idForum"="\d+"})
     */
    public function index(CommentRepository $commentRepository,Comment $comment,$idForum): Response
    {
        $subject=$comment->getIdForum()->getSubject();
        $forum=$comment->getIdForum();
        $nbr=$commentRepository->countAvailable("Available",$forum);
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->tri("Available",$idForum),
            'nbr'=>$nbr,
            'subject'=>$subject,
            'forum'=>$forum,
        ]);
    }
    /**
     * @Route("/{idForum}/indexUser", name="comment_indexUser", methods={"GET"})
     */
    public function indexUser(CommentRepository $commentRepository,Comment $comment): Response
    {
        $subject=$comment->getIdForum()->getSubject();
        $forum=$comment->getIdForum();
        $nbr=$commentRepository->countAvailable("Available",$comment->getIdForum());
        return $this->render('comment/indexUser.html.twig', [
            'comments' => $commentRepository->tri("Available",$comment->getIdForum()->getId()),
            'nbr'=>$nbr,
            'subject'=>$subject,
            'forum'=>$forum,
        ]);
    }

    /**
     * @Route("/new/{idForum}", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request,Comment $comment,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id' => 1]);
        $comment2 = new Comment();
        $idForum=$comment->getIdForum();
        $comment2->setIdForum($idForum);
        $form = $this->createForm(CommentType::class, $comment2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment2->setCreatedBy($user);
            $comment2->setStatus("Available");
            $comment2->setLikes(0);
            $comment2->setDislike(0);
            $comment2->setCreatedDate(new \DateTime('now'));
            $filterWords = new BadWords();
            $commentt = $form->get('content')->getData();
            $filterWords->setText($commentt);
            $testCommentt = $filterWords->checkAmong();
            $nn= $filterWords->dictionary;
            $ss=array('amine');

            if($testCommentt ==  true)
            {
                $replace=str_ireplace(implode(" ",$nn),'*******',$commentt);
                $replace2=str_ireplace(implode(" ",$ss),'*******',$replace);
                $comment2->setContent($replace2);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment2);
            $entityManager->flush();
            return $this->redirectToRoute('comment_index',['idForum'=>$idForum->getId()]);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/newUser/{idForum}", name="comment_newUser", methods={"GET","POST"})
     */
    public function newUser(Request $request,Comment $comment,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id' => 1]);
        $comment2 = new Comment();
        $idForum=$comment->getIdForum();
        $comment2->setIdForum($idForum);
        $form = $this->createForm(CommentType::class, $comment2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment2->setCreatedBy($user);
            $comment2->setStatus("Available");
            $comment2->setLikes(0);
            $comment2->setDislike(0);
            $comment2->setCreatedDate(new \DateTime('now'));
            $filterWords = new BadWords();
            $commentt = $form->get('content')->getData();
            $filterWords->setText($commentt);
            $testCommentt = $filterWords->checkAmong();
            $nn= $filterWords->dictionary;
            $ss=array('amine');

            if($testCommentt ==  true)
            {
                $replace=str_ireplace(implode(" ",$nn),'*******',$commentt);
                $replace2=str_ireplace(implode(" ",$ss),'*******',$replace);
                $comment2->setContent($replace2);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment2);
            $entityManager->flush();
            return $this->redirectToRoute('comment_indexUser',['idForum'=>$idForum->getId()]);
        }

        return $this->render('comment/newUser.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/details", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id' => 1]);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setLastUpdatedDate(new \DateTime('now'));
            $comment->setLastUpdatedBy($user);
            $filterWords = new BadWords();
            $commentt = $form->get('content')->getData();
            $filterWords->setText($commentt);
            $testCommentt = $filterWords->checkAmong();
            $nn= $filterWords->dictionary;
            $ss=array('amine');

            if($testCommentt ==  true)
            {
                $replace=str_ireplace(implode(" ",$nn),'*******',$commentt);
                $replace2=str_ireplace(implode(" ",$ss),'*******',$replace);
                $comment->setContent($replace2);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_index', ['idForum' => $comment->getIdForum()->getId()]);
        }
        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/editUser", name="comment_editUser", methods={"GET","POST"})
     */
    public function editUser(Request $request, Comment $comment,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id' => 1]);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setLastUpdatedDate(new \DateTime('now'));
            $comment->setLastUpdatedBy($user);
            $filterWords = new BadWords();
            $commentt = $form->get('content')->getData();
            $filterWords->setText($commentt);
            $testCommentt = $filterWords->checkAmong();
            $nn= $filterWords->dictionary;
            $ss=array('amine');

            if($testCommentt ==  true)
            {
                $replace=str_ireplace(implode(" ",$nn),'*******',$commentt);
                $replace2=str_ireplace(implode(" ",$ss),'*******',$replace);
                $comment->setContent($replace2);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_indexUser', ['idForum' => $comment->getIdForum()->getId()]);
        }
        return $this->render('comment/editUser.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/like", name="comment_like", methods={"GET","POST"})
     */
    public function like(Request $request, Comment $comment): Response
    {
        $comment->setLikes($comment->getLikes()+1);
        $comment->setLastUpdatedDate(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('comment_index',['idForum'=>$comment->getIdForum()->getId()]);
    }
    /**
     * @Route("/{id}/dislike", name="comment_dislike", methods={"GET","POST"})
     */
    public function dislike(Request $request, Comment $comment): Response
    {
        $comment->setDislike($comment->getDislike()+1);
        $comment->setLastUpdatedDate(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('comment_index',['idForum'=>$comment->getIdForum()->getId()]);
    }

    /**
     * @Route("/{id}/delete", name="comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index',['idForum'=>$comment->getIdForum()->getId()]);
    }
}
