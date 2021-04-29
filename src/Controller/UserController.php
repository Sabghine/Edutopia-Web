<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use ContainerFijPxRE\getClasseControllerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/voice", name="aziz2",  methods={"GET","POST"})
     */
    public function voice()
    {
        /** Uncomment and populate these variables in your code */
 $text = 'Text to synthesize';

// create client object

        putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/service-account.json');
        $client = new TextToSpeechClient()
        ;
        $client->setAuthConfig('/path/to/service-account.json');


        $client->useApplicationDefaultCredentials();

        $input_text = (new SynthesisInput())
            ->setText($text);

// note: the voice can also be specified by name
// names of voices can be retrieved with $client->listVoices()
        $voice = (new VoiceSelectionParams())
            ->setLanguageCode('en-US')
            ->setSsmlGender(SsmlVoiceGender::FEMALE);

        $audioConfig = (new AudioConfig())
            ->setAudioEncoding(AudioEncoding::MP3);

        $response = $client->synthesizeSpeech($input_text, $voice, $audioConfig);
        $audioContent = $response->getAudioContent();

        file_put_contents('output.mp3', $audioContent);
        print('Audio content written to "output.mp3"' . PHP_EOL);

        $client->close();
    }
    /**
     * @Route("/vice" )
     */
    public function  vice()
    {
        $response = new VoiceResponse();
         $say = $response->say('Hi', ['voice' => 'Polly.Joanna']);
        $say->break_(['strength' => 'x-weak', 'time' => '100ms']);
        $say->emphasis('Words to emphasize', ['level' => 'moderate']);
        $say->p('Words to speak');
        $say->append('aaaaaa');
        $say->phoneme('Words to speak', ['alphabet' => 'x-sampa', 'ph' => 'pɪˈkɑːn']);
        $say->append('bbbbbbb');
        $say->prosody('Words to speak', ['pitch' => '-10%', 'rate' => '85%',
            'volume' => '-6dB']);
        $say->s('Words to speak');
        $say->say_as('Words to speak', ['interpret-as' => 'spell-out']);
        $say->sub('Words to be substituted', ['alias' => 'alias']);
        $say->w('Words to speak');


        return $response;


    }



    /**
     * @Route("/mail", name="aziz",  methods={"GET","POST"})
     */
    public function mail($name=null, \Swift_Mailer $mailer, UserRepository $userRepository   )
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('azizhelmi.louati@esprit.tn')
            ->setTo('zizoulouati7@gmail.com')
            ->setBody(
               ' sent by the administration , you have passed 10 absence '
            )
        ;

        $mailer->send($message);
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);


    }


    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    /**
     * @Route("/add", name="wissem",  methods={"GET","POST"})
     */
    public function add(Request $request , UserRepository $userRepository ,$name=null, \Swift_Mailer $mailer): Response
    {
        $user = $userRepository->findOneBySomeField($request->get("wissem"));

        $user->setNbasbsece($user->getNbasbsece() + 1 );

        if ($user->getNbasbsece()>10)
        {
            $this->mail($name=null, $mailer,  $userRepository );
            $user->setNbasbsece(0);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_index');

    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
