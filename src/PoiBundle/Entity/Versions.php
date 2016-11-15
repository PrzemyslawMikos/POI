<?php

namespace PoiBundle\Entity;

/**
 * Versions
 */
class Versions
{
    /**
     * @var integer
     */
    private $members;

    /**
     * @var \DateTime
     */
    private $addeddate;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set members
     *
     * @param integer $members
     *
     * @return Versions
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members
     *
     * @return integer
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set addeddate
     *
     * @param \DateTime $addeddate
     *
     * @return Versions
     */
    public function setAddeddate($addeddate)
    {
        $this->addeddate = $addeddate;

        return $this;
    }

    /**
     * Get addeddate
     *
     * @return \DateTime
     */
    public function getAddeddate()
    {
        return $this->addeddate;
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

