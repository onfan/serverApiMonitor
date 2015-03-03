<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 03/03/15
 * Time: 00:01
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;


class UrlCheckerCommand extends ContainerAwareCommand
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

        $this->redis = $this->getContainer()->get('snc_redis.default');

        $client = new Client(
            array(
                "base_url" => "http://www.onfan.com/api/v4/"
            )
        );
        $url = 'specialities/1';

        $request = $client->createRequest('GET', 'specialities/1');
        $this->log = $this->getContainer()->get('monolog.logger.command');

        try {
            $response = $client->send($request);
            $code = $response->getStatusCode();

            //save status somewhere
            $this->notifySuccess(sprintf('URL %s has status %s', $url, $code), $url);
            $json = $response->json();

            //check if has all the expected fields
            $expectedKeys = array('id', 'name');
            foreach ($expectedKeys as $key) {
                if (!array_key_exists($key, $json)) {
                    $message = sprintf("This key %s doesn't exist in response", $key);
                    $this->notifyError($message);
                }
            }


        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->log->error('URL not found: ' . $url);
            $output->writeln($e->getMessage());
        }


    }

    protected function notifySuccess($message, $url)
    {
        $this->redis->sadd($url, time());
        $this->log->info($message);
    }

    protected function notifyError($message)
    {
        $this->log->error($message);
    }
}