<?php

namespace app\tgtools\speller;

/**
 * Description of CountedObjects
 * Общий модуль для всех числительных.
 * К числительным относим:
 *  - наименования разрядов целого части числа, ведут себя как числительные
 *  - наименования исчисляемых
 *  - наименования разрядов дробной части числа, ведут себя как исчисляемые
 * Содержит список записей $_rows в TitlesObject.
 * Свойства:
 * 
 * @property int $declension склонение текущего числительного
 * @property-read int $gender род текущего числительного
 * @property-read array $row текущая запись 
 *  в виде массива ['titles' => ['title1', 'title2', 'title3'], 'type' => <int> ]
 * 
 * Методы:
 * string spell(string $key, int $declension) 
 * возвращает слово в записи $key значение из массива 'titles' 
 * в позиции $declention
 *  
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class CountableObjects extends CountableBaseObjects {
    
    /**
     * Текущее значение склонения числительного,
     * оно же позиция в списке склонений $titles.
     * Значение $this->_rows->key должно быть задано!
     * @return int
     */
    public function getDeclension(){
        return $this->titleId;
    }
    /**
     * Назначает текущее значение склоенния числительного
     * Значение $this->_rows->key должно быть задано!
     * @param int $value
     * @return $this
     */
    public function setDeclension($value) {
        $this->titleId = $value;
        return $this;
    }
    /**
     * Род текущего числительного, определяется из 'type'.
     * Значение $this->_rows->key должно быть задано!
     * @return int
     */
    public function getGender(){
        return $this->type;
    }
    
    /**
     * Призносит числительное согласно номера записи $key
     * и склонения числа, перед числительным, $declension.
     * @param int|string $key
     * @param int $declension
     * @return string
     */
    public function spell($key = null, $declension = null){
        return $this->setKey($key)->setDeclension($declension)->getTitle();
    }
}
