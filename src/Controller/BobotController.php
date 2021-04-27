<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

class BobotController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    function messageAction(Request $request)
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Configuration for the BotMan WebDriver
        $config = [];
        // Create BotMan instance
        $botman = BotManFactory::create($config);
        // Give the bot some things to listen for.
        $botman->hears('(hello|hi|hey)', function (BotMan $bot) {
            $bot->reply('Hello!');
        });
        $botman->hears('(?)', function (BotMan $bot) {
            $bot->reply('Dans cette interface, vous trouverez la liste des matières.');
            $bot->reply('Vous pouvez seulement les consultez.');
        });
        $botman->hears('(recherche)', function (BotMan $bot) {
            $bot->reply('Concernant la recherche, vous trouverez en haut un barre de recherche');
            $bot->reply('en cas ou il ya aucun résultat trouvé, un message sera affiché');
            $bot->reply('Sinon, un lien vers la résultat sera affiché');
        });
        // Set a fallback
        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I did not understand.');
        });
        // Start listening
        $botman->listen();
        return new Response();
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('subject/index.html.twig');
    }

    /**
     * @Route("/chatframe", name="chatframe")
     */
    public function chatframeAction(Request $request)
    {
        return $this->render('chat_frame.html.twig');
    }
}
