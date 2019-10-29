<?php

namespace app\tgtools\common;

/**
 * Description of TGC(onversions)
 * Утилиты связанные с конвертацией типов
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class TGC {
    /**
     * Конвертирует переменную в строку
     * с использованием gettype
     * @param mixed $value
     * @return string
     */
    public static function toString($value){
        $type = gettype($value);
        return static::varToString($value, $type);
    }
    
    /**
     * Преобразует строку в указаный тип
     * @param string $value строка
     * @param string $type указаный тип
     * @return mixed полученное значение
     */
    public static function stringToVar($value, $type){
        return static::getTypeClass($type)->toVar($value);
    }
    
    /**
     * Преобразует переменную в строку
     * @param mixed $value строка
     * @param string $type тип переменной
     * @return string полученное значение
     */
    public static function varToString($value, $type){
        return static::getTypeClass($type)->toString($value);
    }

    /**
     * Тип переменной указаного типа
     * @param mixed $var переменная
     * @param string $type указаный тип
     * @return boolean true - тип совпадает
     */
    public static function varOfType($var, $type){
        return static::getTypeClass($type)->thisType($var);
    }

    /**
     * @param string $type
     * @return \app\tgtools\common\VartypeInterface
     */
    protected static function getTypeClass($type){
        switch ($type) {
            case "boolean": case "bool":
                return new BoolType();
            case "integer": case "int":    
                return new IntType();
            case "double": case "dbl": case "float":
                return new DoubleType();
            case "string":
                return new StringType();
            case "array":
                return new ArrayType();
            case "object":
                return new ObjectType();
            default:
                return "not scripted '$type'";
        }
    }
}
