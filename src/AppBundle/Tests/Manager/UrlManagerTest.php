<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 06/03/15
 * Time: 22:39
 */

namespace AppBundle\Tests\Manager;


use AppBundle\Manager\UrlManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UrlManagerTest extends KernelTestCase
{

    protected function getContainer()
    {
        static::bootKernel();

        return static::$kernel->getContainer();
    }

    protected function getRedisService()
    {
        return $this->getContainer()->get('snc_redis.default');
    }

    /**
     * @test
     */
    public function shouldAddUrlToList()
    {
        $redis = $this->getRedisService();
        $redis->flushdb();

        $urlManager = $this->getContainer()->get('monitor.manager.url');
        $urlManager->addUrl('api/v4');

        $this->assertTrue($redis->sismember('urls', 'api/v4'));
    }

    /**
     * @test
     */
    public function shouldReturnListOfUrlsToTest()
    {
        $redis = $this->getRedisService();
        $redis->flushdb();
        $urlManager = new UrlManager($redis);
        $urlManager->addUrl('api/v4');
        $urlManager->addUrl('api/v5');
        $urlManager->addUrl('api/v6');

        $expectedResults = array(
            'api/v4', 'api/v5', 'api/v6'
        );

        $urlsStored = $urlManager->getAllUrls();
        foreach ($expectedResults as $expected) {
            $this->assertTrue(in_array($expected, $urlsStored), $expected . ' not find');
        }
    }

    /**
     * @test
     */
    public function shouldStoreDifferentsStatus()
    {
        $redis = $this->getRedisService();
        $redis->flushdb();

        $urlManager = $this->getContainer()->get('monitor.manager.url');
        $url = 'api/v4/search';
        $status = 200;
        $time = 55555;
        $urlManager->setVisited($url, $status, $time);

        $this->assertTrue($redis->sismember('api/v4/search:200', 55555));
    }
}