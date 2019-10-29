<?php

namespace app\tgtools\speller;

/**
 * Description of ItemImplementer
 * Представление числа прописью с применением
 * Применение форматов к числу прописью
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class ItemRepresentation extends SpellRepresentation{
    /**
     * Целая часть числа
     * @var \app\tgtools\speller\Integer
     */
    public $integer;
    /**
     * Дробная часть числа
     * @var \app\tgtools\speller\Decimals
     */
    public $fractional;
    
    /**
     * Целое число прописью
     * @return string полученное значение
     */
    protected function spellInt(){
        $this->item->push(0);
        $this->integer = Integer::spell($this->number, $this->item, 0);
        $this->_parts = $this->integer->parts;
        $this->_parts[4] = $this->integer->number;
        if ($this->format){
            $this->_result = vsprintf($this->format, $this->_parts);
        } else {
            $this->_result = $this->integer->spelt;
        }
        return $this->_result;
    }
    
    /**
     * вещественное число прописью
     * @return string полученное значение
     */
    protected function spellDbl(){
        list($int, $frac) = explode('.', (string) $this->number, 2);
        $this->item->push(strlen($frac));
        $this->integer = Integer::spell($int, $this->item, 0);
        $this->fractional = Decimals::spell($frac, $this->item, 1);
        $this->_parts = array_merge($this->integer->parts, $this->fractional->parts) ;
        if ($this->format){
            $this->_result = vsprintf($this->format, $this->_parts);
        } else {
            $this->_result = 
                    $this->integer->spelt
                        .' '.
                    $this->fractional->spelt;
        }
        return $this->_result;
        
    }
}
