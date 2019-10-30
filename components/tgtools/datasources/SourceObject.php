<?php
namespace app\components\tgtools\datasources;

/**
 * SourceObject 
 * Читает массив строк в переменную <source> из хранилища 
 * @author GTatarnikov
 */
class SourceObject extends \yii\base\Object {
    /**
     * Имя источника данных в хранилище
     * @var string
     */
    public $sourceName;
    /**
     * Содержимое источника
     * @var array
     */
    protected $source = [];
    
    /**
     * 
     * @param string $config имя источника данных ['title' => <Name>]
     */
    public function __construct($config) {
        parent::__construct($config);
        //выичсляем путь к хранилищам
        $this->source = require $this->datastorePath;
    }
    
    /**
     * 
     * @return string
     */
    protected function getDatastorePath() {
        if(is_file($this->sourceName)){
            //вмосто имени раздела имеем имя файла
            return $this->sourceName;
        } elseif (defined('DATASTORE_PATH')){
            if(is_file(DATASTORE_PATH)){
                //так на всякий случай
                return DATASTORE_PATH;
            } else if ( preg_match('~^@\w+[\\\/]*~', DATASTORE_PATH, $out)){
                $alias = trim( $out[0],'\/');
                return str_replace($alias, \Yii::getAlias($alias), DATASTORE_PATH).
                    '\\'.$this->sourceName.'.php';
            }    
        } else {
            throw new InvalidConfigException('Invalid data-store configuration: unknown data store path "'.$this->sourceName.'"');
        }
    }
    
}
