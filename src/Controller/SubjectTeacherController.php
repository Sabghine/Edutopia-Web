<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\Subject1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;




/**
 * @Route("/subjectTeacher")
 */
class SubjectTeacherController extends AbstractController
{
    /**
     * @Route("/", name="subject_teacher_index", methods={"GET"})
     */
    public function index(): Response
    {
        $subjects = $this->getDoctrine()
            ->getRepository(Subject::class)
            ->findAll();

        return $this->render('subject_teacher/index.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    /**
     * @Route("/new", name="subject_teacher_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $subject = new Subject();
        $form = $this->createForm(Subject1Type::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('subject_teacher_index');
        }

        return $this->render('subject_teacher/new.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route ("/search", name="ajax_search")
     */
    public function searchAction( Request $request) {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository('App:Subject')->findSubjectsByName($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Subject Not found :( ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));

    }
    public function getRealEntities($subjects) {
        foreach ($subjects as $subject) {
            $realSubjects[$subject->getId()] =[$subject->getIdSubject()];
        }
        return $realSubjects;
    }


    /**
     * @Route("/{id}", name="subject_teacher_show", methods={"GET"})
     */
    public function show(Subject $subject): Response
    {
        return $this->render('subject_teacher/show.html.twig', [
            'subject' => $subject,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="subject_teacher_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Subject $subject): Response
    {
        $form = $this->createForm(Subject1Type::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('subject_teacher_index');
        }

        return $this->render('subject_teacher/edit.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="subject_teacher_delete", methods={"POST"})
     */
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('subject_teacher_index');
    }
}
