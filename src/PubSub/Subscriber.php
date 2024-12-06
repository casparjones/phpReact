<?php

namespace App\PubSub;

use Predis\Client;
use React\EventLoop\LoopInterface;

class Subscriber
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function subscribe(LoopInterface $loop, string $channel): void
    {
        echo "Subscribing to channel: '$channel'\n";

        // Startet die Pub/Sub-Verbindung und lässt den Event-Loop weiterlaufen
        $loop->addTimer(0, function () use ($channel) {
            echo "test 1\n"; 
            // Startet den Pub/Sub-Loop, der Nachrichten empfangen kann
            $pubsub = $this->client->pubSubLoop();


            $pubsub->subscribe($channel);
            /* @var stdClass $message */
            foreach ($pubsub as $message) {
                var_dump($message);
            }
        });

        // Der Event-Loop bleibt weiterhin aktiv und blockiert nicht
        // pubSubLoop wird in einem anderen Timer ausgeführt und blockiert nicht den Haupt-Event-Loop
    }
}
