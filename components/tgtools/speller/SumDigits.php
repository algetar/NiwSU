<?php
namespace app\components\tgtools\speller;
use app\components\tgtools\common\TGU;
use app\components\tgtools\speller\PrimeNumberTitles;

/**
 * Description of SumDigits
 * Произносит тысячный разряд
 * 
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
class SumDigits {
    /**
     * Род исчисляемого, назначается до вызова метода spell(). 
     * Применяется к последнему базовому числу разряда
     * @var int
     */
    public $gender = 0;
    /**
     * Склонение исчисляемого, назаначается последним числом в разряде
     * @var int
     */
    public $declension = 0;
    /**
     * Наименования базовых чисел
     * @var \app\components\tgtools\speller\PrimeNumberTitles
     */
    private $_pnt;
    
    /**
     * В конструкторе инициализируем список базовых чисел
     */
    public function __construct() {
        $this->_pnt = new PrimeNumberTitles();
    }
    
    /**
     * Произносит число из 3 цифр (разряд).
     * Число в формате string.
     * @param string $value
     * @return string
     */
    public function spell($value, $gender = null, $zero = false){
        $int = (int) $value;
        $number = (string) $int;
        if (($int === 0) && !$zero){
            return '';
        }
        if ($gender !== null){
            $this->gender = $gender;
        }
        if ($int >= 100){
            return TGU::join2Words( $this->_pnt->spell( $base = $number[0].'00'), $this->spell($int - (int)$base));
        } elseif ($int >= 20){
            return TGU::join2Words($this->_pnt->spell( $base = $number[0].'0'), $this->spell($int - (int)$base));
        } else {
            $value = $this->_pnt->spell($number, $this->gender);
            $this->declension = $this->_pnt->declension;
            return $value;
        }
    }
}
