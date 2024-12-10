<?php

namespace App\PubSub\Loop;

use App\Loop\LoopInterface;
use React\EventLoop\Loop;

class ReactLoop implements LoopInterface
{

    private ?\React\EventLoop\LoopInterface $loop;
    private string $consumer;

    public function __construct()
    {
        $this->loop = Loop::get();
    }

    /**
     * @inheritDoc
     */
    public function addPeriodicTimer(float $interval, callable $callback): void
    {
        $this->loop->addPeriodicTimer($interval, $callback);
    }

    /**
     * @inheritDoc
     */
    public function addTimer(float $delay, callable $callback): void
    {
        $this->loop->addTimer($delay, $callback);
    }

}