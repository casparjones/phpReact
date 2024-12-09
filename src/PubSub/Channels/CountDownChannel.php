<?php

namespace App\PubSub\Channels;

use App\PubSub\Channels\Channel;

class CountDownChannel extends BaseChannel implements Channel
{

    public static function getName(): string
    {
        return 'countdown';
    }

    protected function processMessage($message): void
    {
        $payload = json_decode($message->payload, true);
        $this->echo("Channel '{$this->getName()}' process the Message");
        if(!empty($payload['countDown'])) {
            $array = range(1, $payload['countDown']);
            foreach ($array as $index) {
                if (!$this->isRunning()) {
                    $this->echo("Channel '{$this->getName()}' stopped during execution.");
                    break;
                }

                sleep(1);
                $this->echo("Processing message in {$this->getName()}, step $index...");
            }
        }
    }
}