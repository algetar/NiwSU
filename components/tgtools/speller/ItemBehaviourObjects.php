<?php

namespace app\components\tgtools\speller;

/**
 * Description of ItemBehaviourObjects
 * Объекты с поведением исчисляемых
 * 
 * @property array $row текущая строка
 * 
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class ItemBehaviourObjects extends CountableObjects {
    /**
     * Возвращает текущую/указаную строку
     * если указан $key строка с этим ключом становится текущей
     * @param mixed $key ключ записи
     * @return array
     */
    public function getRow($key = null){
        return $this->_rows->setKey($key)->row;
    }
}
