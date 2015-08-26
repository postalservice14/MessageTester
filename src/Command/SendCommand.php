<?php

namespace Dn\MessageTester\Command;

use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends Command
{
    protected function configure()
    {
        $this->setName('send')
            ->addOption('host', 'H', InputOption::VALUE_REQUIRED, 'AMQP Hostname', 'localhost')
            ->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'AMQP Port', '5672')
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'AMQP Username', 'guest')
            ->addOption('password', 'P', InputOption::VALUE_REQUIRED, 'AMQP Password', 'guest')
            ->addOption('exchange', 'e', InputOption::VALUE_REQUIRED, 'Exchange to push message on', 'hello')
            ->addOption('message', 'm', InputOption::VALUE_REQUIRED, 'Message body', 'Hello World')
            ->addOption('ssl', 's', InputOption::VALUE_NONE, 'Is SSL connection')
            ->setDescription('Send message');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('ssl')) {
            $output->writeln('SSL Enabled');
            $connection = new AMQPSSLConnection(
                $input->getOption('host'),
                $input->getOption('port'),
                $input->getOption('username'),
                $input->getOption('password')
            );
        } else {
            $output->writeln('SSL Disabled');
            $connection = new AMQPStreamConnection(
                $input->getOption('host'),
                $input->getOption('port'),
                $input->getOption('username'),
                $input->getOption('password')
            );
        }

        $channel = $connection->channel();

        $message = new AMQPMessage($input->getOption('message'), array('content_type' => 'text/plain', 'delivery_mode' => 2));
        $channel->basic_publish($message, $input->getOption('exchange'));

        $channel->close();
        $connection->close();
    }
}