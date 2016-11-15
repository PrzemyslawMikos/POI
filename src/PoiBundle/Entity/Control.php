<?php

namespace PoiBundle\Entity;

/**
 * Control
 */
class Control
{
    /**
     * @var integer
     */
    private $tonextversion;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set tonextversion
     *
     * @param integer $tonextversion
     *
     * @return Control
     */
    public function setTonextversion($tonextversion)
    {
        $this->tonextversion = $tonextversion;

        return $this;
    }

    /**
     * Get tonextversion
     *
     * @return integer
     */
    public function getTonextversion()
    {
        return $this->tonextversion;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

