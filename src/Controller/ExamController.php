<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Form\ExamType;
use App\Repository\ExamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exam")
 */
class ExamController extends AbstractController
{
    /**
     * @Route("/", name="exam_index", methods={"GET"})
     */
    public function index(ExamRepository $examRepository): Response
    {
        return $this->render('exam/login.html.twig', [
            'exams' => $examRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="exam_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $exam = new Exam();
        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exam);
            $entityManager->flush();

            return $this->redirectToRoute('exam_index');
        }

        return $this->render('exam/new.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idExam}", name="exam_show", methods={"GET"})
     */
    public function show(Exam $exam): Response
    {
        return $this->render('exam/show.html.twig', [
            'exam' => $exam,
        ]);
    }

    /**
     * @Route("/{idExam}/edit", name="exam_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Exam $exam): Response
    {
        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('exam_index');
        }

        return $this->render('exam/edit.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idExam}", name="exam_delete", methods={"POST"})
     */
    public function delete(Request $request, Exam $exam): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exam->getIdExam(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exam);
            $entityManager->flush();
        }

        return $this->redirectToRoute('exam_index');
    }
}
