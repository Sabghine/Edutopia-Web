<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Entity\LigneExam;
use App\Entity\Subject;
use App\Entity\User;
use App\Form\ExamType;
use App\Repository\ExamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exam")
 */
class ExamController extends AbstractController
{
    private $userId=1;
    /**
     * @Route("/", name="exam_index", methods={"GET"})

    public function index(Subject $subject): Response
    {
        $exams=$this->getDoctrine()->getManager()->getRepository(Exam::class)->findBy(['idSubject'=>$subject->getId()]);

        return $this->render('exam/index.html.twig', [
            'exams' => $exams,
        ]);
    }
*/
    /**
     * @Route("/exam_bysubject/{id}", name="exam_bysubject", methods={"GET"})
     */
    public function exam_bysubject(Subject $subject): Response
    {

        $exams=$this->getDoctrine()->getManager()->getRepository(Exam::class)->findBy(['idSubject'=>$subject->getId()]);

        return $this->render('exam/index.html.twig', [
            'exams' => $exams,
            'subject' => $subject,
        ]);
    }

    /**
     * @Route("/exam_consultertoday/", name="exam_consultertoday", methods={"GET"})
     */
    public function exam_consultertoday(): Response
    {
        $lignes=$this->getDoctrine()->getManager()->getRepository(LigneExam::class)->findBy(["iduser"=>$this->userId]);


        $str="";
        foreach ($lignes as $item) {
            $str .= $item->getIdExam()->getIdexam() . ",";

        }
        $str =rtrim($str,',');


        if($str!='')
        $exams=$this->getDoctrine()->getManager()->createQuery("select distinct e from  App\Entity\Exam e where e.idExam not in (".$str.")  and e.startDate='".date("Y-m-d")."'")->getResult();
        else
        $exams=$this->getDoctrine()->getManager()->createQuery("select distinct e from  App\Entity\Exam e where  e.startDate='".date("Y-m-d")."'")->getResult();

        return $this->render('exam/FrontConsulterExamenToday.html.twig', [
            "exams"=>$exams
        ]);
    }

    /**
     * @Route("/exam_passage/{idExam}", name="exam_passage", methods={"GET"})
     */
    public function exam_passage(Exam $exam): Response
    {
        $questions=$this->getDoctrine()->getManager()->createQuery("select distinct q from App\Entity\Question q where  q.idExam =".$exam->getIdExam())->getResult();
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find(1);

        return $this->render('exam/frontpassagexam.html.twig', [
            'exam' => $exam,
            'questions'=>$questions,
            'u'=>$user
        ]);
    }

    /**
     * @Route("/exam_passage/exam_resultat", name="exam_resultat", methods={"GET","POST"})
     */
    public  function exam_resultat(Request $request){

        $iduser =$request->get('iduser');
        $idexam = $request->get('idexam');
        $note = $request->get('note');

        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($iduser);
        $exam=$this->getDoctrine()->getManager()->getRepository(Exam::class)->find($idexam);

        $ligne=new LigneExam();
        $ligne->setIdexam($exam);
        $ligne->setIduser($user);
        $ligne->setNote($note);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($ligne);
        $entityManager->flush();

        return new JsonResponse("ok");


    }

    /**
     * @Route("/exam_passer", name="exam_passer", methods={"GET","POST"})
     */
    public  function exam_passer(Request $request){

        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($this->userId);
        $lexam=$this->getDoctrine()->getManager()->getRepository(LigneExam::class)->findBy(["iduser"=>$user->getId()]);


        return $this->render('exam/FrontExamPasser.html.twig', [
            'lignes' => $lexam,

        ]);

    }


    /**
     * @Route("/exam_calendar", name="exam_calendar", methods={"GET","POST"})
     */
    public  function exam_calendar(Request $request){

        $exams=$this->getDoctrine()->getManager()->getRepository(Exam::class)->findAll();

        $rdvs = [];

        foreach($exams as $ex){
            $rdvs[] = [
                'id' => $ex->getIdExam(),
                'start' => $ex->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $ex->getFinishDate()->format('Y-m-d H:i:s'),
                'title' => $ex->getType(),
                'description' => " aaa",

            ];
        }

        $data = json_encode($rdvs);

        return $this->render('exam/calendarexam.html.twig',compact('data')

        );

    }



    /**
     * @Route("/{id}/new", name="exam_new", methods={"GET","POST"})
     */
    public function new(Request $request,Subject $subject): Response
    {
        $exam = new Exam();
        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exam->setIdSubject($subject);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exam);
            $entityManager->flush();

            $exams=$this->getDoctrine()->getManager()->getRepository(Exam::class)->findBy(['idSubject'=>$exam->getIdSubject()]);

            return $this->render('exam/index.html.twig', [
                'exams' => $exams,
                'subject' => $subject
            ]);
        }

        return $this->render('exam/new.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
            'subject' => $subject,
        ]);
    }

    /**
     * @Route("/{idExam}", name="exam_show", methods={"GET"})
     */
    public function show(Exam $exam): Response
    {
        $questions=$this->getDoctrine()->getManager()->createQuery("select distinct q from App\Entity\Question q where  q.idExam =".$exam->getIdExam())->getResult();

        return $this->render('exam/show.html.twig', [
            'exam' => $exam,
            'questions'=>$questions
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


            $exams=$this->getDoctrine()->getManager()->getRepository(Exam::class)->findBy(['idSubject'=>$exam->getIdSubject()]);

            return $this->render('exam/index.html.twig', [
                'exams' => $exams,
                'subject' => $exam->getIdSubject()
            ]);
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
        $ids= $exam->getIdSubject();
        if ($this->isCsrfTokenValid('delete'.$exam->getIdExam(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exam);
            $entityManager->flush();
        }


        $exams=$this->getDoctrine()->getManager()->getRepository(Exam::class)->findBy(['idSubject'=>$ids]);
        $subject=$this->getDoctrine()->getManager()->getRepository(Subject::class)->find($ids);

        return $this->render('exam/index.html.twig', [
            'exams' => $exams,
            'subject' => $subject,
        ]);
    }




}
