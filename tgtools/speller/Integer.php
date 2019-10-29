<?php
namespace app\tgtools\speller;
use app\tgtools\common\TGU;
use app\tgtools\speller\SumDigits;
use app\tgtools\speller\Items;

/**
 * Description of Integer
 * Произносим целое число
 * 
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class Integer extends \yii\base\Object {
    /**
     * Отлаждочная информация
     * @var array
     */
    public $debug = [];
    /**
     * Произнесенное число
     * @var string
     */
    public $spelt = '';
    /**
     * Произнесенное число, его исчисляемое и само число
     * @var array
     */
    public $parts = ['', '', 0];
    /**
     * Исходное значение
     * @var int
     */
    public $number;
    /**
     * Исчисляемые
     * @var \app\tgtools\speller\Items
     */
    public $item;
    /**
     * целая/дробная часть числа
     * @var int
     */
    public $part = 0;
    /**
     * Род исчисляемого
     * @var int
     */
    public $gender = 0;
    /**
     * склонение последней цифры числа
     * @var int
     */
    public $declension = 0;
    /**
     * Произносит тысячные разряды чисел
     * @var \app\tgtools\speller\SumDigits
     */
    private $_sd;
    /**
     * Произносит наименования тысячных разрядов
     * в нулевом разряде исчисляемое
     * @var \app\tgtools\speller\DigitNameTitles
     */
    private $_dn;

    /**
     * В конструкторе инициализируем источники
     */
    public function __construct($config = []) {
        parent::__construct($config);
        $this->_sd = new SumDigits();
        //сответсвующие числу числительные
        $this->_dn = $this->item->digitNames($this->part);
        if (is_string($this->number)){
            $this->number = (int) $this->number;
        }
        if ($this->number === 0){
            $this->parts = [
                $this->_sd->spell(0, $this->gender = $this->_dn->setkey(0)->gender, true),      //число прописью
                $this->_dn
                    ->setDeclension($this->declension = $this->item->getCurrentItem()->declension)
                    ->spell()  //наименование исчисляемого
            ];
            $this->spelt = TGU::JoinWords($this->parts);
        } else {
            $this->spelt = $this->_inWords($this->number, 0);
        }
        $this->parts[] = $this->number;
    }
    
    /**
     * произносим число $value;
     * соответсвующее ему исчислимое уже добавлено в $item.
     * @param int|string $value число
     * @param Items $item исчисляемое
     * @param int $part номер произносимой части
     * @return $string
     */
    public static function spell($value, Items $item, $part){
        return (new static([
            'number' => $value, 
            'item' => $item, 
            'part' => $part
        ]));
    }

    /**
     * Собственно произносит число $number разряда $i
     * @param string $number
     * @param int $i
     * @return string
     */
    private function _inWords($number, $i){
        $len = strlen($number);
        //задаем разряд
        $this->_dn->key = $i;
        //произносим число разряда, род числительного опредлен
        $spelt = $this->_sd->spell(($len > 3) ? mb_substr($number, $len -= 3): $number, $this->_dn->gender);
        //число произнесено, запоминаем склонение
        $this->_dn->declension = $this->_sd->declension;
        if ($i === 0){
            //Склонение нулевого разряда, соответсвует склонению всего числа
            $this->declension = $this->_sd->declension;
            //нулевому разряду соответсвует исчисляемое
            $this->gender = $this->_dn->gender;
        }
        if (strlen($number) > 3){
            //произносим следующий разряд, объединяем с текущим
            $spelt = TGU::JoinWords([$this->_inWords(mb_substr($number, 0, $len), $i+1), $spelt]); 
        }
        //имя числительного
        $digit = $this->_dn->spell($i);
        //нулевой разряд произносится последним
        if ($i === 0){
            //отдельно произнесенное число и его исчисляемое
            $this->parts = [$spelt, $digit]; 
        }
        return TGU::Join2Words($spelt, $digit);
    }
    
    public function addDebugger($id, $name, $value) {
        $this->debug[$id][$name] = $value;
    }
}


