<?php

namespace App\PubSub\Channels;

use App\Loop\LoopInterface;
use Predis\Client;
use stdClass;

abstract class BaseChannel implements Channel
{
    private bool $running = true;
    private string $name;
    private LoopInterface $loop;
    private Client $client;
    protected string $consumer;

    public function __construct()
    {
        $this->name = $this->getName();
    }

    public static function getName(): string
    {
        return static::class;
    }

    public function processInitOutput(): void
    {
        $this->echo("Channel '{$this->getName()}' with consumer '{$this->consumer}' process the Message");
    }

    public function setLoop(LoopInterface $loop): void {
        $this->loop = $loop;
    }

    public function setRedisConfig($redisConfig): void {
        $this->client = new Client($redisConfig);

    }

    public static function println(string $message) {
        $time = date_create()->format("d.m.Y H:i:s");
        $formattedMessage = sprintf(
            "[\e[32m%s\e[0m] \e[34m%s\e[0m",
            $time, // Zeit in grüner Farbe
            $message // Nachricht in blauer Farbe
        );
        echo $formattedMessage . PHP_EOL;
    }

    public function echo(string $message): void
    {
        self::println($message);
    }

    public function isRunning(): bool
    {
        return $this->running;
    }

    public function run() : void {
        $this->loop->addTimer(0, function () {
            BaseChannel::println("Channel: " . $this->getName());
            $this->startListPolling($this->loop, $this);
        });
    }

    private function startListPolling(LoopInterface $loop, Channel $channel): void
    {
        $loop->addPeriodicTimer(0.1, function () use ($loop, $channel) {
            try {
                $message = $this->client->rpop($channel->getName());
                if ($message) {
                    $messageObject = json_decode($message);
                    BaseChannel::println("Message received on channel '{$channel->getName()}': $message");
                    $loop->addTimer(1, function () use ($channel, $messageObject) {
                        $channel->execute($messageObject);
                    });
                }
            } catch (\Exception $e) {
                BaseChannel::println("Error on channel '{$channel->getName()}': {$e->getMessage()}");
            }
        });
    }

    public function stop(): void
    {
        $this->running = false;
        $this->echo("Channel '{$this->name}' has been stopped");
    }

    public function execute($message): void
    {
        if (!$this->isRunning()) {
            $this->echo("Channel '{$this->name}' is not running. Skipping execution.");
            return;
        }

        $this->processMessage($message);
    }

    public function setConsumer(mixed $consumerId)
    {
        $this->consumer = $consumerId;
    }

    /**
     * Abstrakte Methode, die in der Kindklasse implementiert werden muss.
     * Diese Methode verarbeitet die eigentliche Nachricht.
     */
    abstract protected function processMessage(stdClass $message): void;
}
