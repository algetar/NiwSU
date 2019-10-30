<?php

namespace app\components\tgtools\speller;

/**
 * Description of MoneyImplementer
 * Число с денежными исчисляемыми прописью
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class MoneyRepresentation  extends SpellRepresentation{
    /**
     * Целая часть числа
     * @var \app\components\tgtools\speller\Integer
     */
    public $integer;
    /**
     * Дробная часть числа
     * @var \app\components\tgtools\speller\Decimals
     */
    public $fractional;
    
    /**
     * Целое число прописью
     * @return string полученное значение
     */
    protected function spellInt(){
        //в денежной группе целое число интерпретируется как
        //вещественное с нулевой копеечной зоной
        return $this->spellDbl();
    }
    
    /**
     * вещественное число прописью
     * @return string полученное значение
     */
    protected function spellDbl(){
        if (strpos( ($number = (string) $this->number), '.') !== false){
            list($int, $frac) = explode('.', $number , 2);
        } else {
            list($int, $frac) = [$number, ''];
        }
        $this->item->push(2);
        $decimal = sprintf('%-02s', $frac);
        $this->integer = Integer::spell($int, $this->item, 0);
        $this->fractional = Integer::spell($decimal, $this->item, 1);
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

