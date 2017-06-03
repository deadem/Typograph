<?php

namespace Test;

use DJEM\Typograph\Typograph;

class ProcessedTest extends \PHPUnit\Framework\TestCase
{
    public function testProcessed()
    {
        $values = preg_split('/\r?\n/', $this->text);
        do {
            do {
                $first = array_shift($values);
            } while (empty($first));
            $second = array_shift($values);

            $parsed = Typograph::parse($first);
            $this->assertEquals($second, $parsed);

            $parsed = Typograph::parse($parsed);
            $this->assertEquals($second, $parsed);
        } while (! empty($values));
    }

    private $text = <<<'EOF'
«толстого клиента»
&laquo;толстого клиента&raquo;

реализован на базе трехзвенной архитектуры с использованием в качестве клиентского приложения «толстого клиента».
реализован на&nbsp;базе трехзвенной архитектуры с&nbsp;использованием в&nbsp;качестве клиентского приложения &laquo;толстого клиента&raquo;.

«Умной» практически весь контент хранится в виде заранее
&laquo;Умной&raquo; практически весь контент хранится в&nbsp;виде заранее
EOF;
}
