<?php

/*
 * Copyright (c) 2019 gtatarnikov.
 * Contributors:
 *    gtatarnikov - initial API and implementation and/or initial documentation
 */

namespace app\tgtools\debug;
use app\modules\supports\models\DebugLog;

/**
 * Description of UserDebugLog
 *
 * @author gtatarnikov
 */
class UserDebugLog {
    
    /**
     * Добавлять в лог пустые параметры объекта
     * @var boolean
     */
    public static $printEmptyVars = false;
    /**
     * Добавлять в лог список процедур объекта
     * @var boolean
     */
    public static $printMethods = false;

    /**
     * Сохраняет указаное значение в лог
     * @param string $title наименование значения
     * @param mixed $value значение
     * @param type $printEmptyVars для объектов указывать все свойства
     * @param type $printMethods для объектов указывать все методы
     */
    public static function addLog($title, $value, $printEmptyVars= false, $printMethods = false){
        static::$printEmptyVars = $printEmptyVars;
        static::$printMethods = $printMethods;
        static::addValue(0, $title, $value);
    }
    
    /**C:\xampp\htdocs\sporcoza\runtime\debug\5cee07971f872.data
     * Добавляет строку лога в бд
     * @param int $nesting
     * @param string $title
     * @param mixed $var
     */
    protected static function addValue($nesting, $title, $var){
        if (is_array($var)){
            if ($var){
                $id = static::saveItem($nesting, 'array', $title, '{list↓}');
                static::saveArray($id, $var);
            } else {
                static::saveItem($nesting, 'array', $title, '[]');
            }
        } elseif (($var instanceof \app\modules\purso\models\Timetables) || 
                ($var instanceof \app\modules\purso\models\TimetablesActions)){
            $id = static::saveItem($nesting, 'object', $title, $type = get_class($var));
            static::saveTimetable($id, $type, $var);
        } elseif ($var instanceof Selectors){
            $id = static::saveItem($nesting, 'object', $title, 'Selectors');
            static::saveSelector($id, $var);
        } elseif (is_object($var)){
            $id = static::saveItem($nesting, 'object', $title, get_class($var));
            static::saveObject($id, $var);
        } elseif (is_scalar($var)) {
            static::saveItem($nesting, gettype($var), $title, $var);
        } elseif (is_null($var)) {
            static::saveItem($nesting, 'null', $title, '');
        } else {
            static::saveItem($nesting, 'unknown type', $title, $var);
        }
    }
    
    /**
     * Сохраняет элементы массива
     * @param int $nesting
     * @param array $array
     */
    protected static function saveArray($nesting, $array){
        foreach ($array as $key => $item) {
            static::addValue($nesting, $key, $item);
        }
    }

    /**
     * Сохраняет объект 
     * @param int $nesting
     * @param object $object
     */
    protected static function saveObject($nesting, $object) {
        $vars = get_class_vars(get_class($object));
        foreach ($vars as $key => $var){
            if (static::$printEmptyVars or $var){
                static::addValue($nesting, $key, $var);
            }
        }
        if (static::$printMethods){
            $methods = get_class_methods(get_class($object));
            foreach ($methods as $method){
                static::saveItem($nesting, 'method', $method);
            }
        }
    }
    
    /**
     * Добавляет в лог период расписания
     * @param int $nesting
     * @param \app\modules\purso\models\Timetables|TimetablesActions $timetable
     */
    protected static function saveTimetable($nesting, $timetable) {
        static::saveObjectItem($nesting, 'ResourceIdx', $timetable);
        static::saveObjectItem($nesting, 'FullTimePeriod', $timetable);
        static::saveObjectItem($nesting, 'DatePeriod', $timetable);
        static::saveObjectItem($nesting, 'OrderID', $timetable);
    }
    /**
     * Добавляет в лог селектор расписания
     * @param int $nesting
     * @param \app\modules\supports\models\Selectors $selector
     */
    protected static function saveSelector($nesting, Selectors $selector){
        static::saveObjectItem($nesting, 'ResourceIdx', $selector);
        static::saveObjectItem($nesting, 'FullTimePeriod', $selector);
        static::saveObjectItem($nesting, 'DatePeriod', $selector);
        static::saveObjectItem($nesting, 'OrderID', $selector);
        static::saveObjectItem($nesting, 'TeamID', $selector);
        static::saveObjectItem($nesting, 'TeamTitle', $selector);
        static::saveObjectItem($nesting, 'OrderTitle', $selector);
        static::saveObjectItem($nesting, 'Duration', $selector);
    }
    
    /**
     * Сохраняет атрибут объекта в логах
     * @param mixed $nesting
     * @param string $attr
     * @param mixed $object
     */
    protected static function saveObjectItem($nesting, $attr, $object) {
        static::saveItem($nesting, gettype($object->$attr), $attr, $object->$attr);
    }

    /**
     * Сохраняет запись в БД
     * @param int $nesting
     * @param string $type
     * @param string $title
     * @param mixed $var
     */
    protected static function saveItem($nesting, $type, $title, $var = '') {
        return DebugLog::saveItem($nesting, $type, $title, $var);
    }
    
    public static function getLog(){
        $log = new DebugLog();
        return $log->getLog();
    }
   
    
}
