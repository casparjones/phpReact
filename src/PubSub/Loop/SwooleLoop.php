<?php

namespace App\PubSub\Loop;

use App\Loop\LoopInterface;

class SwooleLoop implements LoopInterface
{
    /**
     * Fügt einen wiederkehrenden Timer hinzu.
     *
     * @param float $interval Intervall in Sekunden.
     * @param callable $callback Funktion, die bei jedem Intervall aufgerufen wird.
     * @return void
     */
    public function addPeriodicTimer(float $interval, callable $callback): void
    {
        $milliseconds = (int) ($interval * 1000); // Swoole erwartet Millisekunden
        \Swoole\Timer::tick($milliseconds, $callback);
    }

    /**
     * Fügt einen einmaligen Timer hinzu oder startet eine Koroutine, wenn delay 0 ist.
     *
     * @param float $delay Verzögerung in Sekunden, bevor die Funktion ausgeführt wird.
     * @param callable $callback Funktion, die nach Ablauf der Verzögerung oder sofort bei delay 0 aufgerufen wird.
     * @return void
     */
    public function addTimer(float $delay, callable $callback): void
    {
        if ($delay === 0.0) {
            \Swoole\Timer::after(1, $callback); // 1 Millisekunde
        } else {
            $milliseconds = (int) ($delay * 1000); // Swoole erwartet Millisekunden
            \Swoole\Timer::after($milliseconds, $callback);
        }
    }
}
