<?php

namespace app\tgtools\common;

/**
 * Description of ObjectType
 * Решить проблему с параметрами.
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class ObjectType implements VartypeInterface {
    /**
     * Преобразует переменную текущего типа в строку 
     * @param mixed $value
     * @return string
     */
    public function toString($value){
        return serialize($value);
    }
    /**
     * Преобразует строку в переменную текущего типа
     * @param string $value
     * @return mixed
     */
    public function toVar($value){
        return unserialize($value);
        if (is_object($value)){
            //уже объект
            return $value;
        }
        if (!is_string($value)){
            //нужна строка
            throw new InvalidValueException('Parameter must be a string: ArrayType.toVar($value)');
        }
        if (( $pos = strpos($value, ',')) !== false){
            $config = json_decode(substr($value, $pos+1), true);
            $value = substr($value, 0, $pos);
        }
        $config['class'] = $value;
        return \Yii::createObject($config);
    }
    
    /**
     * Тип переманной совпадает с текущим
     * @param mixed $type
     * @return boolean
     */
    public function thisType($type){
        return strtolower($type) === 'object';
    }
}
