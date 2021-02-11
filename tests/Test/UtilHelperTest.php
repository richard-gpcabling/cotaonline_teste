<?php

namespace Test;

use App\Helpers\UtilHelper;
use UnitTestCase;

class UtilHelperTest extends UnitTestCase
{
    public function testMakeTrim()
    {
        $str = 'Leandro Teixeira';
        $length = 10;

        $expected = 'Leandro';

        $this->assertEquals($expected, UtilHelper::makeTrim($str, $length));
    }
}
