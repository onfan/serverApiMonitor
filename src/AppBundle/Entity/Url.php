<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 07/03/15
 * Time: 17:50
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Ferrandini\Urlizer;

class Url
{

    /** @var  String */
    protected $url;

    /** @var  Integer */
    protected $status;

    /** @var  ArrayCollection */
    protected $keys;

    /** @var  Integer */
    protected $keyStatus;
    public function __construct()
    {
        $this->keys = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->getUrl();
    }

    /**
     * @return String
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param String $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return ArrayCollection
     */
    public function getKeys()
    {
        return $this->keys;
    }

    public function addKey(Key $key)
    {
        $this->keys->add($key);
    }

    public function removeKey(Key $key)
    {
        $this->keys->removeElement($key);
    }


    public function getKeysNameList()
    {
        return 'keys:' . $this->getUrl();
    }

    public function getSlug()
    {
        return str_replace('/', '__', $this->getUrl());
    }

    public static function createFromSlug($slug)
    {
        $url = new self;
        $url->setUrl(str_replace('__', '/', $slug));

        return $url;
    }

    /**
     * @return int
     */
    public function getKeyStatus()
    {
        return $this->keyStatus;
    }

    /**
     * @param int $keyStatus
     */
    public function setKeyStatus($keyStatus)
    {
        $this->keyStatus = $keyStatus;
    }
}