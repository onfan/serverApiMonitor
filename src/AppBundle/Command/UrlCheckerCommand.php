<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 03/03/15
 * Time: 00:01
 */

namespace AppBundle\Command;

use AppBundle\EventListener\ErrorEvent;
use AppBundle\EventListener\EventsName;
use AppBundle\EventListener\Not200Event;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;


class UrlCheckerCommand extends BaseCommand
{

    protected $log;

    protected $redis;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('api:check')
            ->setDescription("Check all venues in onfan that don't have foursquare_id and try to find id");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var \AppBundle\Manager\UrlManager $urlManager */
        $urlManager = $this->getContainer()->get('monitor.manager.url');

        $urls = $urlManager->getAllUrls();
        foreach ($urls as $url) {
            $this->checkUrl($url, $output);
        }
    }

    protected function checkUrl($url, $output)
    {

        $client = new Client(
            array(
                "base_url" => "http://www.onfan.com/"
            )
        );

        $request = $client->createRequest('GET', $url);
        $log = $this->getContainer()->get('monolog.logger.command');
        /** @var \AppBundle\Manager\UrlManager $urlManager */
        $urlManager = $this->getContainer()->get('monitor.manager.url');

        try {
            $response = $client->send($request);
            $code = (int)$response->getStatusCode();
            $urlManager->setVisited($url, $code);

            if ($code >= 300) {
               $this->throwEvent();
            }

            $json = $response->json();
            //check if has all the expected fields
            $expectedKeys = array('id', 'name');
            foreach ($expectedKeys as $key) {
                if (!array_key_exists($key, $json)) {
                    $message = sprintf("This key %s doesn't exist in response", $key);
                    $this->notifyError($message, $log, $output);
                }
            }


        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $log->error('URL not found: ' . $url);
            $output->writeln($e->getMessage());
            $urlManager->setVisited($url, 404);

            $this->throwEvent();

        }
    }

    protected function throwEvent()
    {
        $event = new Not200Event();
        $dispatcher = $this->getContainer()->get('event_dispatcher');
        $dispatcher->dispatch(EventsName::STATUS_NOT_200, $event);
    }
    protected function notifySuccess($message, $url, $log, $output)
    {
        $log->info($message);
        $output->writeln($message);

    }

    protected function notifyError($message, $log, $output)
    {
        $log->error($message);
    }
}