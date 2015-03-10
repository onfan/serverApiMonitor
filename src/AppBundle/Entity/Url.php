<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 07/03/15
 * Time: 17:50
 */

namespace AppBundle\Entity;


class Url {

    /** @var  String */
    protected $url;

    /** @var  Integer */
    protected $status;

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




}