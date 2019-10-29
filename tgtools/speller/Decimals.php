<?php
namespace app\tgtools\speller;
use app\tgtools\speller\TGS;
use app\tgtools\common\TGU;
use app\tgtools\speller\Items;
/**
 * Description of Decimals
 * Называем дробную часть числа
 * 
 * @property \app\tgtools\speller\Integer $integer число прописью
 * 
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class Decimals extends \yii\base\Object {
    /**
     * Называное число
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
     * Произносит наименования тысячных разрядов
     * в нулевом разряде исчисляемое
     * @var \app\tgtools\speller\DigitNameTitles
     */
    private $_dn;
    /**
     * Число дробной части прописью
     * @var \app\tgtools\speller\Integer
     */
    private $_int;

    /**
     * Число прописью
     * @return \app\tgtools\speller\Integer
     */
    public function getInteger(){
        return $this->_int;
    }
    
    /**
     * Вычисление всех параметров класса в конструкторе
     * @param array $config
     */
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dn = $this->item->digitNames(1);
        //любая часть числа всегда женского рода
        $this->gender = TGS::GENDER_FEMALE;
        //само исчислимое не влияет на склонение числа, но склоняется от наименования
        //разряда дробного числа, которые, впрочем, всегда в склонении 2-4
        $this->declension = TGS::DECLENSION_FRACTION;
        $this->parts = [
            Integer::spell($this->number, $this->item, 1)->spelt, //названное число
            $this->item->titles->setDeclension($this->declension)->spell(), //исчисляемое
        ];
        //теперь собираем все вместе
        $this->spelt = TGU::JoinWords($this->parts);
        $this->parts[] = $this->number; //само число
    }
    
    /**
     * Називаем число прописью
     * @param int|string $number
     * @param Items $item
     * @return \static
     */
    public static function spell($number, Items $item = null){
        return (new static([
            'number' => (int) $number,
            'item' =>$item
        ]));
    }
}
