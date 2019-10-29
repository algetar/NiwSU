<?php
namespace app\tgtools\speller;

use app\tgtools\datasources\TitlesObject;
use app\tgtools\speller\NumberObjects;

/**
 * Description of PrimeNumberTitles
 * Наименования базовых чисел.
 * 
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class PrimeNumberTitles extends NumberObjects {
    /**
     * В конструкторе инициализируем источники
     */
    public function __construct() {
        parent::__construct();
        $this->_rows = new TitlesObject('number-names');
    }
}
