<?php

namespace App\Controller;

use App\Entity\Specialties;
use App\Form\SpecialtiesType;
use App\Repository\SpecialitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;


/**
 * @Route("/specialties")
 */
class SpecialtiesController extends AbstractController
{
    /**
     * @Route("/", name="specialties_index", methods={"GET"})
     */
    public function index(SpecialitiesRepository $specialitiesRepository): Response
    {
        return $this->render('specialties/index.html.twig', [
            'specialties' => $specialitiesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="specialties_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $specialty = new Specialties();
        $form = $this->createForm(SpecialtiesType::class, $specialty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($specialty);
            $entityManager->flush();

            return $this->redirectToRoute('specialties_index');
        }

        return $this->render('specialties/new.html.twig', [
            'specialty' => $specialty,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="specialties_show", methods={"GET"})
     */
    public function show(Specialties $specialty): Response
    {
        return $this->render('specialties/show.html.twig', [
            'specialty' => $specialty,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="specialties_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Specialties $specialty): Response
    {
        $form = $this->createForm(SpecialtiesType::class, $specialty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('specialties_index');
        }

        return $this->render('specialties/edit.html.twig', [
            'specialty' => $specialty,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="specialties_delete", methods={"POST"})
     */
    public function delete(Request $request, Specialties $specialty, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specialty->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($specialty);
            $entityManager->flush();
        }
        $flashy->success('specialité supprimé!');

        return $this->redirectToRoute('specialties_index');
    }
}
