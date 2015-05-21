<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 22/5/15
 * Time: 0:09
 */

namespace AppBundle\Command;


use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerCommand {

    protected function configure()
    {
        $this
            ->setName('job:worker')
            ->setDescription('Manages the beanstalk stuff');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $log = $this->getContainer()->get('monolog.logger.command');
        $startTime = time();
        $seconds = 20;

        while (true) {

            // check urls every X SECONDS
            $this->checkStatus($output, $startTime);
            usleep($seconds);
        }
    }

    protected function checkStatus($output, $startTime)
    {
        $memory = memory_get_usage(true) * 3;

        if ($memory < memory_get_usage(true)) {
            $output->writeln("<error>Worker is using too much RAM, dying</error>");
            exit(1);
        }

        $timeElapsed = time() - $startTime;
        if ($timeElapsed > 800) {
            $output->writeln("<info>Worker is working for 12 minutes -> dying</info>");
            exit(1);
        }
    }

}
