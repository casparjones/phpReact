<?php

namespace App\Controller;

use App\Entity\Product;
use App\PubSub\Channels\CountDownChannel;
use App\PubSub\Channels\SystemChannel;
use App\PubSub\Publisher;
use App\PubSub\Subscriber;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Symfony\Component\HttpFoundation\Request;
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
        //$pub->publish("system", "controller start the action");
        $pub->publish(SystemChannel::getName(), [
            'message' => "start the countdown at " . date_create()->format("H:i:s"),
            'countDown' => 15
        ]);

        return $this->render('welcome/index.html.twig');
    }
}
