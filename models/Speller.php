<?php

namespace app\models;
use app\tgtools\speller\TGS;

/**
 * Description of Speller
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class Speller extends tblSpeller
{
    /**
     * Указаное число прописью
     * @return string
     */
    public function spell(){
        if ($this->Number){
            $this->Number = str_replace(',', '.', $this->Number);
        }
        $this->Spelt = TGS::spell($this->Number, $this->Item, $this->Format);
        return $this->save();
    }
}
