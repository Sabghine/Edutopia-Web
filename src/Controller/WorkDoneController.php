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

/**
 * @Route("/workDone")
 */
class WorkDoneController extends AbstractController
{
    /**
     * @Route("/", name="work_done_index", methods={"GET"})
     */
    public function index(WorkDoneRepository $workDoneRepository): Response
    {
        return $this->render('work_done/index.html.twig', [
            'work_dones' => $workDoneRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{idActivity}", name="work_done_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $workDone = new WorkDone();
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($workDone);
            $entityManager->flush();

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
