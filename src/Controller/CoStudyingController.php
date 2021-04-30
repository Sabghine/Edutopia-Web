<?php

namespace App\Controller;

use App\Entity\CoStudying;
use App\Entity\Costudyingtype as Costudyingtypes;
use App\Entity\User;
use App\Form\CoStudyingType;
use App\Repository\CoStudyingRepository;
use App\Repository\UserRepository;
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
    public function index(CoStudyingRepository $coStudyingRepository, UserRepository $userRepository): Response
    {
        return $this->render('co_studying/login.html.twig', [
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
    public function new(Request $request, SluggerInterface $slugger, UserRepository $userRepository): Response
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
            $coStudying->setCreatedDate(new \DateTime("now"));
            $user = new User();
            $user = $userRepository->findOneBy(['id' => 1]);
            $coStudying->setIdStudent($user);
            $coStudying->setRating(0);

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
    public function edit(Request $request, CoStudying $coStudying, SluggerInterface $slugger, UserRepository $userRepository): Response
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
            $coStudying->setLastUpdatedDate(new \DateTime("now"));
            $user = new User();
            $user = $userRepository->findOneBy(['id' => 1]);
            $coStudying->setLastUpdatedBy($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Contenu modifié avec succès!');

            return $this->redirectToRoute('co_studying_front',['id' => $coStudying->getId()]);
        }
        return $this->render('co_studying/edit_front.html.twig', [
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
     * @Route("/{id}/delete", name="co_studying_delete_front", methods={"POST"})
     */
    public function delete_front(Request $request, CoStudying $coStudying): Response
    {
        if ($this->isCsrfTokenValid('delete' . $coStudying->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($coStudying);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Contenu supprimé avec succès!');
        return $this->redirectToRoute('co_studying_font');
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
     * @param CoStudyingRepository $repository
     * @return Response
     * @Route ("list/front" , name="triCategorieB_front")
     */
    function OrderByRating(CoStudyingRepository $repository, Request $request): Response
    {
        $data = $request->get('myTexts');
        if ($data == "Catégorie") {
            $costudyings = $repository->OrderByRating();
            $this->addFlash('success', 'Contenu trié selon catégorie avec succès!');
        } elseif ($data == "Rating") {
            $costudyings = $repository->OrderByName();
            $this->addFlash('success', 'Contenu trié selon l"évaluation (rating) avec succès!');
        } else {
            $costudyings = $this->getDoctrine()
                ->getRepository(CoStudying::class)
                ->findAll();
        }

        $costudyingtypes = $this->getDoctrine()
            ->getRepository(Costudyingtypes::class)
            ->findAll();

        return $this->render('co_studying/index_front.html.twig', [
            'co_studyings' => $costudyings, 'co_studyingtypes' => $costudyingtypes
        ]);
    }


    /**
     * @Route("/TriCat/show", name="costudying_cat", methods={"POST", "GET"})
     */
    public function FindByCategorie(EntityManagerInterface $em, Request $request): Response
    {
        $data = $request->get('myText');
        if ($data == "Opportunité") {
            $abc = 1;
            $queryBuilder = $em->getRepository(CoStudying::class)->createQueryBuilder('E');
            $queryBuilder->andWhere('E.type = :name');
            $queryBuilder->setParameter('name', $abc);
            $costudyings = $queryBuilder->getQuery()->getResult();
            $this->addFlash('success', 'Contenu filtré (Opportunité) avec succès!');
        } elseif ($data == "Résumé") {
            $abc = 2;
            $queryBuilder = $em->getRepository(CoStudying::class)->createQueryBuilder('E');
            $queryBuilder->andWhere('E.type = :name');
            $queryBuilder->setParameter('name', $abc);
            $costudyings = $queryBuilder->getQuery()->getResult();
            $this->addFlash('success', 'Contenu filtré (Résumé) avec succès!');
        } elseif ($data == "Freelance") {
            $abc = 3;
            $queryBuilder = $em->getRepository(CoStudying::class)->createQueryBuilder('E');
            $queryBuilder->andWhere('E.type = :name');
            $queryBuilder->setParameter('name', $abc);
            $costudyings = $queryBuilder->getQuery()->getResult();
            $this->addFlash('success', 'Contenu filtré (Freelance) avec succès!');
        } else if ($data == "Offre Stage") {
            $abc = 4;
            $queryBuilder = $em->getRepository(CoStudying::class)->createQueryBuilder('E');
            $queryBuilder->andWhere('E.type = :name');
            $queryBuilder->setParameter('name', $abc);
            $costudyings = $queryBuilder->getQuery()->getResult();
            $this->addFlash('success', 'Contenu filtré (Offre Stage) avec succès!');
        } else {
            $costudyings = $this->getDoctrine()
                ->getRepository(CoStudying::class)
                ->findAll();
        }

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
