<?php

namespace app\tgtools\common;

/**
 * Description of utilities
 * @property \Application app
 * @author gtatarnikov
 */
class TGU {
    
    /**
     * @var \TGU\Application.php the application instance
    */
   public static $app;
    
    /**
     * Объединяет два слова через указаный разделитель
     * если ни одно из слов не пусто
     * @param string $word1 - слово один
     * @param string $word2 - сдово два
     * @param string $delim - разделитель
     * @return string Полученный результат
     */
    public static function join2Words($word1, $word2, $delim = ' '){
        
        if( self::IsEmpty($word1, true)) {return $word2;}
        if( self::IsEmpty($word2, true)) {return $word1;}
        return $word1.$delim.$word2;
    }
    
    /**
     * Объединяет все слова массива $words
     * пустые слова пропускаются вместе с разделителем
     * @param array $words массив строк
     * @param string $delim разделитель
     * @return string Полученный результат
     */
    public static function joinWords($words, $delim = ' '){
        if (!is_array($words)){
            return $words;
        }
        $result = '';
        foreach ($words as $word) {
            $result = static::join2Words($result, $word, $delim);
        }
        return $result;
    }

    /**
     * Объединяет два слова, если оба не пустые
     * @param string $word1 первое слово
     * @param string $word2 второе слово
     * @param string $delim разделитель
     * @return string
     */
    public static function joinNotEmpty($word1, $word2, $delim = ' '){
        if( self::IsEmpty($word1, true)) {return '';}
        if( self::IsEmpty($word2, true)) {return '';}
        return $word1.$delim.$word2;
    }
    
    /**
     * Разделяет слово на две части по первому встреченому символу $delim
     * в массив из двух значений. При отсутсвии разделителя заполняет 
     * элемент массива c индексом $index значением $default
     * @param string $text предложение
     * @param string $delim разделитель слов
     * @param string $default значение по умолчанию
     * @param int $index индекс значения по умолчанию
     * @return array(2)
     */
    public static function split2Words($text, $delim = ' ', $default = '', $index = 1){
        if (strpos($text, $delim) !== false) {
            return explode('/', $text, 2);
        } else {
            if ($index){
                return [$text, $default];
            } else {
                return [$default, $text];
            }
        }
    }
    
    /**
     * Читает с конца строки $text символы пока они являются цифрами или 
     * количесво считанных цифр не превысит $count в строку $number.  
     * Если количество цифр меньше $count, то недостающие заполняюдся 
     * значением $default;
     * При пустом значении $number второй параметр выставляется в null.
     * @param string $text текст
     * @param int $count количество цифр
     * @param mixed $default заполнеие недостающих цифр
     * @return array(2) [текст без цифр, цифры]
     */
    public static function cutNumber($text, $count = 1, $default = 0) {
        $number = '';
        $len = 0;
        for ($i = 0; $i < $count; $i++){
            if (is_numeric($cypher = substr($text, -($i), 1))) {
                $number = $cypher.$number;
                $len++;
            } else {
                $number = ((string)$default).$number;
            }
        }
        if ($len){
            $text = substr($text, 0, strlen($text) - $len);
        }
        return [$text, $number];
    }
    
    /**
     * Раскладывает число $number в массив цифр указным количеством в $count элементов.
     * Недостающие цифры заполняются значением $default;
     * @param int $number
     * @return array
     */
    public static function splitOnCyphes($number, $count, $default = 0){
        $string = (string) $number;
        $cyphers = [];
        $len = strlen($string);
        for ($i = 0; $i < $count; $i++){
            if ($i < $len){
                $cyphers[] = substr($string, $i, 1);
            } else {
                $cyphers[] = $default;
            }
        }
        return $cyphers;
    }

    /**
     * Обрамлякт слово левым и правым символом
     * @param string $word
     * @param string $left
     * @param string $right
     * @return string
     */
    public static function Embrace($word, $left, $right = ''){
        if(self::IsEmpty($right, true)){$right = $left;}
        return $left.$word.$right;
    }

    /**
     * Убирает $count символов с начала и с конца
     * @param string $str
     * @param integer $count
     */
    public static function unEmbrace($str, $count = 1){
        $len = strlen($str);
        if ($len <= 2*$count){
            return '';
        } else {
            return substr($str, $count, $len - 2*$count);
        }
    }

    /**
     * Убирает $count символов с начала и с конца
     * Псевдоним функции unEmbrace
     * @param string $str
     * @param integer $count
     */
    public static function deleteEmbrace($str, $count = 1){
        return static::unEmbrace($str, $count);
    }
    
    /**
     * Преобразует целое во время
     * @param integer $time - количество минут с нуля часов
     */
    public static function toTime($time, $sep = '.'){
        $hours = (int) ($time/60);
        $minutes = $time - $hours * 60;
        return sprintf("%02d", $hours) . $sep . sprintf("%02d", $minutes);
    }
    
    /**
     * Выбирает элемент массива
     * @param mixed $index -- индекс элемента массива
     * @param array $arr -- сам массив
     * @return mixed -- значение выбранного элемента массива 
     */
    public static function arrayValue( $index, $arr){
        return $arr[$index];
    }
    
    /**
     * Проверяет обект, строку, число на ничтожность
     * если $trim == false -- строку из пробелов признает пустой
     * @param mixed $object
     * @param boolean $trim
     * @return boolean
     */
    public static function IsEmpty($object, $trim = false){
        if($trim && is_string($object)){
            if (trim($object) == ''){
                $object = '';
            }
        }
        return ($object === null) || ($object === []) || ($object === 0) || ($object === '') || ($object === '0');
    }

    /**
     * Проверяет идентификатор на пустое значение
     * @param integer $id -- идентификатор
     * @return boolean
     */
    public static function IsIdEmpty($id){
        if( ($id == null) || ($id == '') || ($id <= 0)){
            return true;
        } else {
            return false;
        }
    }
    /**
     * Подсчитывает количество единичных бит
     * в двоичном представлении десятичного числа.
     * @param integer $number
     * @return int
     */
    public static function bitCount($number)
    {   
        $result = 0;
        for ($i = $number; $i != 0; $i >>= 1){
            if ($i & 1){
                $result++;
            }
        }
        return $result;
    }
    
    /**
     * Единичные биты числа $number1 совпадают 
     * с единичными битами числа $number2
     * @param int $number1
     * @param int $number2
     * @return bool
     */
    public static function bitEqual($number1, $number2){
        return (($number1 & $number2) === $number1);
    }

    /**
     * Вычисляет позицию первого значащего бита
     * @param integer $number
     */
    public static function bitPos($number){
        $result = 0;
        for ($i = $number; $i != 0; $i >>= 1){
            if ($i & 1){
                return $result;
            }
            $result++;
        }
        return 0;
    }
    
    /**
     * Вычисляет статус индекса относительно базового
     *  0 -- исходный индекс базовый
     *  1 -- базовый индекс начало составного
     *  2 -- базовый индекс часть составного
     * @param int $ridx -- исходный индекс
     * @param int $idx -- базовый индекс
     * @return bool
     */
    public static function bitStatus($ridx, $idx){
        if (TGU::bitCount($ridx) === 1){
            //это базовый ресурс единственный
            return 0;
        } elseif (TGU::bitPos($ridx) === TGU::bitPos($idx)) {
            //базовый ресурс начало составного
            return 1;
        } else {
            //базовый ресурс перекрыт составным
            return 2;
        }
    }

    /**
     * Переводит число в строку битов
     * @param integer $number
     * @param integer $power -- степень двойки - разрядность числа
     * @return string
     */
    public static function bitToString($number, $power = 2){
        return sprintf("%'.0".(pow(2, $power))."b", $number);
    }

    /**
     * Уменьшает разрядность числа
     * @param integer $number
     * @param integer $power -- степень двойки - разрядность числа
     * @return integer
     */
    public static function bitDelevel($number, $power = 2){
        $str = self::bitToString($number, $power);
        $bits = '';
        for ($i = 0; $i < strlen($str); $i += 2 ){
            if(substr($str, $i, 2) == '11'){
                $bits .= '1';
            } else {
                $bits .= '0';
            }
        }
        return bindec($bits);
    }
    
    /**
     * выставляет нулевой бит числа в единицу
     * @param int $number
     * @param int $base
     * @return int
     */
    public static function bitAdd($number, $base){
        //
        if( $number & $base){
            //бит уже выставлен
            return $number;
        } else {
            return $number + $base;
        }
    }

    /**
     * выставляет бит числа в 0
     * @param int $number
     * @param int $base
     * @return int
     */
    public static function bitMinus($number, $base){
        //
        if( $number & $base){
            //бит выставлен
            return $number - $base;
        } else {
            //бит уже нулевой
            return $number;
        }
    }

    /**
     * Генерирует случайный цвет в виде строки 16-ричных цифр,
     * случайным образом меняя интенсивность каждого RGB цвета.
     * @param array $rgb 
     * @return string
     */
    public static function genColor( $rgb = null){
        if ($rgb === null){
            $rgb = static::genRGB(); 
        }
        for( $i = 0; $i < 3; $i++){
            if ($rgb[$i] === -1){
                $rgb[$i] = 255;
            } else {
                $rgb[$i] = rand(0, $rgb[$i]);
            }
        }
        return vsprintf('#%02x%02x%02x', $rgb);
    }
 
    /**
     * Генерирует случайный шаблон rgb для генерации случайного цвета
     * Случайным образом исключает один из цветов из списка генерации 
     * случайной интенсивности цвета
     * @param boolean $base -- юридическое лицо
     * @return array
     */
    public static function genRGB(){
        $rgb = [255, 255, 255];
        $rgb[rand(0, 2)] = -1 ;
        return $rgb;
    }

    /**
     * Подбирает цвет (forecolor), выдимый на фоне (backcolor)
     * @param string $color - цвет фона
     * @return string
     */
    public static function genCompatibilityColor( $color){
        $rgb = [hexdec(substr($color, 1, 2)), substr($color, 3, 2), substr($color, 5, 2)];
        //0.3*R + 0.59*G + 0.11*B
        return sprintf('#%02x%02x%02x', (255-$rgb[0]) * 0.3, (255-$rgb[1])* 0.59, (255-$rgb[2])*0.11);
    }
    
    /**
     * Сравнивает два значения
     * @param mixed $value1
     * @param string $sign
     * @param mixed $value2
     * @return bool
     */
    public static function compare($value1, $sign, $value2){
	switch ($sign) {
	    case '<':
		return ($value1 < $value2);
	    case '>':
		return ($value1 > $value2);
	    case '>=':
		return ($value1 >= $value2);
	    case '<=':
		return ($value1 <= $value2);
	    case '==':
		return ($value1 == $value2);
	    case '===':
		return ($value1 === $value2);
	    case '!=':
            case '#':
	    case '<>':
		return ($value1 != $value2);
	}
    }
    
    public static function dumpVar($var){
        if (is_array($var)){
            $result = '{';
            foreach ($var as $key => $value) {
                $result .= "[$key]=".static::dumpVar($value).',';
            }
            $result .= '};';
        } else {
            $result = $var;
        }
        return $result;
    }    
    
    /**
     * Выделяет из строки слева указаное количество слов
     * @param string $string
     * @return string
     */
    public static function subWords($string, $count = 1){
        $words = explode(' ', $string);
        if (count($words) <= $count){
            return $string;
        }
        return static::implode($words, ' ', $count);
    }
    
    /**
     * Показывает SQL запрос Yii2::Query
     * @param \yii\db\Query $query
     * @param int $mode
     * @return string
     */
    public static function getSQL(\yii\db\Query $query, $mode = 1){
        if ($mode == 1 ){
            return $query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql;
        } else {
            return $query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->sql;
        }
        
    }
            
    /**
     * True - если в $param содержатся только латинские буквы и цифры
     * @param string $param
     * @return boolean
     */
    public static function isLatinScript($param) {
        return preg_match("/^[a-z0-9_]+$/i", $param);
    }
}