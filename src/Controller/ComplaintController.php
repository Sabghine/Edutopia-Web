<?php

namespace App\Controller;

use App\Entity\Complaint;
use App\Form\ComplaintType;
use App\Repository\ComplaintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/complaint")
 */
class ComplaintController extends AbstractController
{
    /**
     * @Route ("/listeC", name="listeComplaints" ,  methods={"GET"} )
     */

    public function GetComplaints(ComplaintRepository $repository, SerializerInterface $serializerInterface)
    {
        $complaints = $this->getDoctrine()->getManager()->getRepository(Complaint::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($complaints , 'json', [AbstractNormalizer::ATTRIBUTES => ['object','description','status']]);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/add" ,name="add_complaint")
     */

    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {

        $complaint = new Complaint();
        $object = $request->query->get("object");
        $description = $request->query->get("description");
        $status = $request->query->get("status");

        $complaint->setDescription($description);
        $complaint->setObject($object);
        $complaint->setStatus($status);


        $em->persist($complaint);
        $em->flush();
        $json = $serializer->serialize($complaint, 'json', ['groups' => 'complaints']);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($json);
        return new JsonResponse($formatted);


    }


    /**
     * @Route("/stats", name="stats")
     */
    public function stat()
    {
        return $this->render('complaint/stats.html.twig', [
        ]);
    }

    /**
     * @Route("/", name="complaint_index", methods={"GET"})
     */
    public function index(ComplaintRepository $complaintRepository): Response
    {
        return $this->render('complaint/index.html.twig', [
            'complaints' => $complaintRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="complaint_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $currentUser = $this->getUser();

        $complaint = new Complaint();
        $form = $this->createForm(ComplaintType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $complaint->setCreatedBy($currentUser);
            $complaint->setCreatedDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($complaint);
            $entityManager->flush();

            return $this->redirectToRoute('complaint_index');
        }
        $this->addFlash('success', 'Ajouté avec succées');
        return $this->render('complaint/new.html.twig', [
            'complaint' => $complaint,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="complaint_show", methods={"GET"})
     */
    public function show(Complaint $complaint): Response
    {
        return $this->render('complaint/show.html.twig', [
            'complaint' => $complaint,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="complaint_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Complaint $complaint): Response
    {
        $form = $this->createForm(ComplaintType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('complaint_index');
        }

        return $this->render('complaint/edit.html.twig', [
            'complaint' => $complaint,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="complaint_delete", methods={"POST"})
     */
    public function delete(Request $request, Complaint $complaint): Response
    {
        if ($this->isCsrfTokenValid('delete' . $complaint->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($complaint);
            $entityManager->flush();
        }

        return $this->redirectToRoute('complaint_index');
    }


}
