<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Subject;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }


    /**
     * @Route ("/search", name="ajax_search_course")
     */
    public function searchAction( Request $request) {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository('App:Course')->findCourseByName($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Course Not found :( ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));

    }
    public function getRealEntities($Course) {
        foreach ($Course as $Course) {
            $realCourse[$Course->getId()] =[$Course->getName()];
        }
        return $realCourse;
    }
    /**
     * @Route("/course_bysubjectStudent/{id}", name="course_bysubjectStudent", methods={"GET"})
     */
    public function course_bysubjectStudent(Subject $subject): Response
    {

        $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy(['idSubject'=>$subject->getId()]);

        return $this->render('course/showcours.html.twig', [
            'courses' => $courses,
            'subject' => $subject,
        ]);
    }

    /**
     * @Route("/course_bysubject/{id}", name="course_bysubject", methods={"GET"})
     */
    public function course_bysubject(Subject $subject): Response
    {

        $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy(['idSubject'=>$subject->getId()]);

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'subject' => $subject,
        ]);
    }

    /**
     * @Route("/course_download/{id}", name="course_download", methods={"GET"})
     */
    public function course_download(Course $course): Response
    {
        $brochure=  $this->getParameter('brochures_directory').'\\'.$course->getCourseFile();

        $response = new BinaryFileResponse('$brochure');
        dump($response);
        die();

        $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy(['idSubject'=>$course->getIdSubject()->getId()]);

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'subject' => $course->getIdSubject(),
        ]);
    }


    /**
     * @Route("/new/{id}", name="course_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger,Subject $subject): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        ;
        if ($form->isSubmitted() && $form->isValid()) {
            $course->setIdSubject($subject);
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('courseFile')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $course->setCourseFile($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy(['idSubject'=>$subject->getId()]);

            return $this->render('course/index.html.twig', [
                'courses' => $courses,
                'subject' => $subject,
            ]);
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
            'subject' => $subject
        ]);
    }

    /**
     * @Route("/{id}", name="course_show", methods={"GET"})
     */
    public function show(Course $course): Response
    {

        return $this->render('course/show.html.twig', [
            'course' => $course,
            'subject' => $course->getIdSubject(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Course $course , SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('courseFile')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $course->setCourseFile($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();

            $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy(['idSubject'=>$course->getIdSubject()->getId()]);

            return $this->render('course/index.html.twig', [
                'courses' => $courses,
                'subject' => $course->getIdSubject(),
            ]);
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
            'subject'=>$course->getIdSubject()
        ]);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"POST"})
     */
    public function delete(Request $request, Course $course): Response
    {
        $subject=$course->getIdSubject();
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy(['idSubject'=>$subject->getId()]);

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'subject' => $subject,
        ]);
    }


}
