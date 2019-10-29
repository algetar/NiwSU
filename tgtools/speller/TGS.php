<?php
namespace app\tgtools\speller;
use app\tgtools\speller\Items;

/**
 * Description of TGS
 * an object spell (in Words) utility
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class TGS {
    //Род слова
    const GENDER_NEUTRAL        = 0;    // среднего рода
    const GENDER_MALE           = 1;    // женского рода
    const GENDER_FEMALE         = 2;    // мужского рода
    //Склонение слова
    const DECLENSION_MANY       = 0;    //исчисляемое соотностится с числом 0 и числами от 5
    const DECLENSION_SINGLE     = 1;    //исчисляемое соотностится с 1
    const DECLENSION_FEW        = 2;    //исчисляемое соотностится с числом от 2 до 4
    const DECLENSION_COUNTER    = 1;    //исчисляемое с перечислением
    const DECLENSION_FRACTION   = 2;    //исчисляемое соотностится с числом с отрицательной степенью
    //группы исчисляемых
    const GROUP_NONE = 0; //нет группы исчисляемых
    const GROUP_MONEY = 1; //группа из двух исчисляемых, соответсвующих целой и дробной части 
    const GROUP_STACK = 2; //группа из двух и более исчисляемых, сдвигающих порядок тысячных разрядов
    //Дополнительные строки исчисляемых 
    const ROW_EMPTY = ['titles' => ['']];
    const ROW_INT = ['titles' => ['целых', 'целая', 'целых'], 'type' => self::GENDER_FEMALE];
    //форматные символы, используемые sprintf
    //'/%(.*?)['.self::SPRINTF_FLAGS.']*['.self::SPRINTF_SPECS.']/' Регулярное выражение для анализа шаблона sprintf()
    //const SPRINTF_FLAGS = '0-9\$\.\-\+\s\''; //флаги форматной строки
    //const SPRINTF_SPECS = 'sducoxXbgGeEfF';  //Спецификаторы форматной строки, они закрывают форматную строку
    
    /**
     * Называет объект $value с исчисляемым $item в формате $format
     * @param mixed $value число
     * @param string $item исчисляемое
     * @param string $format формат представления чисел
     * @return string 
     */
    public static function spell($value, $item = null, $format = null){
        if (is_bool($value)){
            return $value ? 'true': 'false';
        } elseif (is_array($value)) {
            return 'array';
        } elseif (is_object($value)) {
            return get_class($value);
        } elseif (is_resource($value)) {
            return get_resource_type($value);
        } elseif (is_null($value)) {
            return 'NULL';
        }
        return static::spellNumber($value, $item, $format);
    }
    
    /**
     * Называет число $value с исчисляемым $item в формате $format
     * @param double|int|string $value число
     * @param string $item исчисляемое
     * Строка в виде @c.x
     * @ - опционально, означает имя группы, иначе имя шабона.
     * .x - опционально, если указано, то х это число, означающее сокращение 
     * наименования исчисляемого до х букв.
     * Примеры:
     * spell(1102002.23, '@кг')
     *  результат: одна тысяча сто две ТОННЫ два ЦЕЛЫХ двадцать три сотых килограмма;
     *  тоже для целого числа:
     * spell(1102002, '@кг')
     *  результат: одна тысяча сто две ТОННЫ два килограмма;
     *  тоже для целого числа без группы:
     * spell(1102002, 'кг')
     *  результат: один миллион сто две тысячи два килограмма;
     * spell(5034.02, '@руб')
     *  результат: пять тысяч тридцать четыре рубля две копейки
     *  тоже самое с сокращением
     *  spell(5034.02, '@руб.3')
     *  результат: пять тысяч тридцать четыре руб. две коп.
     * 
     * @param string $format формат представления числа 
     * Форматная строка, которая будет использована в функции
     * string vsprintf($format, array $values)
     * Число в ходе анализа будет представляется в виде:
     * [
     *  //целое число/целая часть числа
     *  0 - целая часть числа прописью, элемент 1$
     *  1 - исчисляемое целой части числа, элемент 2$
     *  2 - целая часть числа, элемент 3$
     *  //дробная часть числа
     *  3 - дробная часть числа прописью, элемент 4$
     *  4 - исчисляемое дробной части числа, элемент 5$
     *  5 - дробная часть числа, элемент 6$
     * ]
     * Примеры:
     * spell(25, 'экз', 'документ составлен в количестве: %3$04d %2$s')
     *  результат: документ составлен в количестве: 0025 экземпляров
     *  тоже самое с другим элементом в форматной строке
     * spell(21, 'экз', 'документ составлен в количестве: %1$s %2$s')
     *  результат: документ составлен в количестве: двадцать один экземпляр
     * spell(5034.02, '@руб', 'итого %1$s %2$s, %6$02d %5$s')
     *  результат: итого пять тысяч тридцать четыре рубля, 02 копейки
     * @return string полученый результат
     */
    public static function spellNumber($value, $item = null, $format = null){
        return Items::parse($item, $format)->spell($value);
    }
    
    /**
     * Синоим spellNumber
     * @param double|int|string $value
     * @param string $item
     * @param string $format
     * @return string
     */
    public static function sn($value, $item = null, $format = null){
        return static::spellNumber($value, $item, $format);
    }

    /**
     * Называет целое число $value с исчисляемым $item в формате $format.
     * Число насильно переводится в формат intger, денежная группа по-прежнему
     * считается числом с двумя знаками после запятой
     * @param int|string $value число
     * @param string $item исчисляемое
     * @param string $format формат представления числа
     * @return string полученый результат
     */
    public static function spellInt($value, $item = null, $format = null){
        return Items::parse($item, $format)->spell((int) $value);
    }
    /**
     * Называет целое число $value с исчисляемым $item в формате $format.
     * Число насильно переводится в формат intger, денежная группа по-прежнему
     * считается числом с двумя знаками после запятой
     * синоним spellInt
     * @param int|string $value число
     * @param string $item исчисляемое
     * @param string $format формат представления числа
     * @return string полученый результат
     */
    public static function si($value, $item = null, $format = null){
        return static::spellInt($value, $item, $format);
    }

    /**
     * Называет вещественное число $value с исчисляемым $item в формате $format.
     * @param double|string $value число
     * @param string $item исчисляемое
     * @param string $format формат представления числа
     * @return string полученый результат
     */
    public static function spellDoubl($value, $item = null, $format = null){
        return Items::parse($item, $format)->spell((double) $value);
    }
    /**
     * Называет вещественное число $value с исчисляемым $item в формате $format.
     * синоним spellDoubl.
     * @param double|string $value число
     * @param string $item исчисляемое
     * @param string $format формат представления числа
     * @return string полученый результат
     */
    public static function sd($value, $item = null, $format = null){
        return static::spellDoubl($value, $item, $format);
    }
}
