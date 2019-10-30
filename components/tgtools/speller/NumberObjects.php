<?php

namespace app\components\tgtools\speller;

/**
 * Description of NumberObjects
 * Базовый класс для объектов с поведением чисел.
 * Свойства:
 * @property-read int $declension склонение текущего числа, определяется из 'type'
 * @property int $gender род текущего исчисляемого
 *              определяет текущую позицию в 'titles' назначается внешними модулями 
 * Методы:
 * string spell(int $key, int $gender) возвращает слово в записи $key
 *  значение из массива 'titles' в позиции $gender
 *  
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class NumberObjects extends CountableBaseObjects {
    /**
     * Род текущего исчисляемого
     * Значение $this->_rows->key должно быть задано!
     * @return int
     */
    public function getGender(){
        return $this->titleId;
    }
    /**
     * @param int $value
     * @return $this
     */
    public function setGender($value) {
        $this->titleId = $value;
        return $this;
    }
    /**
     * @return int
     */
    public function getDeclension(){
        return $this->type;
    }
    /**
     * Наименование числа в строке, соответсвующей $key, 
     * и рода исчисляемого $gender.
     * @param int|string $key
     * @param int $gender
     * @return string
     */
    public function spell($key = null, $gender = null){
        if (is_string($key)){
            $key = (int) $key;
        }
        return $this->setKey($key)->setGender($gender)->title;
    }
}
