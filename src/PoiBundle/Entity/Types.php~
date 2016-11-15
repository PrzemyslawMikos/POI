<?php

namespace PoiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Types
 *
 * @ORM\Table(name="types", indexes={@ORM\Index(name="FK_Types_Administrators_idx", columns={"Creator_Id"})})
 * @ORM\Entity
 */
class Types
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
     * @ORM\Column(name="Name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=200, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="AddedDate", type="datetime", nullable=false)
     */
    private $addeddate;

    /**
     * @var string
     * @Assert\File(mimeTypes={"image/jpg", "image/jpeg", "image/png"})
     * @ORM\Column(name="Image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="MimeType", type="string", length=50, nullable=false)
     */
    private $mimetype;

    /**
     * @var \Administrators
     *
     * @ORM\ManyToOne(targetEntity="Administrators")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Creator_Id", referencedColumnName="Id")
     * })
     */
    private $creator;

    /**
     * Return string value of Type
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Types
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
     * @return Types
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
     * Set addeddate
     *
     * @param \DateTime $addeddate
     * @return Types
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
     * Set image
     *
     * @param string $image
     * @return Types
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set mimetype
     *
     * @param string $mimetype
     * @return Types
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creator
     *
     * @param \PoiBundle\Entity\Administrators $creator
     * @return Types
     */
    public function setCreator(\PoiBundle\Entity\Administrators $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \PoiBundle\Entity\Administrators 
     */
    public function getCreator()
    {
        return $this->creator;
    }
}
