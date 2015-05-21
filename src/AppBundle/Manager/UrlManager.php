<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 06/03/15
 * Time: 22:38
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Key;
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
            $this->populateKeysFor($urlObject);

            $collection->add($urlObject);
        }

        return $collection;
    }


    public function populateKeysFor(Url $url)
    {
        foreach ($this->redis->hgetall($url->getKeysNameList()) as $key => $value) {
            $url->addKey(new Key($key));
        }

        return $url;
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

    public function setKeyStatus(Url $url, $key, $status)
    {
        $this->redis->hset($url->getKeysNameList(), $key, $status);
    }

    public function getKeyStatus(Url $url, $key)
    {
        $this->redis->hget($url->getKeysNameList(), $key);
    }

    /**
     * @param Url $url
     * @return bool
     */
    public function hasKeysError(Url $url)
    {
        $errors = [];
        foreach ($this->redis->hgetall($url->getKeysNameList()) as $key => $value) {
            if ($value === 'error') {
                $errors[$key] = $value;
            }
        }

        return count($errors) > 0;
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

    public function setKeys(Url $url)
    {
        //delete first all keys...
        $this->redis->del($url->getKeysNameList());

        foreach ($url->getKeys() as $key) {
            $this->redis->hset($url->getKeysNameList(), $key->getName(), 'unknown');
        }

    }

    public function delete(Url $url)
    {
        $this->redis->del($url->getKeysNameList());
        $this->redis->srem(self::LIST_URLS, $url->getUrl());
    }
}