<?php

namespace Dn\MessageTester\Command;

use Herrera\Json\Exception\FileException;
use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
{
    const MANIFEST_FILE = 'http://postalservice14.github.io/MessageTester/manifest.json';

    protected function configure()
    {
        $this->setName('update')
            ->setDescription('Update MessageTester to latest version.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Looking for updates...');

        try {
            $manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
        } catch (FileException $e) {
            if ($output->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }
            $output->writeln('<error>Unable to search for updates</error>');

            return 1;
        }

        if ($manager->update($this->getApplication()->getVersion(), true)) {
            $output->writeln('<info>Updated to latest version</info>');
        } else {
            $output->writeln('<comment>Already up-to-date</comment>');
        }

        return 0;
    }
}
