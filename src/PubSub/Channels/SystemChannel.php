<?php

namespace App\PubSub\Channels;

use App\PubSub\Channels\Channel;

class SystemChannel extends BaseChannel implements Channel
{

    public static function getName(): string
    {
        return 'system';
    }

    protected function processMessage($message): void
    {
        $this->echo("Channel '{$this->getName()}' process the Message");
        var_dump($message);
    }
}