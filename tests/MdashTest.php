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

            $parsed = Typograph::parse($first);
            $this->assertEquals($second, $parsed);

            $parsed = Typograph::parse($parsed);
            $this->assertEquals($second, $parsed);
        } while (! empty($values));
    }

    private $text = <<<'EOF'
- Кто здесь?
<nobr>&mdash; Кто здесь</nobr>?

<p>- Кто здесь?</p><p>- Это я!</p>
<p><nobr>&mdash; Кто здесь</nobr>?</p><p><nobr>&mdash; Это я</nobr>!</p>

Я - звезда!
<nobr>Я &mdash; звезда</nobr>!

А я <nobr>&mdash; зве зда</nobr>!
<nobr>А я &mdash; зве зда</nobr>!

А <nobr>я &mdash; зве</nobr> зда!
<nobr>А я &mdash; зве зда</nobr>!

«Умной статики» - практически весь контент хранится в виде заранее
&laquo;Умной статики&raquo; <nobr>&mdash; практически</nobr> весь контент хранится <nobr>в виде</nobr> заранее
EOF;
}
