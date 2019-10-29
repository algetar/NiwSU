<?php

namespace app\tgtools\speller;
use app\tgtools\datasources\TitlesObject;

/**
 * Description of DecimalTitles
 * Наименования разрядов дробной части.
 * Ведут себя как исчисляемые.
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class DecimalTitles extends ItemBehaviourObjects {
    /**
     * В конструкторе инициализируем источники
     */
    public function __construct() {
        parent::__construct();
        $this->_rows = new TitlesObject('decimal-names');
    }
}
