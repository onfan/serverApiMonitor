<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 07/03/15
 * Time: 17:50
 */

namespace AppBundle\Entity;


class Key
{

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

    public function __construct($name = null)
    {
        $this->name = $name;
    }
    /**
     *
     */
    public function __toString()
    {
        return $this->name;
    }


}
