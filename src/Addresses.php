<?php

namespace App;

class Addresses
{

    /**
     *
     * @var Address[]
     */
    private $addresses;

    public function getAddresses()
    {
        return $this->addresses;
    }

    public function setAddresses(array $addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

    public function addAddress($address)
    {
        $this->addresses[] = $address;
        return $this;
    }

    public function delAddress($index)
    {
        if (key_exists($index, $this->addresses)) {
            unset($this->addresses[$index]);
        }
        return $this;
    }

    public function reindexAddresses()
    {
        $this->addresses = array_values($this->addresses);
        return $this;
    }

}
