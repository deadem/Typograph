<?php

namespace Test;

use DJEM\Typograph\Typograph;

class MdashTest extends \PHPUnit\Framework\TestCase
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

    private $text = <<<'EOF'
- Кто здесь?
<nobr>&mdash; Кто здесь</nobr>?

<p>- Кто здесь?</p><p>- Это я!</p>
<p><nobr>&mdash; Кто здесь</nobr>?</p><p><nobr>&mdash; Это я</nobr>!</p>

«Умной статики» - практически весь контент хранится в виде заранее
&laquo;Умной статики&raquo; <nobr>&mdash; практически</nobr> весь контент хранится <nobr>в виде</nobr> заранее
EOF;
}
