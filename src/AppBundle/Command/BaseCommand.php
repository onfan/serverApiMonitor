<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Doctrine\ORM\EntityManager;
use Onfan\SocialGraphBundle\SocialGraph;
use Doctrine\DBAL\Connection;

/**
 * Base command
 *
 * Every command inherits from this class
 */
abstract class BaseCommand extends ContainerAwareCommand
{

    /**
     * True if styles have been already loaded
     * @var bool $stylesLoaded
     */
    protected $stylesLoaded = false;

    /**
     * Load styles for commandline programs
     * @param OutputInterface &$output
     */
    protected function loadStyles(OutputInterface & $output)
    {
        if (!$this->stylesLoaded) {
            // onfan style¿?
            $style = new OutputFormatterStyle('yellow');
            $output->getFormatter()->setStyle('monitor', $style);

            $this->stylesLoaded = true;
        }
    }


    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @return \Doctrine\DBAL\Connection;
     */
    protected function getConnection()
    {
        return $this->getContainer()->get('database_connection');
    }


}
