<?php

namespace PoiBundle\Entity\Application;

use PoiBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\Annotation\Type;

class UsersAndroid
{
    private $id;

    private $permissionid;

    private $nickname;

    private $email;

    private $phone;

    private $username;

    private $password;

    /**
     * @Type("DateTime<'Y-m-d'>")
     */
    private $creationdate;

    private $firstlogin;

    private $unblocked;


    public function __construct()
    {
    }

    public static function constructUser(Users $user)
    {
        $instance = new self();
        $instance->id = $user->getId();
        $instance->permissionid = $user->getPermission()->getId();
        $instance->nickname = $user->getNickname();
        $instance->email = $user->getEmail();
        $instance->phone = $user->getPhone();
        $instance->username = $user->getUsername();
        $instance->creationdate = $user->getCreationdate();
        $instance->firstlogin= $user->getFirstlogin();
        $instance->unblocked = $user->getUnblocked();
        return $instance;
    }

    public static function constructRequest(Request $request)
    {
        $instance = new self();
        $instance->id = null;
        // Nowo stworzony użytkownik ma zwykłe uprawnienia
        $instance->permissionid = 1;
        $instance->nickname = $request->get('nickname');
        $instance->email = $request->get('email');
        $instance->phone = $request->get('phone');
        $instance->username = $request->get('username');
        $instance->password = $request->get('password');
        return $instance;
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPermissionid()
    {
        return $this->permissionid;
    }

    public function setPermissionid($permissionid)
    {
        $this->permissionid = $permissionid;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getCreationdate()
    {
        return $this->creationdate;
    }

    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;
    }

    public function getFirstlogin()
    {
        return $this->firstlogin;
    }

    public function setFirstlogin($firstlogin)
    {
        $this->firstlogin = $firstlogin;
    }

    public function getUnblocked()
    {
        return $this->unblocked;
    }

    public function setUnblocked($unblocked)
    {
        $this->unblocked = $unblocked;
    }

}