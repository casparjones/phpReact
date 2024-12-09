<?php

namespace App\PubSub\Channels;

use App\PubSub\Channels\Channel;
use stdClass;

class SystemChannel extends BaseChannel implements Channel
{

    public static function getName(): string
    {
        return 'consumer:system';
    }

    protected function processMessage(stdClass $message): void
    {
        $this->echo("Channel '{$this->getName()}' process the Message");
        $this->echo("receive message: " . json_encode($message));
    }
}