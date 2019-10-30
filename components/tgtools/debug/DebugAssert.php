<?php

namespace app\components\tgtools\debug;

/**
 * Description of debug
 *
 * @author gtatarnikov
 */
class DebugAssert {
    /*
     *    (начиная с PHP 4, PHP 5)
     *   "integer"
     *   "double" (по историческим соображениям "double" возвращается в случае с float, а не просто "float")
     *   "string"
     *   "array"
     *   "object"
     *   "resource" (начиная с PHP 4, PHP 5)
     *   "NULL" (начиная с PHP 4, PHP 5)
     *   "unknown type"
     * 
     */
    
    public static function toString($value){
        if ($value === ''){
            return "{empty}";
        }
        $type = gettype($value);
        switch ($type) {
            case "boolean":
                return static::cbool($value);
            case "integer":
                return static::cint($value);
            case "double":
            case "float":
                return static::cfloat($value);
            case "string":
                return $value;
            case "array":
                return static::carray($value);
            default:
                stop;
                return $type;
        }
    }
    
    public static function assert($var){
        if (gettype($var) == "array"){
            static::assert(static::carray($var));
        } else {
            stop;
        }
    }
    
    public static function carray($array){
        $string = '';
        foreach ($array as $index => $item){
            if ($string){
                $string .= ";array[$index]=".static::toString($item);
            } else {
                $string = "array[$index]=".static::toString($item);
            }
        }
        return $string;
    }

    public static function cbool($value){
        if($value){
            return 'true';
        } else {
            return 'false';
        }
    }
    public static function cint($value){
        return (string) $value;
    }
    public static function cfloat($value){
        return sprintf('%f', $value);
    }
}
