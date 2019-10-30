<?php
namespace app\components\tgtools\datasources;

/**
 * Description of DeclinableObject
 * Управление списком записей в TableObject представленных в виде:
 * [
 *  key => ['title' => ['title1', 'title2', ...], 'type' => <key> ],
 *  ...
 * ]
 * ключ key ключ записи
 * опция 'title' может содержать любое количество элементов;
 *      если задано в виде скаляра, то переводится в массив с одним элементом.
 * опция 'type' тип объекта, определенного опцией 'title';
 *      если опущено, то считаем его равным 0
 * 
 * @property mixed $key ключ/индекс текущей строки
 * @property int $titleId текущее значение в списке title
 * @property int $type тип объектов titles
 * @property string $titles список типов 
 * @property string $title значение из списка $titles в позиции $titleId
 * 
 * @author GTatarnikov
 */
class TitlesObject extends TableObject {
    /**
     * Количество элементов в опции 'titles'.
     * = 0, количество элементов не ограничено.
     * > 0, количесво элементов ограничено заданым значением,
     *  если количество меньше заданого, то недостающие элементы
     *  заполняются последним значением массива.
     * @var int
     */
    public $titleCount = 3;

    /**
     * Значения свойств по умолчанию @see TableObject.defaults
     * @var string
     */
    public $defaults = [
        'titles' => ['type' => 'array', 'value' => ''],
        'type' => ['type' => 'int', 'value' => 0, 'access' => 1, 'null' => false],
        'titleId' => ['type' => 'int', 'value' => 0, 'access' => 3, 'null' => 'i'], //ignoring a null
    ];

    /**
     * В конструкторе задаем имя источника данных
     * и читаем его содержимое
     * @param string $title
     */
    public function __construct($title) {
        parent::__construct(['sourceName' => DATASTORE_TITLES.'/'.$title]);
    }
    
    /**
     * Текущее значение ключа
     * Чтение ключа текущей записи $id обернуты в методы
     * для предотвращения присвоения ключу id значения Null
     * @return mixed
     */
    public function getKey(){
        return $this->_id;
    }
    /**
     * Задает текущее значение ключа
     * Чтение ключа текущей записи $id обернуты в методы
     * для предотвращения присвоения ключу id значения Null
     * @param mixed $value
     * @return $this можно использовать для реализации паттерна di
     */
    public function setKey($value){
        if ($value !== null){
            $this->_id = $value;
        }
        return $this;
    }
    
    /**
     * Опция 'titles'
     * Зависит от $titleCount.
     * $titleCount = 0, количество элементов не ограничено.
     * $titleCount > 0, количесво элементов ограничено заданым значением,
     *  если количество меньше заданого, то недостающие элементы
     *  заполняются последним значением массива.
     * @return array
     */
    public function titles(){
        $titles = $this->titles;
        if (($this->titleCount > 0) &&  ($count = count($titles)) < $this->titleCount){
            $item = $titles[$count - 1];
            for ($i = $count; $i < $this->titleCount; $i++){
                $titles[$i] = $item;
            }
        }
        return $titles;
    }

        /**
     * Значение текущей позиции 'titles'
     * @return string
     */
    public function getTitle(){
        return $this->titles()[$this->titleId];
    }
    
}
