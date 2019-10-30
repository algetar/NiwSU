<?php
namespace app\components\tgtools\speller;

/**
 * Description of CountableBehaviourObjects
 * Объекты с поведением числительных
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class CountableBehaviourObjects extends CountableObjects {
    /**
     * в нулевой разрыд числа добавляет пустую строку
     * Добавляет пестую 
     */
    public function emptyRow(){
        $this->addRow(TGS::ROW_EMPTY);
    }
    /**
     * в нулевой разрыд числа добавляет строку со словом 'целых',
     * устанавливает род исчисляемого $gender и делает его текущим
     * @param type $gender род исчисляемого
     */
    public function intRow($gender){
        $row = TGS::ROW_INT;
        $row['type'] = $gender;
        $this->addRow($row);
    }
    /**
     * в нулевой разрыд числа добавляет строку $row
     * @param array $row
     */
    public function addRow($row){
        $this->_rows->unshiftRow($row);
        //добавленная запись становится текущей
        $this->key = 0;
    }
    
    
}
