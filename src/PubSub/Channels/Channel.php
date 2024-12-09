<?php

namespace App\PubSub\Channels;

/**
 * Interface für Channels
 */
interface Channel
{
    public static function getName(): string; // Gibt den Namen des Channels zurück

    public function execute($message): void; // Verarbeitet eine Nachricht
}