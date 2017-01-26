<?php

namespace PoiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ratings
 *
 * @ORM\Table(name="ratings", indexes={@ORM\Index(name="FK_Users_Points_idx", columns={"User_Id"}), @ORM\Index(name="FK_Ratings_Points_idx", columns={"Point_Id"})})
 * @ORM\Entity
 */
class Ratings
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
     * @var integer
     *
     * @ORM\Column(name="Rating", type="integer", nullable=false)
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="RateDate", type="datetime", nullable=false)
     */
    private $ratedate;

    /**
     * @var \Points
     *
     * @ORM\ManyToOne(targetEntity="Points", inversedBy="ratings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Point_Id", referencedColumnName="Id")
     * })
     */
    private $point;

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
     * Set rating
     *
     * @param integer $rating
     * @return Ratings
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set ratedate
     *
     * @param \DateTime $ratedate
     * @return Ratings
     */
    public function setRatedate($ratedate)
    {
        $this->ratedate = $ratedate;

        return $this;
    }

    /**
     * Get ratedate
     *
     * @return \DateTime 
     */
    public function getRatedate()
    {
        return $this->ratedate;
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
     * @return Ratings
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
     * Set user
     *
     * @param \PoiBundle\Entity\Users $user
     * @return Ratings
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
}
