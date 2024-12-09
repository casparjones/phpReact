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
        // echo "Publishing message: '$message' to channel '$channel'\n";
        return $this->client->publish($channel, json_encode($message));
    }
}
