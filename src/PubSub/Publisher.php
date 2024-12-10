<?php

namespace App\PubSub;

use Predis\Client;
use stdClass;

class Publisher
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function publish(string $channel, mixed $message): int
    {
        if(is_string($message)) {
            $message = ['message' => $message];
        }
        // echo "Publishing message: '$message' to channel '$channel'\n";
        $jsonMessage = json_encode($message);
        // return $this->client->publish($channel, $jsonMessage);
        return $this->client->lpush($channel, [$jsonMessage]);
    }
}
