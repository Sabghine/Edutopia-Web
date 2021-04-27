<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/questions/{id}", name="question_index", methods={"GET"})
     */
    public function index(Exam $exam): Response
    {
        $questions=$this->getDoctrine()->getManager()->getRepository(Exam::class)->findBy(['idExam'=>$exam->getIdExam()]);

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
            'exam' => $exam,

        ]);
    }

    /**
     * @Route("/{id}", name="question_show", methods={"GET"})
     */
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
            'exam' => $question->getIdExam(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $questions=$this->getDoctrine()->getManager()->getRepository(Question::class)->findBy(['idExam'=>$question->getIdExam()->getIdExam()]);


            return $this->render('exam/show.html.twig', [
                'exam' => $question->getIdExam(),
                'questions'=>$questions
            ]);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
            'exam'=> $question->getIdExam()
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods={"POST"})
     */
    public function delete(Request $request, Question $question): Response
    {
        $exam=$question->getIdExam();
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        $questions=$this->getDoctrine()->getManager()->getRepository(Question::class)->findBy(['idExam'=>$exam->getIdExam()]);


        return $this->render('exam/show.html.twig', [
            'exam' => $exam,
            'questions'=>$questions
        ]);
    }

    /**
     * @Route("/question_ByExamnew/{id}", name="question_ByExamnew", methods={"GET","POST"})
     */
    public function question_ByExamnew(Request $request,Exam $exam): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $question->setIdExam($exam);
            $entityManager->persist($question);
            $entityManager->flush();

            $questions=$this->getDoctrine()->getManager()->createQuery("select distinct q from App\Entity\Question q where  q.idExam =".$exam->getIdExam())->getResult();

            return $this->render('exam/show.html.twig', [
                'exam' => $exam,
                'questions'=>$questions
            ]);
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
            'exam'=>$exam
        ]);
    }


}
