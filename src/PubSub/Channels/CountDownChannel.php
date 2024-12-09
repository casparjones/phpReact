<?php

namespace App\PubSub\Channels;

use App\PubSub\Channels\Channel;
use stdClass;

class CountDownChannel extends BaseChannel implements Channel
{

    public static function getName(): string
    {
        return 'consumer:countdown';
    }

    protected function processMessage(stdClass $message): void
    {
        $this->echo("Channel '{$this->getName()}' process the Message");
        if(!empty($message->countDown)) {
            $array = range(1, $message->countDown);
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