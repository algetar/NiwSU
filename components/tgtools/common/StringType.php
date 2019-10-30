<?php

namespace app\components\tgtools\common;

/**
 * Description of StringType
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class StringType implements VartypeInterface {
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
        return (string) $value;
    }
    
    /**
     * Тип переманной совпадает с текущим
     * @param mixed $type
     * @return boolean
     */
    public function thisType($type){
        return strtolower($type) === 'string';
    }
}


