<?php

namespace Test;

use DJEM\Typograph;

class NoWrapTest extends \PHPUNIT_Framework_Testcase
{
    public function testHtml()
    {
        $values = preg_split('/\r?\n/', $this->text);
        do {
            do {
                $first = array_shift($values);
            } while (empty($first));
            $second = array_shift($values);

            $this->assertEquals($second, Typograph::parse($first));
        } while (!empty($values));
    }

    private $text = <<<EOF
О компании
<nowrap>О компании</nowrap>

кое-как
<nowrap>кое-как</nowrap>

Ну, не знаю.
<nowrap>Ну, не знаю.</nowrap>

ну вот и всё
<nowrap>ну вот и всё</nowrap>

<nowrap>О компании</nowrap>
<nowrap>О компании</nowrap>
EOF;
}
