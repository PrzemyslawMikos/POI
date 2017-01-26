<?php

namespace PoiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PoiBundle\Entity\Application\PointsAndroid;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Points
 *
 * @ORM\Table(name="points", indexes={@ORM\Index(name="FK_Points_Users_idx", columns={"User_Id"}), @ORM\Index(name="FK_Points_Types_idx", columns={"Type_Id"}), @ORM\Index(name="FK_Points_Administrators_idx", columns={"Accept_Id"})})
 * @ORM\Entity(repositoryClass="PoiBundle\Repository\PointsRepository")
 */
class Points
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
     * @var double
     *
     * @ORM\Column(name="Longitude", type="decimal", precision=9, scale=6, nullable=false)
     */
    private $longitude;

    /**
     * @var double
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
     * @ORM\Column(name="Locality", type="string", length=90, nullable=false)
     */
    private $locality;

    /**
     * @var string
     *
     * @ORM\Column(name="Rating", type="decimal", precision=2, scale=1, nullable=true)
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=300, nullable=false)
     */
    private $description;

    /**
     * @var string
     * @Assert\File(mimeTypes={"image/jpg", "image/jpeg", "image/png"})
     * @ORM\Column(name="Picture", type="string", length=255, nullable=false)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="MimeType", type="string", length=50, nullable=false)
     */
    private $mimetype;

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
     * @var \Ratings
     *
     * @ORM\OneToMany(targetEntity="Ratings", mappedBy="point")
     */
    private $ratings;

    public function __construct()
    {
    }

    public static function constructPointAndroid(PointsAndroid $pointAndroid)
    {
        $instance = new self();
        $instance->longitude = $pointAndroid->getLongitude();
        $instance->latitude = $pointAndroid->getLatitude();
        $instance->name = $pointAndroid->getName();
        $instance->locality = $pointAndroid->getLocality();
        $instance->description = $pointAndroid->getDescription();
        $instance->picture = $pointAndroid->getPicture();
        $instance->mimetype = $pointAndroid->getMimetype();
        $instance->addeddate = new \DateTime();
        $instance->accepted = false;
        $instance->unblocked = true;
        return $instance;
    }

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
     * Set rating
     *
     * @param string $rating
     * @return Points
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
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
     * Set locality
     *
     * @param string $locality
     * @return Points
     */
    public function setLocality($locality)
    {

        $this->locality = $locality;
        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
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
     * Set mimetype
     *
     * @param string $mimetype
     * @return Points
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;

        return $this;
    }

    /**
     * Get mimetype
     *
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
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
     * @return Ratings
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param Ratings $ratings
     */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;
    }



}