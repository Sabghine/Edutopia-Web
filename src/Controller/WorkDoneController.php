<?php

namespace App\Controller;

use App\Entity\WorkDone;
use App\Form\WorkDoneType;
use App\Repository\WorkDoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/work/done")
 */
class WorkDoneController extends AbstractController
{
    /**
     * @Route("/", name="work_done_index", methods={"GET"})
     */
    public function index(WorkDoneRepository $workDoneRepository): Response
    {
        return $this->render('work_done/login.html.twig', [
            'work_dones' => $workDoneRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="work_done_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $workDone = new WorkDone();
        $form = $this->createForm(WorkDoneType::class, $workDone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($workDone);
            $entityManager->flush();

            return $this->redirectToRoute('work_done_index');
        }

        return $this->render('work_done/new.html.twig', [
            'work_done' => $workDone,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="work_done_show", methods={"GET"})
     */
    public function show(WorkDone $workDone): Response
    {
        return $this->render('work_done/show.html.twig', [
            'work_done' => $workDone,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="work_done_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WorkDone $workDone): Response
    {
        $form = $this->createForm(WorkDoneType::class, $workDone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('work_done_index');
        }

        return $this->render('work_done/edit.html.twig', [
            'work_done' => $workDone,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="work_done_delete", methods={"POST"})
     */
    public function delete(Request $request, WorkDone $workDone): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workDone->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($workDone);
            $entityManager->flush();
        }

        return $this->redirectToRoute('work_done_index');
    }
}
