<?php

namespace Test;

use DJEM\Typograph\Typograph;

class ProcessedTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessed()
    {
        $values = preg_split('/\r?\n/', $this->text);
        do {
            do {
                $first = array_shift($values);
            } while (empty($first));
            $second = array_shift($values);

            $this->assertEquals($second, Typograph::parse($first));
        } while (! empty($values));
    }

    private $text = <<<EOF
«толстого клиента»
&laquo;толстого клиента&raquo;

реализован на базе трехзвенной архитектуры с использованием в качестве клиентского приложения «толстого клиента».
реализован <nobr>на базе</nobr> трехзвенной архитектуры <nobr>с использованием</nobr> <nobr>в качестве</nobr> клиентского приложения &laquo;толстого клиента&raquo;.

«Умной статики» — практически весь контент хранится в виде заранее
&laquo;Умной статики&raquo; — практически весь контент хранится <nobr>в виде</nobr> заранее
EOF;
}
