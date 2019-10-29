<?php

namespace app\tgtools\common;
use yii\base\InvalidValueException;

/**
 * Description of ArrayType
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class ArrayType implements VartypeInterface {
    /**
     * Преобразует переменную текущего типа в строку 
     * @param mixed $value
     * @return string
     */
    public function toString($value){
        return json_encode($value);
    }
    /**
     * Преобразует строку в переменную текущего типа
     * @param string $value
     * @return mixed
     */
    public function toVar($value){
        if (is_array($value)){
            //уже массив
            return $value;
        }
        if (!is_string($value)){
            //нужна строка
            throw new InvalidValueException('Parameter must be a string: ArrayType.toVar($value)');
        }
        return json_decode($value, true);
    }
    
    /**
     * Тип переманной совпадает с текущим
     * @param mixed $type
     * @return boolean
     */
    public function thisType($type){
        return strtolower($type) === 'array';
    }
}

