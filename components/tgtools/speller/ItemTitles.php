<?php

namespace app\components\tgtools\speller;
use app\components\tgtools\datasources\TitlesObject;

/**
 * Description of ItemTitles
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class ItemTitles extends ItemBehaviourObjects {
    /**
     * В конструкторе инициализируем источники
     */
    public function __construct() {
        parent::__construct();
        $this->_rows = new TitlesObject('item-names');
    }
    
}
