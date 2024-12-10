<?php

namespace App\Controller;

use App\PubSub\Channels\CountDownChannel;
use App\PubSub\Channels\SystemChannel;
use App\PubSub\Publisher;
use Predis\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class welcomeController extends AbstractController
{
    #[Route('/', name: 'welcome_index', methods: ['GET'])]
    public function index(): Response
    {
        $redisClient = new Client();
        $pub = new Publisher($redisClient);
        $pub->publish(CountDownChannel::getName(), [
            'message' => "start the countdown at " . date_create()->format("H:i:s"),
            'countDown' => 15
        ]);

        $pub->publish(SystemChannel::getName(), "controller start the action");

        return $this->render('welcome/index.html.twig');
    }
}
