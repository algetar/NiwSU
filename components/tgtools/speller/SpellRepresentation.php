<?php

namespace app\components\tgtools\speller;

/**
 * Description of SpellRepresentation
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class SpellRepresentation extends \yii\base\Object implements RepresentationInterface {
    /**
     * Заданое число
     * @var string
     */
    public $number;
    /**
     * Заданный формат представления данных
     * Элементы формата должны соответствовать формату vsprintf( $format, $args[])
     * где $args будет массив:
     * [
     *  //целое число/целая часть числа
     *  0 - целая часть числа прописью, элемент 1$
     *  1 - исчисляемое целой части числа, элемент 2$
     *  4 - целая часть числа, элемент 5$
     *  //дробная часть числа
     *  2 - дробная часть числа прописью, элемент 3$
     *  3 - исчисляемое дробной части числа, элемент 4$
     *  5 - дробная часть числа, элемент 6$
     * ]
     * @var string
     */
    public $format;
    /**
     * Исчисляемое текущего числа $number
     * @var \app\components\tgtools\speller\Items
     */
    public $item;
    /**
     * Представление числа по частям
     * [
     *  //целое число/целая часть числа
     *  0 - целая часть числа прописью, элемент 1$
     *  1 - исчисляемое целой части числа, элемент 2$
     *  4 - целая часть числа, элемент 5$
     *  //дробная часть числа
     *  2 - дробная часть числа прописью, элемент 3$
     *  3 - исчисляемое дробной части числа, элемент 4$
     *  5 - дробная часть числа, элемент 6$
     * ]
     * @var array
     */
    protected $_parts = [];
    /**
     * Число прописью
     * @var string
     */
    protected $_result;
    /**
     * Конструктор
     * @param array $config
     *  [
     *      'number' => <int|double|string> число
     *      'item' => <Items> исчисляемое текущего числа $number
     *      'format' => <string> формат представления данных
     *  ]
     */
    public function __construct(Items $item, $format = null){
        parent::__construct([
            'item' => $item,
            'format' => $format
        ]);
    }
    
    /**
     * Число прописью
     * @param string|int|double $number
     * @return string полученное значение
     */
    public function spell($number){
        $this->number = (string)((double) $number);
        if ((strpos($this->number, '.') === false)){
            $this->number = (int) $number;
        } else {
            $this->number = (double) $number;
        }
        if (is_integer($this->number)){
            //целое число
            return $this->spellInt();
        } else {
            //вещественное число
            return $this->spellDbl();
        }
    }
    /**
     * Функция должна вернуть массив из двух
     * строковых значений
     * @return array полученное значение
     */
    protected function splitNumber($number) {}
    /**
     * Целое число прописью
     * @return string полученное значение
     */
    protected function spellInt(){}
    /**
     * вещественное число прописью
     * @return string полученное значение
     */
    protected function spellDbl(){}

    /**
     * Число и исчисляемое прописью
     * @return string
     */
    public function getResult(){
        return $this->_result;
    }
    /**
     * Число прописью по частям
     * @return array
     */
    public function getResultParts(){
        return $this->_parts;
    }
    /**
     * Заданый формат представления данных
     * @return string
     */
    public function getFormat(){
        return $this->format;
    }
}
