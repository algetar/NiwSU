<?php

namespace app\components\tgtools\speller;
use app\components\tgtools\speller\TGS;
use app\components\tgtools\common\TGC;
use app\components\tgtools\datasources\TitlesObject;
use yii\base\InvalidValueException;
/**
 * Description of DigitNameTitles
 * Список наименований троичных разрядов (имен числительных) целого числа.
 * Нулевой разряд соотносится с исчисляемым целой части числа,
 * поэтому класс содержит метод который добавляет в нулевую строку
 * списка текущую строку объекта исполянющего роль исчисляемого ItemBehaviourObjects
 * this addCountable(String|ItemBehaviourObjects $item).
 * 
 * @property string $itemName имя исчисляемого
 * 
 * @author GTatarnikov
 */
class DigitNameTitles extends CountableBehaviourObjects {
    /**
     * Задано сокращение наименования исчисляемого
     * @var string
     */
    public $cutOff = 0;
    /**
     * Наименование исчисляемого (числительное нулевого разряда)
     * @var string
     */
    private $_item = '';
    /**
     * Инициализаруем источники
     */
    public function __construct() {
        parent::__construct();
        $this->reload();
    }
    /**
     * Загружает оригинальный список
     */
    public function reload(){
        $this->_rows = new TitlesObject('digit-names');
    }
    /**
     * Добавляет исчисляемое в нулевой разряд имен числительных
     * @param \app\components\tgtools\speller\ItemBehaviourObjects $item
     * @return $this
     * @throws InvalidValueException
     */
    public function addItem($item){
        if ($item === 0){
            //исчислимое опущено, заменяем его на оно
            $this->emptyRow();
        } elseif($item === 1){
            //используется слово 'целое'
            $this->intRow();
        } elseif (is_string($item)) {
            //задано имя исчислимого в виде строки, строку надо снова проверять
            TGS::parseItem($item);
        } elseif(is_subclass_of($item, 'app\components\tgtools\speller\ItemBehaviourObjects')) {
            $this->addRow($item->row);
        } else {
            throw new InvalidValueException('Unknown "Item" type: '. __METHOD__.'('.TGC::toString($item).')' );
        }
        return $this;
    }
    
    /**
     * Проверка на сокращение выдаваемога наименования
     * @return string
     */
    public function getTitle() {
        return $this->cutTitle(parent::getTitle());
    }
    
    /**
     * Сокращает исчисляемое, если задано сокращение
     * @param string $title
     * @return string
     */
    protected function cutTitle($title){
        //если задано сокращение, то применяем только к нулевому разряду
        if ($this->key === 0){
            if ( ($this->cutOff > 0) && (strlen($title) > $this->cutOff)){
                $title = mb_substr($title, 0, $this->cutOff).'.';
            }
            $this->_item = $title;
        }
        return $title;
    }

    /**
     * Имя исчисляемого
     * @return string
     */
    public function getItemName(){
        return $this->_item;
    }
}
