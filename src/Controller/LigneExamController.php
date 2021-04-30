<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Entity\LigneExam;
use App\Form\LigneExamType;
use App\Repository\ExamRepository;
use App\Repository\LigneExamRepository;
use App\Repository\UserRepository;
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
        return $this->render('ligne_exam/login.html.twig', [
            'ligne_exams' => $ligneExamRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_exam_new", methods={"GET","POST"})
     */
    public function new(Request $request ,LigneExamRepository $ligneExamRepository, UserRepository $userRepository, ExamRepository $examRepository): Response
    {

        $ide = $request->get('ratedEvent');
        $idu = $request->get('iduser');
        $note_t = $request->get('note');
        $note = $number = intval($note_t);

        $ligneExam = new LigneExam();
        $user = $userRepository->findOneBy(['id' => $idu]);
        $exm = $examRepository->findOneBy(['idExam' => $ide]);
        $ligneExam->setIduser($user);
        $ligneExam->setIdexam($exm);
        $ligneExam->setNote($note);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($ligneExam);
        $entityManager->flush();

        $lignes = $this->getDoctrine()->getManager()->getRepository(LigneExam::class)->findBy(["iduser" => $idu]);
        $str = "";
        foreach ($lignes as $item) {
            $str .= $item->getIdExam()->getIdexam() . ",";
        }
        $str = rtrim($str, ',');
        if ($str != '')
            $exams = $this->getDoctrine()->getManager()->createQuery("select distinct e from  App\Entity\Exam e where e.idExam not in (" . $str . ")  and e.startDate='" . date("Y-m-d") . "'")->getResult();
        else
            $exams = $this->getDoctrine()->getManager()->createQuery("select distinct e from  App\Entity\Exam e where  e.startDate='" . date("Y-m-d") . "'")->getResult();

        return $this->render('exam/FrontConsulterExamenToday.html.twig', [
            'ligne_exam' => $ligneExam, 'exams' => $exams,
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
