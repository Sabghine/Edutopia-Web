<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Symfony\Component\Routing\Annotation\Route;

use \Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;





/**
 * @Route("/classe")
 */

class ClasseController extends AbstractController
{
    /**
     * @Route("/contact" )
     */
    public function  contact()
    {


        $account_sid = 'AC51c5011c23e0e8fccc6ab733a143e74e';
        $auth_token = '842235100bf3994b98120c07b4d858ca';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with Voice capabilities
        $twilio_number = "+13342768708";

// Where to make a voice call (your cell phone?)
        $to_number = "+21628392382";

        $client = new Client($account_sid, $auth_token);

        $message =$client->account->messages
            ->create("+21628392382", // to
                ["body" => "new class has ben added", "from" => "+13342768708"]
            );


        return $this->render('classe/index.html.twig' );
    }
    /**
     * @Route("/email" )
     */
    public function mails()
    {
        $client = static::createClient();

        // enables the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/path/to/above/action');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // checks that an email was sent
        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Hello Email', $message->getSubject());
        $this->assertSame('azizhelmi.louati@esprit.tn', key($message->getFrom()));
        $this->assertSame('nour.bouali@esprit.tn', key($message->getTo()));
        $this->assertSame(
            'You should see me from the profiler!',
            $message->getBody()
        );
    }

    /**
     * @Route("/audio", name="audio", methods={"POST","GET"})
     */
    public function audio()
    {

        if (isset($_POST['txt'])){
            $txt = $_POST['txt'];
            $txt = htmlspecialchars($txt);
            $txt = rawurlencode($txt);
            $html = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=en-IN');
            $player = "<audio controls='controls' autoplay><source src='data:audio/mpeg;base64," . base64_encode($html) . "'></audio>";
            echo $player;


        }
    }

    /**
     * @Route("/recherche", name="recherche", methods={"POST","GET"})
     */

    public function recherche(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $ch=$request->get("name");



        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Classe p
            WHERE p.name LIKE :data'
        )
            ->setParameter('data',"%".$ch."%");



        return $this->render('classe/index.html.twig', [
            'classes' => $query->getResult(),

        ]);
    }




    /**
     * @Route("/", name="classe_index", methods={"GET"})
     */
    public function index(ClasseRepository $classeRepository): Response
    {
        return $this->render('classe/login.html.twig', [
            'classes' => $classeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="classe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($classe);
            $entityManager->flush();
            //$this->contact();

            return $this->redirectToRoute('classe_index');
        }

        return $this->render('classe/new.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="classe_show", methods={"GET"})
     */
    public function show(Classe $classe): Response
    {
        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="classe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Classe $classe): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('classe_index');
        }

        return $this->render('classe/edit.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="classe_delete", methods={"POST"})
     */
    public function delete(Request $request, Classe $classe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($classe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('classe_index');
    }
}