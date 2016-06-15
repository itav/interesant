<?php

namespace App;

use Itav\Component\Serializer\Serializer;

class InteresantRepo
{

    private $file = __DIR__ . '/storage/interesant.json';
    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer();
    }

    /**
     * 
     * @param \App\Interesant $interesant
     * @return string | bool
     */
    public function save(Interesant $interesant)
    {
        $rows = json_decode(file_get_contents($this->file), true);
        $data = $this->serializer->normalize($interesant);
        $foundKey = false;
        foreach($rows as $key => $item){
            if($item['id'] == $interesant->getId()){
                $foundKey = $key;
                break;
            }
        }
        if(false !== $foundKey){
            unset($rows[$foundKey]);
        }
        $rows[] = $data;
        
        file_put_contents($this->file, json_encode($rows));
        return $interesant->getId();        
    }

    /**
     * 
     * @param int $id
     * @return \App\Interesant
     */
    public function find($id)
    {
        $rows = json_decode(file_get_contents($this->file), true);
        foreach($rows as $item){
            if($item['id'] == $id){
                return $this->serializer->unserialize($item, Interesant::class);
            }
        } 
        return null;
    }
    /**
     * 
     * @return Interesant[]
     */
    public function findAll()
    {
        $rows = json_decode(file_get_contents($this->file), true);
        $results = [];
        foreach($rows as $item){
            $results[] =  $this->serializer->unserialize($item, Interesant::class);
        } 
        return $results;
    }   
}