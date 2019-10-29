<?php
namespace app\tgtools\speller;
use app\tgtools\common\TGU;
use app\tgtools\speller\PrimeNumberTitles;

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
     * @var \app\tgtools\speller\PrimeNumberTitles
     */
    private $_pnt;

    public $debug = ['step' => 0];
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
        //$this->debug['step']++;
        //$this->addDebug($value,['$int' => $int,'($int === 0) && !$zero' => \app\tgtools\common\TGC::toString(($int === 0) && !$zero)]);
        if (($int === 0) && !$zero){
            return '';
        }
        if ($gender !== null){
            $this->gender = $gender;
        }
        if ($int >= 100){
           // $this->addDebug($number, '$int > 100');
            return TGU::Join2Words( $this->_pnt->spell( $base = $number[0].'00'), $this->spell($int - (int)$base));
        } elseif ($int >= 20){
            //$this->addDebug($number, '$int > 20');
            return TGU::Join2Words($this->_pnt->spell( $base = $number[0].'0'), $this->spell($int - (int)$base));
        } else {
            //$this->addDebug($number, '$int < 20');
            $value = $this->_pnt->spell($number, $this->gender);
            $this->declension = $this->_pnt->declension;
            return $value;
        }
    }
    
    protected function addDebug($title, $value){
        $this->debug[]=[
            'step' => $this->debug['step'],
            'title' => $title, 
            'value' => $value ];
    }
}
