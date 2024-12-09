<?php

namespace App\Command;

use App\PubSub\Channels\CountDownChannel;
use App\PubSub\Channels\SystemChannel;
use App\PubSub\Publisher;
use App\PubSub\Subscriber;
use Predis\Client;
use React\EventLoop\Loop;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RedisPubSubCommand extends Command
{
    protected static $defaultName = 'app:redis-pubsub';

    protected function configure(): void
    {
        $this
            ->setDescription('A simple Pub/Sub system using Redis and Predis')
            ->setHelp('This command demonstrates a Redis Pub/Sub system with Predis and ReactPHP.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Redis-Client erstellen

// Redis-Client-Konfiguration
        $redisConfig = [
            'scheme' => 'tcp',       // Verwendet das TCP-Protokoll
            'host'   => 'redis', // Ersetze dies durch die IP-Adresse deines Redis-Hosts
            'port'   => 6379,        // Der Standardport für Redis
        ];

        // Erstellen eines neuen Predis-Client-Objekts
        $redisClient = new Client($redisConfig);

        // ReactPHP Event-Loop erstellen
        $loop = Loop::get();

        // Publisher und Subscriber erstellen
        $subscriber = new Subscriber($redisConfig);

        // Subscriber starten, um Nachrichten zu empfangen
        $subscriber->addChannel(new CountDownChannel());
        $subscriber->addChannel(new SystemChannel());

        $subscriber->run($loop);

        // Event-Loop läuft für immer und wartet auf Nachrichten
        $output->writeln('Pub/Sub system is running...');
        $loop->run();  // Der Event-Loop läuft dauerhaft und wartet auf Nachrichten

        return Command::SUCCESS;
    }
}
