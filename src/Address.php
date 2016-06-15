<?php

namespace App;

class Address
{

    const TYPE_MAIN = 1;
    const TYPE_POST = 2;
    const TYPE_OTHER = 3;
    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    private $street;
    private $zip;
    private $city;
    private $type;
    private $status;

    public function __construct($street = null, $zip = null, $city = null, $type = self::TYPE_MAIN)
    {
        $this->street = $street;
        $this->zip = $zip;
        $this->city = $city;
        $this->type = $type;
        $this->status = self::STATUS_ACTIVE;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
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
