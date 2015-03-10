<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 06/03/15
 * Time: 22:38
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Url;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @return ArrayCollection
     */
    public function getAllUrls()
    {
        $urls = $this->redis->smembers(self::LIST_URLS);
        $collection = new ArrayCollection();
        foreach ($urls as $url) {
            $urlObject = new Url();
            $urlObject->setUrl($url);
            $collection->add($urlObject);
        }

        return $collection;
    }

    public function addUrl($url)
    {
        $this->redis->sadd(self::LIST_URLS, $url);
    }

    public function setVisited($url, $status, $time = null)
    {
        if (null == $time) {
            $time = time();
        }

        $this->redis->zadd($url, $time, $status);
    }

    public function getLastStatus($url)
    {
        $status = $this->redis->zrangebyscore(
            $url,
            0,
            '+inf',
            array(
                'limit'      => array(0, 1),  // [0]: offset / [1]: count
                'withscores' => false
            )
        );

        if (isset($status[0])) {
            return $status[0];
        } else {
            return null;
        }

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