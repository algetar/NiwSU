<?php

namespace app\components\tgtools\datasources;
use app\components\tgtools\common\TGC;
use yii\base\UnknownPropertyException;
use yii\base\InvalidCallException;
use yii\base\InvalidValueException;

/**
 * Description of TableObject
 * Чтение данных, хранищихся в формате таблицы
 * [
 *      'id' => ['atribute1' => 'value1', 'atribute2' => 'value2', ...],
 *      ...
 *  ]
 * 'id' (int|string) ид записи
 *  'atribute' => 'value' имя и значение атрибута
 * 
 * @property array $row строка ткущей записи
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class TableObject extends SourceObject {
    /**
     * В некоторых записях, некоторые атрибуты могут отсутсвовать.
     * Для таких записей можно задать дефолтные значения в виде
     *  <name> => [ 'value' = <mixed>, 'type' => <string>, 'access' => <int>, 'null' => <bool>, 'config' => <array>, 'params' => <array>],
     * <name> имя атрибута
     * 'type' тип атрибута (int, bool, double, array, object)
     * 'access' опционально, чтение/запись = 1 (по умолчанию) только Get, 2 только Set, 3 Get/Set
     * 'null' опционально, может принимать значения null true|false|i(gnore)
     *      'null' = 'i' игнорировать метод set
     * 'config' параметры создания объекта
     * 'params' интерпретируется как параметры вызова конструктора класса объекта
     * @var array
     */
    public $defaults = [];
    /**
     * Ид текущей записи
     * @var int|string
     */
    protected $_id;
    
    /**
     * Сохраняем в $id значение ключа первой записи загруженного контента
     * 
     * @param array $config
     */
    public function __construct($config) {
        parent::__construct($config);
        $this->_id = array_keys($this->source)[0];        
    }

    /**
     * @param string $name
     * @return mixed
     * @throws UnknownPropertyException
     */
    public function __get($name) {
        if (parent::canGetProperty($name)){
            return parent::__get($name);
        } elseif( $name === 'row') {
            return $this->source[$this->_id];
        } elseif( isset($this->row[$name]) || isset($this->defaults[$name]) ) {
            return $this->getValue($name);
        } else {
            throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }
    
    /**
     * @param string $name
     * @return mixed
     * @throws UnknownPropertyException
     */
    public function __set($name, $value) {
        if (parent::canSetProperty($name)){
            parent::__set($name, $value);
        } elseif( isset($this->row[$name]) || isset($this->defaults[$name]) ) {
            $this->setValue($name, $value);
        } else {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }
    
    /**
     * Тип доступа атрибута
     * @param string $name имя атрибута
     * @return int тип доступа
     */
    protected function getAccess($name){
        return isset($this->defaults[$name]['access']) ? $this->defaults[$name]['access'] : 1;
    }
    
    /**
     * Атрибут может принимать значение Null
     * @param string $name
     * @return boolean
     */
    protected function canSetNull($name){
        if (isset($this->defaults[$name]['null'])){
            return $this->defaults[$name]['null'];
        } else {
            return true;
        }
    }
    
    /**
     * Значение атрибута в текущей строке.
     * При его отсутсвии, значение по умолчанию.
     * Для массива проверяется полнота заполнения, если 
     * в дефолтных параметрах установлен атрибут 'count'.
     * @param string $name имя атрибута
     * @return mixed
     * @throws InvalidCallException
     */
    protected function getValue($name){
        if ($this->getAccess($name) == 2){
            throw new InvalidCallException('Getting write-only attribute: ' . get_class($this) . '::' . $name);
        }
        $type = isset($this->defaults[$name]['type']) ? $this->defaults[$name]['type'] : 'string';
        if (!isset($this->row[$name])){
            $this->_setValue($name, isset($this->defaults[$name]['value'])? $this->defaults[$name]['value'] : '');
        }
        $value = $this->row[$name];
        if ($type === 'array') {
            //возможно секция с массивом состоит из одного
            //элемента и задано в виде одного значения.
            if (!is_array($value = TGC::stringToVar($value, $type))){
                //делаем массив
                $value = [$value];
            }
            return $value;
        } else {
            return TGC::stringToVar($value, $type);
        }
    }
    
    /**
     * Проверяет и сохраняет значение атрибута
     * @param string $name имя атрибута
     * @param mixed $value значение
     * @return string
     * @throws InvalidCallException
     * @throws InvalidValueException
     */
    protected function setValue($name, $value){
        if ($this->getAccess($name) == 1){
            throw new InvalidCallException('Setting read-only attribute: ' . get_class($this) . '::' . $name);
        }
        if ($value === null){
            if(($canNull = $this->canSetNull($name)) === 'i'){
                //игнорировать значение null
                return;
            } elseif( !$canNull){
                //запрет null
                throw new InvalidValueException('Attribute cannot be set to Null: ' . get_class($this) . '::' . $name);
            }
        }
        $this->_setValue($name, $value);
    }
    
    /**
     * сохраняет значение атрибута
     * @param string $name имя атрибута
     * @param mixed $value значение
     * @return string
     */
    protected function _setValue($name, $value){
        $type = isset($this->defaults[$name]['type']) ? $this->defaults[$name]['type'] : 'string';
        $this->source[$this->_id][$name] = TGC::varToString($value, $type);
    }

    /**
     * Добавляет строку в начало списка объектов
     * @param array $row
     */
    public function unshiftRow($row){
        array_unshift($this->source, $row);
    }
}
