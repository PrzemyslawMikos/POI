<?php

namespace PoiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * Users
 *
 * @ORM\Table(name="users", indexes={@ORM\Index(name="FK_Users_Permissions_idx", columns={"Permission_Id"})}, uniqueConstraints={@ORM\UniqueConstraint(name="Username_UNIQUE", columns={"Username"}), @ORM\UniqueConstraint(name="Nickname_UNIQUE", columns={"Nickname"})})
 * @ORM\Entity
 */
class Users implements \Serializable, UserInterface
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
     * @ORM\Column(name="Nickname", type="string", length=30, nullable=false)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="Username", type="string", length=30, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreationDate", type="datetime", nullable=false)
     */
    private $creationdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="FirstLogin", type="boolean", nullable=false)
     */
    private $firstlogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Unblocked", type="boolean", nullable=false)
     */
    private $unblocked;

    /**
     * @var \Permissions
     *
     * @ORM\ManyToOne(targetEntity="Permissions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Permission_Id", referencedColumnName="Id")
     * })
     */
    private $permission;

    /**
     * Return string value of User
     */
    public function __toString()
    {
        return $this->nickname;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return Users
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Users
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set creationdate
     *
     * @param \DateTime $creationdate
     * @return Users
     */
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    /**
     * Get creationdate
     *
     * @return \DateTime 
     */
    public function getCreationdate()
    {
        return $this->creationdate;
    }

    /**
     * Set firstlogin
     *
     * @param boolean $firstlogin
     * @return Users
     */
    public function setFirstlogin($firstlogin)
    {
        $this->firstlogin = $firstlogin;

        return $this;
    }

    /**
     * Get firstlogin
     *
     * @return boolean 
     */
    public function getFirstlogin()
    {
        return $this->firstlogin;
    }

    /**
     * Set unblocked
     *
     * @param boolean $unblocked
     * @return Users
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
     * @return \Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param \Permission $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
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
            $this->nickname,
            $this->email,
            $this->phone,
            $this->password,
            $this->creationdate,
            $this->firstlogin,
            $this->unblocked,
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
            $this->nickname,
            $this->email,
            $this->phone,
            $this->password,
            $this->creationdate,
            $this->firstlogin,
            $this->unblocked,
            ) = unserialize($serialized);
    }

    public function getRoles()
    {
        $permission = $this->getPermission();
        return array($permission->getName());
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
    }

}
