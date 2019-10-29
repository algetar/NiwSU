<?php

namespace app\tgtools\speller;
use app\tgtools\speller\Items;

/**
 * 
 * @author gtatarnikov <gtatarnikov at admnsk.ru>
 */
interface RepresentationInterface {
    public function __construct(Items $item, $format);
    public function spell($number);
    public function getResult();
    public function getResultParts();
    public function getFormat();
}
