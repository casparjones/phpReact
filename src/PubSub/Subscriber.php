<?php

namespace App\PubSub;

use App\PubSub\Channels\BaseChannel;
use App\PubSub\Channels\Channel;
use Predis\Client;
use React\EventLoop\LoopInterface;

class Subscriber
{
    private array $redisParameter = [];
    private array $channels = [];
    private bool $isRunning = true;

    public function __construct(array $redisConfig)
    {
        $this->redisParameter = $redisConfig;
    }

    public function addChannel(Channel $channel): void
    {
        $this->channels[$channel->getName()] = $channel;
    }

    public function run(LoopInterface $loop): void
    {
        // Starte alle Kanäle
        foreach ($this->channels as $channel) {
            $loop->addTimer(0, function () use ($loop, $channel) {
                BaseChannel::println("Channel: " . $channel->getName());
                $this->startPubSubLoop($loop, $channel);
            });
        }
    }

    private function startPubSubLoop(LoopInterface $loop, Channel $channel): void
    {
        if (!$this->isRunning) {
            BaseChannel::println("Channel: {$channel->getName()} is running - cancel");
            return;
        }

        echo "Starting non-blocking Pub/Sub loop for channel: '{$channel->getName()}'\n";

        $pubsubClient = new Client($this->redisParameter); // Separate Verbindung erstellen
        $pubsub = $pubsubClient->pubSubLoop();
        $pubsub->subscribe($channel::getName());

        $loop->addPeriodicTimer(0.5, function () use ($pubsub, $channel, $loop) {
            BaseChannel::println("Periodic Timer run: '{$channel->getName()}'");

            if (!$this->isRunning || !$channel->isRunning()) {
                BaseChannel::println("Stopping Pub/Sub loop for channel: '{$channel->getName()}'");
                $pubsub->unsubscribe();
                return;
            }

            try {
                // Hole die nächste Nachricht aus der Pub/Sub-Schleife
                $message = $pubsub->current();
                if ($message && $message->channel === $channel::getName()) {
                    $channel->execute($message);
                    $pubsub->next();
                } else {
                    BaseChannel::println("No consumer found for channel: '{$channel->getName()}'");
                }
            } catch (\Exception $e) {
                BaseChannel::println("Error on channel '{$channel->getName()}': {$e->getMessage()} - restart channel");
                $this->startPubSubLoop($loop, $channel);
            }
        });
    }

}
