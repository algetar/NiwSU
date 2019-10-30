<?php
defined('DATASTORE_PATH') or define('DATASTORE_PATH', "@app\web\data-store");    //Путь к хранилищу
defined('DATASTORE_TITLES') or define('DATASTORE_TITLES', 'title-objects');      //Раздел хранилища класса TitlesObject
/**
 * Создает удобоваримый вывод функции print_r
 * @param array $arr
 */
function debug($arr)
{
    echo '<pre>';
    echo '--------------------------------------------------------------</br>';
    print_r($arr);
    echo '</br>--------------------------------------------------------------';
    echo '</pre>';
}
