<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 06/03/15
 * Time: 22:38
 */

namespace AppBundle\Manager;

use Predis\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UrlManager
{
    CONST LIST_URLS = 'urls';

    /** @var  \Predis\Client */
    public $redis;

    /**
     * @param $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @return array
     */
    public function getAllUrls()
    {
        return $this->redis->smembers(self::LIST_URLS);
    }

    public function addUrl($url)
    {
        $this->redis->sadd(self::LIST_URLS, $url);
    }

    public function setVisited($url, $status, $time = null)
    {
        if (null == $time){
            $time = time();
        }

        $this->redis->sadd($url .':' . $status, $time);

    }
}