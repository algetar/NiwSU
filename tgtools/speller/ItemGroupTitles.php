<?php

namespace app\tgtools\speller;
use app\tgtools\datasources\TitlesObject;

/**
 * Description of ItemGroupTitles
 * @property array $titles список исчисляемых текущей записи
 * @property int $type тип группы текущей записи
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class ItemGroupTitles extends \yii\base\Object {
    /**
     * Источник записей
     * @var \app\tgtools\datasources\TitlesObject
     */
    private $_rows = null;
    
    /**
     * В конструкторе инициализируем источники
     */
    public function __construct() {
        parent::__construct();
        $this->_rows = new TitlesObject('item-groups');
        $this->_rows->titleCount = 0;
    }
    /**
     * Имя текущего числительного
     * @return string
     */
    public function getKey(){
        return $this->_rows->key;
    }
    
    /**
     * Назаначает Имя текущего числительного
     * @param string $value
     * @return $this
     */
    public function setKey($value){
        $this->_rows->key = $value;
        return $this;
    }
    /**
     * Секция 'title' текущей записи
     * @return array
     */
    public function getTitles(){
        return $this->_rows->titles();
    }
    
    public function getType(){
        return $this->_rows->type;
    }
}