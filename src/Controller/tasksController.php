<?php

namespace App\Controller;

use App\PubSub\Channels\CountDownChannel;
use App\PubSub\Channels\SystemChannel;
use App\PubSub\Publisher;
use App\PubSub\Subscriber;
use Predis\Client;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class tasksController extends AbstractController
{
    #[Route('/', name: 'welcome_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('welcome/index.html.twig', ['tasks' => Subscriber::getTasks([CountDownChannel::getName(), SystemChannel::getName()])]);
    }

    #[Route('/tasks', name: 'tasks_get', methods: ['GET'])]
    public function getTasks(): Response {
        return new JsonResponse(['success' => true, 'tasks' => Subscriber::getTasks([CountDownChannel::getName(), SystemChannel::getName()])]);
    }

    #[Route('/task/countdown', name: 'start_countdown', methods: ['GET'])]
    public function startCountdown(): JsonResponse
    {
        $redisClient = new Client();
        $pub = new Publisher($redisClient);
        $pub->publish(CountDownChannel::getName(), [
            'message' => "start the countdown at " . date_create()->format("H:i:s"),
            'countDown' => 15
        ]);
        return new JsonResponse(['message' => 'start Task']);
    }

    #[Route('/task/system', name: 'start_system', methods: ['GET'])]
    public function startSystem(): JsonResponse
    {
        $redisClient = new Client();
        $pub = new Publisher($redisClient);
        $pub->publish(SystemChannel::getName(), [
            'message' => "send message to system queue " . date_create()->format("H:i:s")
        ]);
        return new JsonResponse(['message' => 'start Task']);
    }
}
