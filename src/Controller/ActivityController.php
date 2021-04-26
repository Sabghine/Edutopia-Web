<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\CoStudying;
use App\Form\ActivityType;
use App\Form\CoStudyingType;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/activity")
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/", name="activity_index", methods={"GET","POST"})
     */
    public function index(ActivityRepository $activityRepository,Request $request,Request $request2): Response
    {
        $nbr=$activityRepository->countAvailable("Available");
        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('trier', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        $formR= $this->createFormBuilder($defaultData)
            ->add('refresh', SubmitType::class)
            ->getForm();
        if ($form->isSubmitted()) {
            return $this->render('activity/index.html.twig', [
                'activities' => $activityRepository->tri("Available")
                , 'nbr' => $nbr,
                'form' => $form->createView(),
                'formR' => $formR->createView(),
            ]);
        }

        $formR->handleRequest($request2);
        if ($formR->isSubmitted()) {
            $act=$activityRepository->findBy(["status" => "Available"]);
            dd($act);
            return $this->render('activity/index.html.twig', [
                'activities' => $activityRepository->findBy(["status" => "Available"])
                , 'nbr' => $nbr,
                'form' => $form->createView(),
                'formR' => $formR->createView(),
            ]);
        }
        return $this->render('activity/index.html.twig', [
            'activities' => $activityRepository->findBy(["status" => "Available"])
            , 'nbr' => $nbr,
            'form' => $form->createView(),
            'formR' => $formR->createView(),
        ]);

    }
    /**
     * @Route("/indexUser", name="activity_indexUser", methods={"GET"})
     */
    public function indexUser(ActivityRepository $activityRepository): Response
    {
        $nbr=$activityRepository->countAvailable("Available");
        return $this->render('activity/indexUser.html.twig', [
            'activities' => $activityRepository->findBy(["status" => "Available"]),
            'nbr' => $nbr,
        ]);
    }
    /**
     * @Route("/ArchivedList", name="activity_ArchivedList", methods={"GET","POST"})
     */
    public function ArchivedList(ActivityRepository $activityRepository): Response
    {
        $nbr=$activityRepository->countAvailable("Archived");
        return $this->render('activity/archivedList.html.twig', [
            'activities' => $activityRepository->findBy(["status" => "Archived"]),
            'nbr' => $nbr,
        ]);
    }

    /**
     * @Route("/new", name="activity_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('workTodo')->getData();

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
                $activity->setWorkTodo($newFilename);
                $activity->setStatus("Available");
                $activity->setCeatedDate(new \DateTime('now'));
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('activity_index');
        }

        return $this->render('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/Details", name="activity_show", methods={"GET","POST"})
     */
    public function show(Activity $activity): Response
    {
        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="activity_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activity $activity, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('workTodo')->getData();

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
                $activity->setWorkTodo($newFilename);

            }
            $activity->setLastUpdatedDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('activity_index');
        }

        return $this->render('activity/edit.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/archive", name="activity_archive", methods={"POST"})
     */
    public function archive(Request $request, Activity $activity): Response
    {
        $activity->setStatus("Archived");
        $activity->setArchivedDate(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($activity);
        $entityManager->flush();

        return $this->redirectToRoute('activity_ArchivedList');
    }

    /**
     * @Route("/{id}", name="activity_active", methods={"POST"})
     */
    public function active(Request $request, Activity $activity): Response
    {
        $activity->setStatus("Available");
        $activity->setLastUpdatedDate(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($activity);
        $entityManager->flush();

        return $this->redirectToRoute('activity_index');
    }

    /**
     * @Route("/{id}", name="activity_delete", methods={"POST"})
     */
    public function delete(Request $request, Activity $activity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activity_index');
    }
}
