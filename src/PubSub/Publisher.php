<?php

namespace App\PubSub;

use Predis\Client;

class Publisher
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function publish(string $channel, string $message)
    {
        // echo "Publishing message: '$message' to channel '$channel'\n";
        $this->client->publish($channel, $message);
    }
}
