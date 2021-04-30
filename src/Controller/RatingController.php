<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\CoStudying;
use App\Entity\Costudyingtype as Costudyingtypes;
use App\Entity\User;
use App\Form\RatingType;
use App\Repository\RatingRepository;
use App\Repository\CoStudyingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rating")
 */
class RatingController extends AbstractController
{
    /**
     * @Route("/", name="rating_index", methods={"GET"})
     */
    public function index(RatingRepository $ratingRepository): Response
    {
        return $this->render('rating/login.html.twig', [
            'ratings' => $ratingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="rating_new", methods={"GET","POST"})
     */
    public function new(Request $request, CoStudyingController $coStudyingController, RatingRepository $ratingRepository, CoStudyingRepository $coStudyingRepository, UserRepository $userRepository): Response
    {
        $data = $request->get('ratedEvent');
        $rating = $request->get('note');
        $cos = new CoStudying();
        $cos = $coStudyingRepository->findOneBy(['id' => $data]);
        $user = new User();
        $user = $userRepository->findOneBy(['id' => 1]);
        $ratingCos = new Rating();
        $ratingCos->setIdItem($cos);
        $ratingCos->setIdRater($user);
        $ratingCos->setRate($rating);
        $Exists = $ratingRepository->findOneBy(['idRater' => $user->getId(), 'idItem' => $cos->getId()]);
        $entityManager = $this->getDoctrine()->getManager();
        if ($Exists) {
            $entityManager->remove($Exists);
            $entityManager->persist($ratingCos);
            $entityManager->flush();
        } else {
            $entityManager->persist($ratingCos);
            $entityManager->flush();
        }

        $queryBuilder = $entityManager->getRepository(Rating::class)->createQueryBuilder('R');
        $queryBuilder->select('count(R.id)');
        $queryBuilder->andWhere('R.idItem = :cat');
        $queryBuilder->setParameter('cat', $cos->getId());
        try {
            $ratings = $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
        }

        $number = intval($ratings);


        $queryBuilder = $entityManager->getRepository(Rating::class)->createQueryBuilder('R');
        $queryBuilder->select('SUM(R.rate) as total');
        $queryBuilder->andWhere('R.idItem = :cat');
        $queryBuilder->setParameter('cat', $cos->getId());

        try {
            $total = $queryBuilder->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }

        $x = array_values($total)[0];
        $sum = intval($x);
        $coStudyingController->UpdateRating($cos, $sum, $number);

        $costudyingtypes = $this->getDoctrine()
            ->getRepository(Costudyingtypes::class)
            ->findAll();

        $costudyings = $this->getDoctrine()
            ->getRepository(CoStudying::class)
            ->findAll();

        return $this->render('co_studying/index_front.html.twig', [
            'co_studying' => $cos, 'co_studyingtypes' => $costudyingtypes,  'co_studyings' => $costudyings
        ]);
    }


    /**
     * @Route("/{id}", name="rating_show", methods={"GET"})
     */
    public function show(Rating $rating): Response
    {
        return $this->render('rating/show.html.twig', [
            'rating' => $rating,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rating_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rating $rating): Response
    {
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rating_index');
        }

        return $this->render('rating/edit.html.twig', [
            'rating' => $rating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rating_delete", methods={"POST"})
     */
    public function delete(Request $request, Rating $rating): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rating->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rating);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rating_index');
    }
}
