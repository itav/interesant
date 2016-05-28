<?php

namespace App;

class Interesant
{

    const TYPE_COMPANY = 1;
    const TYPE_PRIVATE = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    private $id;
    private $name;
    private $type;
    private $firstName;
    private $lastName;
    /**
     *
     * @var Address[]
     */
    private $addresses;
    private $ten;
    private $status;

    public function __construct()
    {
        $this->id =  uniqid();
        $this->type = self::TYPE_PRIVATE;
        $this->status = self::STATUS_ACTIVE;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getAddresses()
    {
        return $this->addresses;
    }

    public function getTen()
    {
        return $this->ten;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    public function setTen($ten)
    {
        $this->ten = $ten;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

}
