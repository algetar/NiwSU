<?php

namespace app\components\tgtools\common;

/**
 * Description of BoolType
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class BoolType implements VartypeInterface {
    /**
     * Преобразует переменную текущего типа в строку 
     * @param mixed $value
     * @return string
     */
    public function toString($value){
        return ($value) ? 'true': 'false';
    }
    /**
     * Преобразует строку в переменную текущего типа
     * @param string $value
     * @return mixed
     */
    public function toVar($value){
        if (is_string($value)){
            return (strtolower($value) === 'false') ? false : (boolean) $value;
        } else {
            return (boolean) $value;
        }
    }
    
    /**
     * Тип переманной совпадает с текущим
     * @param mixed $type
     * @return boolean
     */
    public function thisType($type){
        return in_array(strtolower($type), ['bool', 'boolean']);
    }
}
