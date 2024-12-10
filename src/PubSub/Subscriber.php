<?php

namespace App\PubSub;

use App\PubSub\Channels\BaseChannel;
use App\PubSub\Channels\Channel;
use Predis\Client;
use \App\Loop\LoopInterface;

class Subscriber
{
    private array $redisParameter = [];
    private array $channels = [];
    private bool $isRunning = true;
    private mixed $consumerId;

    public function __construct(array $redisConfig, $consumerId)
    {
        $this->redisParameter = $redisConfig;
        $this->consumerId = $consumerId;
    }

    public function addChannel(LoopInterface $loop, Channel $channel): void
    {
        $channel->setLoop($loop);
        $channel->setRedisConfig($this->redisParameter);
        $channel->setConsumer($this->consumerId);
        $this->channels[$channel->getName()] = $channel;
    }

    public function run(): void
    {
        // Starte alle Kanäle
        foreach ($this->channels as $channel) {
            $channel->run();
        }
    }


}
