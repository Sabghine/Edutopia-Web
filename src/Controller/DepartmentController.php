<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartementRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Deprecated;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Sonata\Exporter\Handler;
use Sonata\Exporter\Source\PDOStatementSourceIterator;
use Sonata\Exporter\Writer\CsvWriter;



/**
 * @Route("/department")
 */
class DepartmentController extends AbstractController
{
    /**
     * @Route("/", name="department_index", methods={"GET"})
     */
    public function index(DepartementRepository $departementRepository): Response
    {
        return $this->render('department/index.html.twig', [
            'departments' => $departementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="department_new", methods={"GET","POST"})
     * @param Request $request
     * @param FlashyNotifier $flashy
     * @return Response
     */
    public function new(Request $request): Response
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/new.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/exportAction", name="exportAction")
     */
    public function exportAction(FlashyNotifier $flashy) {

        $dbh = new \PDO('mysql://root:@127.0.0.1:3306/edutopia','root','');
        $stm = $dbh->prepare('SELECT * FROM `department`');
        $stm->execute();
        $result = $stm->fetchAll();  // Even fetch() will do
        if (count($result)>0) {
            $source = new PDOStatementSourceIterator($stm);

// Prepare the writer
            $writer = new CsvWriter('data.csv');
            $em=$this->getDoctrine()->getManager();
            $department = $em->getRepository('App:Department')->findAll();
            $writer->open();
            $writer->write($department);

// Export the data
            Handler::create($source, $writer)->export();
            $flashy->success('fichier crée!');
        } else {
            var_dump("no"); exit();

        }

        return $this->redirectToRoute('department_index');
    }
    /**
     * @Route("/stat", name="stat", methods={"GET","POST"})
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return Response
     */
    public function statistique(Request $request, NormalizerInterface $Normalizer): Response
    {
        $namedep = $request->get('q');
        $em=$this->getDoctrine()->getManager();
        $department = $em->getRepository('App:Department')->findOneByName($namedep);
        if( is_null($department)) {
            $id=0;
        } else {
            $id=$department->getId();
        }
        $users = $em->getRepository('App:User')->findByDepid($id);
        $jsonContent = $Normalizer->normalize($users, 'json',['groups'=>'students']);

        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/stat", name="stat", methods={"GET","POST"})
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return Response
     */
    public function statistique(Request $request, NormalizerInterface $Normalizer): Response
    {
        $namedep = $request->get('q');
        $em=$this->getDoctrine()->getManager();
        $department = $em->getRepository('App:Department')->findOneByName($namedep);
        if( is_null($department)) {
            $id=0;
        } else {
            $id=$department->getId();
        }
        $users = $em->getRepository('App:User')->findByDepid($id);
        $jsonContent = $Normalizer->normalize($users, 'json',['groups'=>'students']);

        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/{id}", name="department_show", methods={"GET"})
     */
    public function show(Department $department): Response
    {
        $twenties=0;
        $thirties=0;
        $fourties=0;
        $plus=0;
        $em=$this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->findByDepid($department->getId());
        foreach ($users as $user) {

            $year=$user->getBirthDate()->format("Y");
            if($year>=1991 and $year<=2000) {
                $twenties++;
            } elseif ($year>=1981 and $year<=1990) {
                $thirties++;
            } elseif ($year>=1971 and $year<=1980) {
                $fourties++;
            } elseif ($year<=1970) {
                $plus++;
            }
        }
        return $this->render('department/show.html.twig', [
            'department' => $department,
            'users' => $users,
            'twenties' => $twenties,
            'thirties' => $thirties,
            'fourties' => $fourties,
            'plus' => $plus,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="department_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Department $department): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/edit.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="department_delete", methods={"POST"})
     */
    public function delete(Request $request, Department $department, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$department->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($department);
            $entityManager->flush();
        }
        $flashy->success('Departement supprimé!');

        return $this->redirectToRoute('department_index');
    }

}
