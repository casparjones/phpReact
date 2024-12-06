<?php

namespace App\Command;

use App\PubSub\Publisher;
use App\PubSub\Subscriber;
use Predis\Client;
use React\EventLoop\Factory;
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
        $loop = Factory::create();

        // Publisher und Subscriber erstellen
        $publisher = new Publisher($redisClient);
        $subscriber = new Subscriber($redisClient);

        // Subscriber starten, um Nachrichten zu empfangen
        $subscriber->subscribe($loop, 'news'); // Kanalname als string übergeben

        // Publisher sendet Nachrichten an den 'news'-Channel
        $loop->addTimer(1, function() use ($publisher) {
            $publisher->publish('news', 'First message');
        });

        $loop->addTimer(2, function() use ($publisher) {
            $publisher->publish('news', 'Second message');
        });

        $loop->addTimer(3, function() use ($publisher) {
            $publisher->publish('news', 'Third message');
        });

        // Event-Loop läuft für immer und wartet auf Nachrichten
        $output->writeln('Pub/Sub system is running...');
        $loop->run();  // Der Event-Loop läuft dauerhaft und wartet auf Nachrichten

        return Command::SUCCESS;
    }
}
