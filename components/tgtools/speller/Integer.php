<?php
namespace app\components\tgtools\speller;
use app\components\tgtools\common\TGU;
use app\components\tgtools\speller\SumDigits;
use app\components\tgtools\speller\Items;

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
     * @var \app\components\tgtools\speller\Items
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
     * @var \app\components\tgtools\speller\SumDigits
     */
    private $_sd;
    /**
     * Произносит наименования тысячных разрядов
     * в нулевом разряде исчисляемое
     * @var \app\components\tgtools\speller\DigitNameTitles
     */
    private $_dn;

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
     * В конструкторе инициализируем источники
     */
    public function __construct($config = []) {
        parent::__construct($config);
        //разряды прописью
        $this->_sd = new SumDigits();
        //сответсвующие числу числительные
        $this->_dn = $this->item->digitNames($this->part);
        //в этом классе произносятся только целые числа
        if (!is_integer($this->number)){
            $this->number = (int) $this->number;
        }
        //начинаем с нулевого разряда
        $this->spelt = $this->_spell((string)$this->number, 0);
        $this->parts[] = $this->number;
    }
    
    /**
     * Собственно произносит число $number разряда $i
     * @param string $number число
     * @param int $i номер разряда
     * @return string
     */
    private function _spell($number, $i){
        $len = strlen( $number);
        //задаем текущий разряд разряд
        $this->_dn->key = $i;
        //выделяем очередной разряд
        $digit = ($len > 3) ? mb_substr($number, $len -= 3): $number;
        //Слово ноль произносится, если:
        //1. это нулевой разряд ($i > 0), равно 0 ($this->number === 0) и это 
        //  число целое (($this->item->status === 0)) 
        //2. это нулевой разряд ($i > 0), текущая часть числа равна нулю ($this->number === 0), 
        //  это денежная группа ($this->item->type === TGS::GROUP_MONEY)
        //3. это нулевой разряд ($i > 0), число текущего разряда ((int)$digitNumber === 0),
        //   число не целое ($this->item->status > 0) и это целая часть числа ($this->part === 0)
        if ($i === 0){
            //заодно сохраняем род исчисляемого
            $this->gender = $this->_dn->gender;
            if ($this->number === 0){
                $flag = (($this->item->status === 0) || 
                        ($this->item->type === TGS::GROUP_MONEY)) ?
                        true : false;
            } else {
                $flag = (((int)$digit === 0) && 
                        ($this->item->status > 0) && ($this->item->type !== TGS::GROUP_MONEY) &&
                        ($this->part === 0)) ?
                        true : false;
            }
        } else {
            $flag = false;
        }
        //выделяем очередной разряд из трех цифр, и произносим его
        $speltDigit = $this->_sd->spell($digit, $this->_dn->gender, $flag);
        //число произнесено, выставляем склонение и произносим имя числительного
        $digitName = $this->_dn->setDeclension($this->_sd->declension)->spell($i);
        if ($i === 0){
            //склонение нулевого разряда и есть склонение всего числа
            $this->declension = $this->_sd->declension;
            $spelt = $speltDigit;
        } else {
            $spelt = TGU::joinNotEmpty($speltDigit, $digitName);
        }
        if (strlen($number) > 3){
            //произносим следующий разряд, объединяем с текущим
            $spelt = TGU::joinWords([$this->_spell(mb_substr($number, 0, $len), $i+1), $spelt]); 
        }
        if ($i === 0){
            //нулевой разряд в рекурсии произносится последним, и потому 
            //$spelt содержит произнесенной число без исчисляемого, а
            //$digitName - имя исчисляемого
            return TGU::joinWords($this->parts = [$spelt, $digitName]);
        } else {
            return $spelt;
        }
            
            
    }
    
    public function addDebugger($id, $name, $value) {
        $this->debug[$id][$name] = $value;
    }
}


