<?php

namespace app\components\tgtools\common;

/**
 * Description of DoubleType
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class DoubleType implements VartypeInterface {
    /**
     * Преобразует переменную текущего типа в строку 
     * @param mixed $value
     * @return string
     */
    public function toString($value){
        return (string) $value;
    }
    /**
     * Преобразует строку в переменную текущего типа
     * @param string $value
     * @return mixed
     */
    public function toVar($value){
        return (double) $value;
    }
    
    /**
     * Тип переманной совпадает с текущим
     * @param mixed $type
     * @return boolean
     */
    public function thisType($type){
        return in_array(strtolower($type), ['double', 'dbl', 'float']);
    }
}

