<?php

namespace App;

use Itav\Component\Serializer\Serializer;

class InteresantRepo
{

    private $file = __DIR__ . '/storage/interesant.csv';
    private $fileOld = __DIR__ . '/storage/interesant.csv.old';
    private $fileTemp = __DIR__ . '/storage/interesant.csv.temp';

    public function save(Interesant $interesant)
    {
        if (!file_exists($this->file)) {
            return false;
        }
        $serializer = new Serializer();
        $id = $interesant->getId();
        $rows = [];
        file_put_contents($this->fileTemp, '');
        $handle = fopen($this->file, 'r+');
        $found = false;
        while(($line = fgets($handle, 4096)) !== false){
            $item = new Interesant();
            $itemData = $this->unescape(str_getcsv($line)[0]);
            $item = $serializer->unserialize($itemData, Interesant::class, $item);
            if ($item->getId() !== $id) {
                file_put_contents($this->fileTemp, $line, FILE_APPEND);
                continue;
            }
            $found = true;
        }
        fclose($handle);
        $data = $this->escape(json_encode($serializer->normalize($interesant)));
        if(!$found){
            if(($result = file_put_contents($this->file, $data, FILE_APPEND)) === false){
                return false;
            }
            return $id;
        }
        if(($result = file_put_contents($this->fileTemp, $data, FILE_APPEND)) === false){
            return false;
        }
        rename($this->file, $this->fileOld);
        rename($this->fileTemp, $this->file);        
        return $id;
    }

    /**
     * 
     * @param int $id
     * @return \App\Interesant
     */
    public function find($id)
    {
        $serializer = new Serializer();
        $handle = fopen($this->file, 'r+');
        while(($line = fgets($handle, 4096)) !== false){
            $item = new Interesant();
            $itemData = $this->unescape(str_getcsv($line)[0]);
            $item = $serializer->unserialize($itemData, Interesant::class, $item);
            if ($item->getId() === $id) {
                fclose($handle);
                return $item;
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
        $serializer = new Serializer();
        $handle = fopen($this->file, 'r+');
        $items = [];
        while(($line = fgets($handle, 4096)) !== false){
            $item = new Interesant();
            $itemData = $this->unescape(str_getcsv($line)[0]);
            $item = $serializer->unserialize($itemData, Interesant::class, $item);
            $items[] = $item;
        }
        fclose($handle);
        return $items;
    }
    
    private function escape($line)
    {
        return '"' . str_replace('"', '\"', $line) . '"' . PHP_EOL;

    }
    private function unescape($line)
    {
        return str_replace('\"', '"', $line);

    }    
}
