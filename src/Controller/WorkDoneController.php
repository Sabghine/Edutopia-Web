<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\WorkDone;
use App\Form\WorkDoneType;
use App\Repository\WorkDoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\WorkDoneScoreType;

/**
 * @Route("/workDone")
 */
class WorkDoneController extends AbstractController
{
    /**
     * @Route("/{idActivity}", name="work_done_index", methods={"GET","POST"})
     */
    public function index(WorkDoneRepository $workDoneRepository,WorkDone $workDone): Response
    {
        $nbr=$workDoneRepository->countStatus("Available",$workDone->getIdActivity()->getId());
        $nbrN=$workDoneRepository->countStatus("ScoreAdded",$workDone->getIdActivity()->getId());
        return $this->render('work_done/index.html.twig', [
            'work_dones' => $workDoneRepository->findBy(["idActivity" => $workDone->getIdActivity()]),
            'nbr'=>$nbr,
            'nbrN'=>$nbrN,
        ]);
    }

    /**
     * @Route("/new/{idActivity}", name="work_done_new", methods={"GET","POST"})
     */
    public function new(Request $request,WorkDone $workDone, SluggerInterface $slugger): Response
    {
        $workDone2 = new WorkDone();
        $form = $this->createForm(WorkDoneType::class, $workDone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('workFile')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

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
                $workDone2->setWorkFile($newFilename);
            }
            $idActivity=$workDone->getIdActivity()->getId();
            $workDone2->setStatus("Available");
            $workDone2->setUploadedDate(new \DateTime('now'));
            $workDone->setLastUpdatedDate(new \DateTime('now'));
            $workDone2->setIdActivity($idActivity);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($workDone2);
            $entityManager->flush();

            return $this->redirectToRoute('work_done_index' ,['idActivity'=>$$idActivity]);
        }

        return $this->render('work_done/new.html.twig', [
            'work_done' => $workDone,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/details", name="work_done_show", methods={"GET","POST"})
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
    public function edit(Request $request, WorkDone $workDone,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(WorkDoneType::class, $workDone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('workFile')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

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
                $workDone->setWorkFile($newFilename);
            }

            $idActivity=$workDone->getIdActivity()->getId();
            $workDone->setLastUpdatedDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($workDone);
            $entityManager->flush();

            return $this->redirectToRoute('work_done_index' ,['idActivity'=>$idActivity]);
        }

        return $this->render('work_done/edit.html.twig', [
            'work_done' => $workDone,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/score", name="work_done_score", methods={"POST"})
     */
    public function score(Request $request, WorkDone $workDone): Response
    {
        $form = $this->createForm(WorkDoneScoreType::class, $workDone);
        $form->handleRequest($request);
        $deadline=$workDone->getIdActivity()->getDeadline();
        $uploadedDate=$workDone->setLastUpdatedDate();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($uploadedDate<$deadline)
            {
                $score=$workDone->getScore()*80;
                $depot=20*20;
                $notescore=$score/100;
                $notedepot=$depot/100;
                $moyenneScore=$notescore+$notedepot;
                $workDone->setScore($moyenneScore);
                $idActivity = $workDone->getIdActivity()->getId();
                $workDone->setStatus("ScoreAdded");
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($workDone);
                $entityManager->flush();
            }
            else {
                $score=$workDone->getScore()*80;
                $depot=0*20;
                $notescore=$score/100;
                $notedepot=$depot/100;
                $moyenneScore=$notescore+$notedepot;
                $workDone->setScore($moyenneScore);
                $idActivity = $workDone->getIdActivity()->getId();
                $workDone->setStatus("ScoreAdded");
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($workDone);
                $entityManager->flush();
            }

            return $this->redirectToRoute('work_done_index', ['idActivity' => $idActivity]);
        }
        return $this->render('work_done/score.html.twig', [
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
        $idActivity=$workDone->getIdActivity()->getId();
        return $this->redirectToRoute('work_done_index' ,['idActivity'=>$idActivity]);
    }
}
