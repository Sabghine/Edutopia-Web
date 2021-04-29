<?php

namespace App\Controller;

use App\Entity\Costudyingtype;
use App\Form\CostudyingtypeType;
use App\Repository\CostudyingtypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/costudyingtype")
 */
class CostudyingtypeController extends AbstractController
{
    /**
     * @Route("/", name="costudyingtype_index", methods={"GET"})
     */
    public function index(CostudyingtypeRepository $costudyingtypeRepository): Response
    {
        return $this->render('costudyingtype/login.html.twig', [
            'costudyingtypes' => $costudyingtypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="costudyingtype_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $costudyingtype = new Costudyingtype();
        $form = $this->createForm(CostudyingtypeType::class, $costudyingtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($costudyingtype);
            $entityManager->flush();

            return $this->redirectToRoute('costudyingtype_index');
        }

        return $this->render('costudyingtype/new.html.twig', [
            'costudyingtype' => $costudyingtype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="costudyingtype_show", methods={"GET"})
     */
    public function show(Costudyingtype $costudyingtype): Response
    {
        return $this->render('costudyingtype/show.html.twig', [
            'costudyingtype' => $costudyingtype,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="costudyingtype_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Costudyingtype $costudyingtype): Response
    {
        $form = $this->createForm(CostudyingtypeType::class, $costudyingtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('costudyingtype_index');
        }

        return $this->render('costudyingtype/edit.html.twig', [
            'costudyingtype' => $costudyingtype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="costudyingtype_delete", methods={"POST"})
     */
    public function delete(Request $request, Costudyingtype $costudyingtype): Response
    {
        if ($this->isCsrfTokenValid('delete'.$costudyingtype->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($costudyingtype);
            $entityManager->flush();
        }

        return $this->redirectToRoute('costudyingtype_index');
    }
}
