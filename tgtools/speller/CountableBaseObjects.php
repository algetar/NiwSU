<?php

namespace app\tgtools\speller;

/**
 * Description of CountableBaseObjects
 * Интерпретация записей TitlesObject в качесве базовых 
 * атрибутов объектов с поведением числительных
 * 
 * @property int|string $key ключ текущей записи key
 * @property int $titleId номер позиции в списке 'titles'
 * @property int $type значение опции 'type' текущей записи
 * @property string $title значение в позиции $titleId списка 'titles'
 * 
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class CountableBaseObjects extends \yii\base\Object {
    /**
     * Источник записей
     * @var \app\tgtools\datasources\TitlesObject
     */
    protected $_rows = null;
    
    /**
     * Ид текущей записи
     * @return string
     */
    public function getKey(){
        return $this->_rows->key;
    }
    /**
     * Назначачет ид текущей записи
     * @param string $value
     * @return $this
     */
    public function setKey($value){
        $this->_rows->key = $value;
        return $this;
    }
    
    /**
     * номер позиции в списке 'titles'
     * @return int 
     */
    public function getTitleId(){
        return $this->_rows->titleId;
    }
    /**
     * Назначачет номер позиции в списке 'titles'
     * @param int $value
     * @return $this
     */
    public function setTitleId($value){
        $this->_rows->titleId = $value;
        return $this;
    }
    /**
     * значение опции 'type' текущей записи
     * @return int
     */
    public function getType(){
        return $this->_rows->type;
    }
    /**
     * значение в позиции $titleId списка 'titles' в строке $key
     * @return string
     */
    public function getTitle(){
        return $this->_rows->title;
    }
}
