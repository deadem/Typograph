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
&mdash; Кто&nbsp;здесь?

<p>- Кто здесь?</p><p>- Это я!</p>
<p>&mdash; Кто&nbsp;здесь?</p><p>&mdash; Это&nbsp;я!</p>

Я - звезда!
Я&nbsp;&mdash; звезда!

А я <nobr>&mdash; зве зда</nobr>!
А&nbsp;я&nbsp;&mdash; зве&nbsp;зда!

А <nobr>я &mdash; зве</nobr> зда!
А&nbsp;я&nbsp;&mdash; зве&nbsp;зда!

«Умной статики» - практически весь контент хранится в виде заранее
&laquo;Умной статики&raquo;&nbsp;&mdash; практически весь контент хранится в&nbsp;виде заранее

Полина &mdash; выдающаяся исполнительница. Она не просто поет, а &laquo;проигрывает&raquo; все песни в манере старой школы.
Полина&nbsp;&mdash; выдающаяся исполнительница. Она&nbsp;не&nbsp;просто поет, а&nbsp;&laquo;проигрывает&raquo; все&nbsp;песни в&nbsp;манере старой школы.

<p>Полина Касьянова &ndash; выдающая исполнительница музыкальных композиций в стиле традиционного джаза. Она не просто поет, а &laquo;проигрывает&raquo; все песни в манере старой школы. И это не копирование старых образов, а мини-спектакли с полным погружением - практически по Станиславскому. Обворожительный голос и эмоциональная манера пения просто завораживают публику.</p>
<p>Полина Касьянова&nbsp;&mdash; выдающая исполнительница музыкальных композиций в&nbsp;стиле традиционного джаза. Она&nbsp;не&nbsp;просто поет, а&nbsp;&laquo;проигрывает&raquo; все&nbsp;песни в&nbsp;манере старой школы. И&nbsp;это&nbsp;не&nbsp;копирование старых образов, а&nbsp;мини-спектакли с&nbsp;полным погружением&nbsp;&mdash; практически по&nbsp;Станиславскому. Обворожительный голос и&nbsp;эмоциональная манера пения просто завораживают публику.</p>
EOF;
}
