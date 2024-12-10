<?php

namespace App\PubSub;

use App\PubSub\Channels\BaseChannel;
use App\PubSub\Channels\Channel;
use App\PubSub\Loop\LoopInterface;
use Predis\Client;

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

    public static function getTasks(array $consumerNames): array
    {
        $tasks = [];
        foreach ($consumerNames as $consumerName) {
            $tasks = array_merge($tasks, self::getConsumerTasks($consumerName));
        }
        return $tasks;
    }

    public static function getConsumerTasks(string $consumerName): array
    {
        $redisConfigJson = getenv('REDIS_SETTING');
        $redisConfig = json_decode($redisConfigJson, true);
        $redisClient = new Client($redisConfig);
        return $redisClient->lrange($consumerName, 0, -1);
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
