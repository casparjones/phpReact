<?php

namespace App\Command;

use App\PubSub\Channels\CountDownChannel;
use App\PubSub\Channels\SystemChannel;
use App\PubSub\Loop\ReactLoop;
use App\PubSub\Publisher;
use App\PubSub\Subscriber;
use Predis\Client;
use React\EventLoop\Loop;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReactPubSubCommand extends Command
{
    protected static $defaultName = 'app:react-pubsub';
    private string $pubsubId;

    protected function configure(): void
    {
        $this
            ->setDescription('A simple Pub/Sub system using Redis and Predis')
            ->setHelp('This command demonstrates a Redis Pub/Sub system with Predis and ReactPHP.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->pubsubId = uniqid();

        // Redis-Client-Konfiguration
        $redisConfig = [
            'scheme' => 'tcp',       // Verwendet das TCP-Protokoll
            'host'   => 'redis', // Ersetze dies durch die IP-Adresse deines Redis-Hosts
            'port'   => 6379,        // Der Standardport für Redis
        ];

        // Erstellen eines neuen Predis-Client-Objekts
        $loop = new ReactLoop();

        // Publisher und Subscriber erstellen
        $subscriber = new Subscriber($redisConfig, $this->pubsubId);

        // Subscriber starten, um Nachrichten zu empfangen
        $subscriber->addChannel($loop, new CountDownChannel());
        $subscriber->addChannel($loop, new SystemChannel());

        $subscriber->run();

        // Event-Loop läuft für immer und wartet auf Nachrichten
        $output->writeln('Pub/Sub system is running...');

        return Command::SUCCESS;
    }
}
