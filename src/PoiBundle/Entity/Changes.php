<?php

namespace PoiBundle\Entity;

/**
 * Changes
 */
class Changes
{
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $actiontype;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \PoiBundle\Entity\Points
     */
    private $point;

    /**
     * @var \PoiBundle\Entity\Versions
     */
    private $version;


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Changes
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set actiontype
     *
     * @param integer $actiontype
     *
     * @return Changes
     */
    public function setActiontype($actiontype)
    {
        $this->actiontype = $actiontype;

        return $this;
    }

    /**
     * Get actiontype
     *
     * @return integer
     */
    public function getActiontype()
    {
        return $this->actiontype;
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

    /**
     * Set point
     *
     * @param \PoiBundle\Entity\Points $point
     *
     * @return Changes
     */
    public function setPoint(\PoiBundle\Entity\Points $point = null)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return \PoiBundle\Entity\Points
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set version
     *
     * @param \PoiBundle\Entity\Versions $version
     *
     * @return Changes
     */
    public function setVersion(\PoiBundle\Entity\Versions $version = null)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return \PoiBundle\Entity\Versions
     */
    public function getVersion()
    {
        return $this->version;
    }
}

