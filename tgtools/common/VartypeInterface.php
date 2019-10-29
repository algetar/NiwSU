<?php

namespace app\tgtools\common;

/**
 *
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
interface VartypeInterface {
    /**
     * Преобразует переменную текущего типа в строку 
     * @param mixed $value
     * @return string
     */
    public function toString($value);
    /**
     * Преобразует строку в переменную текущего типа
     * @param string $value
     * @return mixed
     */
    public function toVar($value);
    /**
     * Тип переманной совпадает с текущим
     * @param mixed $value
     * @return boolean
     */
    public function thisType($value);
}
