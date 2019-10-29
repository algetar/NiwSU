<?php

namespace app\tgtools\speller;
use app\tgtools\speller\TGS;
use app\tgtools\common\TGU;
use app\tgtools\speller\ItemRepresentation;
use app\tgtools\speller\MoneyRepresentation;
use app\tgtools\speller\StackRepresentation;
use app\tgtools\speller\ItemTitles;
use app\tgtools\speller\ItemGroupTitles;
use app\tgtools\speller\DigitNameTitles;
use app\tgtools\speller\DecimalTitles;

/**
 * Description of Items
 * Исчисляемые.
 * Анализирует шаблон, и выставляет соответствующие свойства класса.
 * 
 * @property \app\tgtools\speller\ItemTitles $titles списки исчисляемых
 * @property \app\tgtools\speller\ItemGroupTitles $groups списки групп исчисляемых
 * @property \app\tgtools\speller\DecimalTitles $decimals список наименований разрядов дробной части числа
 * @property int $cutoff сокращение наименования исчисляемого
 * 
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class Items extends \yii\base\Object {
    /**
     * Количество цифр после запятой
     * @var int
     */
    public $status = 0;
    /**
     * Тип группы 
     *  0 - нет группы
     *  1 - money, пара исчисляемых в которых целая часть числа
     *      соотносится с первой, дробная часть числа со второй
     *  2 - группа, 2 и более исчисляемых, которые сдвигают порядок
     *      разрядов числительных (например, грамм исчисляется сначала
     *      в килограммах, потом в тоннах, и только потом идут тысячи и т.д.).
     * @var int
     */
    public $type = 0;
    /**
     * Имя исчисляемого/группы исчисляемых
     * @var string
     */
    public $name = '';
    /**
     * формат шаблона:
     * @c.x
     * @ - опционально, если присутсвует, то 'с' означает имя группы, иначе имя шабона
     * .x - опционально, если указано, то х это число, означающее 
     * сокращение наименования исчисляемого до х букв. Хранится в $cutOff.
     * @var string
     */
    public $format;
    /**
     * Задано сокращение наименования исчисляемого
     * @var string
     */
    private $_cutOff = '';
    /**
     * Список имен разрядов с установленными исчисляемыми 
     * для произнесения целой и дробной части числа соответсвенно
     * @var \app\tgtools\speller\DigitNameTitles[]
     */
    protected $_digits;
    /**
     * @var \app\tgtools\speller\ItemTitles
     */
    protected $_titles;
    /**
     * Наименования разрядов дробной части числа
     * @var \app\tgtools\speller\DecimalTitles
     */
    protected $_decimals;
    /**
     * @var \app\tgtools\speller\ItemGroupTitles
     */
    protected $_groups;
    
    /**
     * Анализ форматной строки $format
     * @param array $config
     */
    public function __construct($config = array()) {
        parent::__construct($config);
        //первый символ
        $this->parseItem($this->format);
        $this->_digits = [null, null];
        $this->_titles = New ItemTitles();
    }

    /**
     * Анализирует шаблоны
     * @param string $item шаблон исчисляемого
     * формат шаблона:
     * @c.x
     * @ - опционально, если присутсвует, то 'с' означает имя группы, иначе имя исчисляемого
     * .x - опционально, если указано, то х это число, означающее 
     * сокращение наименования исчисляемого до х букв. Хранится в $cutOff.
     * @param string $format формат представления данных
     * @return \static
     */
    public static function parse($itemFormat, $format){
        $item = new static([
            'format' => $itemFormat
        ]);
        if ($item->type === TGS::GROUP_NONE){
            return new ItemRepresentation($item, $format);
        }elseif ($item->type === TGS::GROUP_MONEY){ 
            return new MoneyRepresentation($item, $format);
        }elseif ($item->type === TGS::GROUP_STACK){ 
            return new StackRepresentation($item, $format);
        }
    }
    
    /**
     * Применяет результаты анализа форматной строки
     * @param int $status
     *  0 целое число
     *  > 0 количество цифр после запятой дробного числа
     */
    public function push($status){
        $this->status = $status;
        if ($this->type === TGS::GROUP_NONE){
            //нет группы, 
            $this->pushItem($this->name, $this->status);
        } else {
            $this->pushGroup();
        }
    }

    /**
     * Инициализирует списки числительных для целой/дробной 
     * части числа и добавляет соответствующее исчисляемой 
     * в нулевой разряд 
     * @param string $name имя исчисляемого
     * @param int $status целая/дробная часть числа
     */
    protected function pushItem($name, $status){
        if ($status == 0){
            //исчисляемое исчисляется целым числом
            if ($name == ''){
                $this->digitNames(0)->emptyRow();
            } else {
                $this->digitNames(0)->addItem($this->titles->setKey($name));
            }
            $this->digitNames(0)->setKey(0);
        } else {
            if ($this->type === TGS::GROUP_MONEY){
                //для денежной группы, обе части числа представляют себя
                //целым числом, каждый со своим исчисляемым.
                $this->digitNames(1)->addItem($this->titles->setKey($name));
            } else {
                //последнее исчислимое, становится исчисляемым всего числа
                $this->titles->setKey($name)->setDeclension($this->decimals->declension);
                //исчисляемое исчисляется дробным числом, к целой части добавляем слово целых,
                //а род числа перед ним будет определятся исчисляемым
                $this->digitNames(0)->intRow($this->titles->gender);
                $this->digitNames(1)->addItem($this->decimals->setKey($status));
            }
        }
    }
    
    /**
     * Заполняет списки числительных группой исчисляемых 
     */
    protected function pushGroup(){
        if ($this->type === TGS::GROUP_MONEY){
            //денежки, не может быть целым числом
            for ($i = 0; $i <2; $i++){
                $this->pushItem($this->groups->titles[$i], $i);
            }
        } elseif($this->type === TGS::GROUP_STACK){
            //целая часть числа
            //весь стэк кроме последнего
            $last = count($this->groups->titles) - 1;
            for ($i = 0; $i < $last; $i++){
                $this->pushItem($this->groups->titles[$i], 0);
            }
            //назначенный статус связан только с последним исчислимым
            $this->pushItem($this->groups->titles[$last], $this->status);
        }
    }
    
    /**
     * > 0 означает количество букв, до которого сокращается имя исчисляемого
     * @return int
     */
    public function getCutOff(){
        return (int) $this->_cutOff;
    }
    /**
     * Анализирует строку шаблона с исчисляемым
     * @param string $value шаблон
     * @return void
     */
    private function parseItem($value){
        if (TGU::IsEmpty($value, true)){
            $this->_cutOff = $this->name = '';
            $this->type = 0;
            return;
        }
        if(strpos($value, '.') !== false){
            list($name, $this->_cutOff) = explode('.', $value, 2);
        } else {
            list($name, $this->_cutOff) = [$value, ''];
        }
        if ($name[0] == "@"){
            $this->name = mb_substr($name, 1);
            $this->type = $this->groups->setKey($this->name)->type;
        } else {
            $this->name = $name;
            $this->type = 0;
        }
    }

    /**
     * @return \app\tgtools\speller\ItemGroupTitles
     */
    public function getGroups(){
        if ($this->_groups === null){
            $this->_groups = new ItemGroupTitles();
        }
        return $this->_groups;
    }
    /**
     * @return \app\tgtools\speller\ItemTitles
     */
    public function getTitles(){
        return $this->_titles;
    }
    
    /**
     * список наименований разрядов дробной части числа
     * @return \app\tgtools\speller\DecimalTitles 
     */
    public function getDecimals(){
        if ($this->_decimals === null){
            $this->_decimals = new DecimalTitles();
        }
        return $this->_decimals;
    }

    /**
     * Заполненые списки числительных
     * @param int $id
     * @return \app\tgtools\speller\DigitNameTitles
     */
    public function digitNames($id){
        if ($this->_digits[$id] === null){
            $this->_digits[$id] = new DigitNameTitles();
            $this->_digits[$id]->cutOff = $this->cutoff;
        }
        return $this->_digits[$id];
    }
    
    /**
     * Текущее исчисляемое.
     * @return \app\tgtools\speller\DigitNameTitles
     */
    public function getCurrentItem(){
        //в digitNames(0) для целой части/целом числе/рублевой зоны (status = 0)
        //в digitNames(1) для дробной части/копеечной зоны (status = 1)
        if ($this->status === 0){
            return $this->digitNames(0)->setKey(0);
        } else {
            return $this->digitNames(1)->setKey(0);
        }
    }
}
