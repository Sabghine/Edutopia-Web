<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/forum")
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/", name="forum_index", methods={"GET","POST"})
     */
    public function index(ForumRepository $forumRepository): Response
    {
        $nbr=$forumRepository->countStatus("Available");
        return $this->render('forum/index.html.twig', [
            'forums' => $forumRepository->findBy(["status" => "Available"]),
            'nbr' => $nbr,
        ]);
    }
    /**
     * @Route("/ArchivedList", name="forum_ArchivedList", methods={"GET","POST"})
     */
    public function ArchivedList(ForumRepository $forumRepository): Response
    {
        $nbr=$forumRepository->countStatus("Archived");
        return $this->render('forum/archivedList.html.twig', [
            'forums' => $forumRepository->findBy(["status" => "Archived"]),
            'nbr' => $nbr,
        ]);
    }

    /**
     * @Route("/new", name="forum_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forum->setStatus("Available");
            $forum->getCreatedDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('forum_index');
        }

        return $this->render('forum/new.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/details", name="forum_show", methods={"GET"})
     */
    public function show(Forum $forum): Response
    {
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="forum_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Forum $forum): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forum_index');
        }

        return $this->render('forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/archive", name="forum_archive", methods={"POST"})
     */
    public function archive(Request $request, Forum $forum): Response
    {
        $forum->setStatus("Archived");
        $forum->setArchivedDate(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forum);
        $entityManager->flush();

        return $this->redirectToRoute('forum_ArchivedList');
    }

    /**
     * @Route("/{id}", name="forum_active", methods={"POST"})
     */
    public function active(Request $request, Forum $forum): Response
    {
        $forum->setStatus("Available");
        $forum->setLastUpdatedDate(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forum);
        $entityManager->flush();

        return $this->redirectToRoute('forum_index');
    }

    /**
     * @Route("/{id}/delete", name="forum_delete", methods={"POST"})
     */
    public function delete(Request $request, Forum $forum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_index');
    }
}
