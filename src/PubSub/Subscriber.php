<?php

namespace App\PubSub;

use App\PubSub\Channels\BaseChannel;
use App\PubSub\Channels\Channel;
use Predis\Client;
use React\EventLoop\Loop;
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
        // ReactPHP Event-Loop erstellen
        $loop = Loop::get();
        $channel->setLoop($loop);
        $channel->setRedisConfig($this->redisParameter);
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
