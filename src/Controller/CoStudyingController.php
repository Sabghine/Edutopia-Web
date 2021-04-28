<?php

namespace App\Controller;

use App\Entity\CoStudying;
use App\Entity\Costudyingtype as Costudyingtypes;
use App\Form\CoStudyingType;
use App\Repository\CoStudyingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/co/studying")
 */
class CoStudyingController extends AbstractController
{
    /**
     * @Route("/", name="co_studying_index", methods={"GET"})
     */
    public function index(CoStudyingRepository $coStudyingRepository): Response
    {
        return $this->render('co_studying/index.html.twig', [
            'co_studyings' => $coStudyingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/csfornt", name="co_studying_font", methods={"GET"})
     */
    public function index_front(): Response
    {
        $costudyings = $this->getDoctrine()
            ->getRepository(CoStudying::class)
            ->findAll();

        $costudyingtypes = $this->getDoctrine()
            ->getRepository(Costudyingtypes::class)
            ->findAll();

        return $this->render('co_studying/index_front.html.twig', [
            'co_studyings' => $costudyings, 'co_studyingtypes' => $costudyingtypes
        ]);
    }

    /**
     * @Route("/new", name="co_studying_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $coStudying = new CoStudying();
        $form = $this->createForm(CoStudyingType::class, $coStudying);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('file')->getData();

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
                $coStudying->setFile($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($coStudying);
            $entityManager->flush();

            $this->addFlash('success', 'Contenu Ajouté avec succès!');

            return $this->redirectToRoute('co_studying_font');
        }

        return $this->render('co_studying/new.html.twig', [
            'co_studying' => $coStudying,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="co_studying_show", methods={"GET"})
     */
    public function show(CoStudying $coStudying): Response
    {
        return $this->render('co_studying/show.html.twig', [
            'co_studying' => $coStudying,
        ]);
    }

    /**
     * @Route("/front/{id}", name="co_studying_front", methods={"GET"})
     */
    public function show_front(CoStudying $coStudying): Response
    {
        return $this->render('co_studying/show_front.html.twig', [
            'co_studying' => $coStudying,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="co_studying_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CoStudying $coStudying, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CoStudyingType::class, $coStudying);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('file')->getData();
            // this condition is needed because the 'file' field is not required
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
                $coStudying->setFile($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Contenu modifié avec succès!');

            return $this->redirectToRoute('co_studying_index');
        }

        return $this->render('co_studying/edit.html.twig', [
            'co_studying' => $coStudying,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="co_studying_delete", methods={"POST"})
     */
    public function delete(Request $request, CoStudying $coStudying): Response
    {
        if ($this->isCsrfTokenValid('delete' . $coStudying->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($coStudying);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Contenu supprimé avec succès!');
        return $this->redirectToRoute('co_studying_index');
    }

    /**
     * @Route("/tri", name="/tri")
     */
    public function Tri(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT q FROM App\Entity\CoStudying q 
            ORDER BY q.rating DESC'
        );
        var_dump($query);
        die();
        $costudyings = $query->getResult();
        $this->addFlash('success', 'Tri Affectué!');


        return $this->render('co_studying/index.html.twig',
            array('co_studyings' => $costudying));

    }

    /**
     * @param CoStudyingRepository $repository
     * @return Response
     * @Route ("list" , name="triCategorieB")
     */

    function OrderByName(CoStudyingRepository $repository)
    {

        $costudyings = $repository->OrderByName();
        $this->addFlash('success', 'Contenu trié avec succès!');
        return $this->render('co_studying/index.html.twig', [
            'co_studyings' => $costudyings,
        ]);


    }

    /**
     * @Route("/TriCat/show", name="costudying_cat", methods={"POST"})
     */
    public function FindByCategorie(EntityManagerInterface $em, Request $request): Response
    {

        $data = $request->get('myText');
        if ($data == "Opportunity") {
            $abc = 1;
        } elseif ($data == "Summary") {
            $abc = 2;
        } elseif ($data == "Freelance") {
            $abc = 3;
        } else {
            $abc = 4;
        }

        $queryBuilder = $em->getRepository(CoStudying::class)->createQueryBuilder('E');
        $queryBuilder->andWhere('E.type = :name');
        $queryBuilder->setParameter('name', $abc);
        $costudyings = $queryBuilder->getQuery()->getResult();

        $costudyingtypes = $this->getDoctrine()
            ->getRepository(Costudyingtypes::class)
            ->findAll();

        return $this->render('co_studying/index_front.html.twig', [
            'co_studyings' => $costudyings, 'co_studyingtypes' => $costudyingtypes,
        ]);
    }

    public function UpdateRating(CoStudying $costudying, int $sum, int $total)
    {
        $NewRating = $sum / $total;
        $NewRating = $sum / $total;
        $costudying->setRating($NewRating);
        $this->getDoctrine()->getManager()->flush();
    }


}
