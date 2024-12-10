<?php

namespace App\PubSub\Loop;

interface LoopInterface
{
    /**
     * Fügt einen wiederkehrenden Timer hinzu.
     *
     * @param float $interval Intervall in Sekunden.
     * @param callable $callback Funktion, die bei jedem Intervall aufgerufen wird.
     * @return void
     */
    public function addPeriodicTimer(float $interval, callable $callback): void;

    /**
     * Fügt einen einmaligen Timer hinzu.
     *
     * @param float $delay Verzögerung in Sekunden, bevor die Funktion ausgeführt wird.
     * @param callable $callback Funktion, die nach Ablauf der Verzögerung aufgerufen wird.
     * @return void
     */
    public function addTimer(float $delay, callable $callback): void;

}
