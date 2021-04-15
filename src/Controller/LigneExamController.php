<?php

namespace App\Controller;

use App\Entity\LigneExam;
use App\Form\LigneExamType;
use App\Repository\LigneExamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne/exam")
 */
class LigneExamController extends AbstractController
{
    /**
     * @Route("/", name="ligne_exam_index", methods={"GET"})
     */
    public function index(LigneExamRepository $ligneExamRepository): Response
    {
        return $this->render('ligne_exam/index.html.twig', [
            'ligne_exams' => $ligneExamRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_exam_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ligneExam = new LigneExam();
        $form = $this->createForm(LigneExamType::class, $ligneExam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligneExam);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_exam_index');
        }

        return $this->render('ligne_exam/new.html.twig', [
            'ligne_exam' => $ligneExam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idligne}", name="ligne_exam_show", methods={"GET"})
     */
    public function show(LigneExam $ligneExam): Response
    {
        return $this->render('ligne_exam/show.html.twig', [
            'ligne_exam' => $ligneExam,
        ]);
    }

    /**
     * @Route("/{idligne}/edit", name="ligne_exam_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LigneExam $ligneExam): Response
    {
        $form = $this->createForm(LigneExamType::class, $ligneExam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_exam_index');
        }

        return $this->render('ligne_exam/edit.html.twig', [
            'ligne_exam' => $ligneExam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idligne}", name="ligne_exam_delete", methods={"POST"})
     */
    public function delete(Request $request, LigneExam $ligneExam): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneExam->getIdligne(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneExam);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_exam_index');
    }
}
