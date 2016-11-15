<?php

namespace PoiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Points
 *
 * @ORM\Table(name="points", indexes={@ORM\Index(name="FK_Points_Users_idx", columns={"User_Id"}), @ORM\Index(name="FK_Points_Types_idx", columns={"Type_Id"}), @ORM\Index(name="FK_Points_Administrators_idx", columns={"Accept_Id"})})
 * @ORM\Entity
 */
class Points implements \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Longitude", type="decimal", precision=9, scale=6, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="Latitude", type="decimal", precision=9, scale=6, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=300, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="Picture", type="blob", length=16777215, nullable=false)
     */
    private $picture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="AddedDate", type="datetime", nullable=false)
     */
    private $addeddate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Accepted", type="boolean", nullable=false)
     */
    private $accepted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Unblocked", type="boolean", nullable=false)
     */
    private $unblocked;

    /**
     * @var \Administrators
     *
     * @ORM\ManyToOne(targetEntity="Administrators")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Accept_Id", referencedColumnName="Id")
     * })
     */
    private $accept;

    /**
     * @var \Types
     *
     * @ORM\ManyToOne(targetEntity="Types")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Type_Id", referencedColumnName="Id")
     * })
     */
    private $type;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_Id", referencedColumnName="Id")
     * })
     */
    private $user;



    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Points
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Points
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Points
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Points
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Points
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set addeddate
     *
     * @param \DateTime $addeddate
     * @return Points
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
     * Set accepted
     *
     * @param boolean $accepted
     * @return Points
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * Get accepted
     *
     * @return boolean 
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Set unblocked
     *
     * @param boolean $unblocked
     * @return Points
     */
    public function setUnblocked($unblocked)
    {
        $this->unblocked = $unblocked;

        return $this;
    }

    /**
     * Get unblocked
     *
     * @return boolean 
     */
    public function getUnblocked()
    {
        return $this->unblocked;
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
     * Set accept
     *
     * @param \PoiBundle\Entity\Administrators $accept
     * @return Points
     */
    public function setAccept(\PoiBundle\Entity\Administrators $accept = null)
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Get accept
     *
     * @return \PoiBundle\Entity\Administrators 
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Set type
     *
     * @param \PoiBundle\Entity\Types $type
     * @return Points
     */
    public function setType(\PoiBundle\Entity\Types $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \PoiBundle\Entity\Types 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \PoiBundle\Entity\Users $user
     * @return Points
     */
    public function setUser(\PoiBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PoiBundle\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->user,
            $this->type,
            $this->longitude,
            $this->latitude,
            $this->name,
            $this->description,
            $this->picture,
            $this->addeddate,
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->user,
            $this->type,
            $this->longitude,
            $this->latitude,
            $this->name,
            $this->description,
            $this->picture,
            $this->addeddate,
            ) = unserialize($serialized);
    }
}
