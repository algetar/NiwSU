<?php
namespace app\tgtools\common;

/**
 * Description of IntType
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class IntType implements VartypeInterface {
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
        return (int) $value;
    }
    
    /**
     * Тип переманной совпадает с текущим
     * @param mixed $type
     * @return boolean
     */
    public function thisType($type){
        return in_array(strtolower($type), ['int', 'integer']);
    }
}

