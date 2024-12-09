<?php

namespace App\PubSub\Channels;

abstract class BaseChannel implements Channel
{
    private bool $running = true;
    private string $name;

    public function __construct()
    {
        $this->name = $this->getName();
    }

    public static function getName(): string
    {
        return static::class;
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

    /**
     * Abstrakte Methode, die in der Kindklasse implementiert werden muss.
     * Diese Methode verarbeitet die eigentliche Nachricht.
     */
    abstract protected function processMessage($message): void;
}
