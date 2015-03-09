<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 06/03/15
 * Time: 22:38
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Url;
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

    /**
     * @param $url
     * @return bool
     */
    public function existsUrl($url)
    {
        return $this->redis->sismember(self::LIST_URLS, $url);
    }

    /**
     * @param $url
     * @return Url
     */
    public function getUrl($url)
    {
        $urlEntity = new Url();
        $urlEntity->setUrl($url);

        return $urlEntity;

    }
}