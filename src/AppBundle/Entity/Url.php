<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 07/03/15
 * Time: 17:50
 */

namespace AppBundle\Entity;


class Url {

    /** @var  String */
    protected $name;

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}