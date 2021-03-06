<?php

namespace Test;

use DJEM\Typograph\Typograph;

class HtmlTest extends \PHPUnit\Framework\TestCase
{
    public function testHtml()
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
<b>"слово"</b><b>"слово"</b>
<b>&laquo;слово&raquo;</b><b>&laquo;слово&raquo;</b>

<b>"</b>слово<b>"</b>
<b>&laquo;</b>слово<b>&raquo;</b>

<div>asd</div><div>"test"</div>asd
<div>asd</div><div>&laquo;test&raquo;</div>asd

<b>"сло<script test='>'>во"</b><b>"сло</script>во"</b>
<b>&laquo;сло<script test='>'>во"</b><b>"сло</script>во&raquo;</b>

<b>"</b>слово<b attr="<test>">"</b><b>"слово"</b>
<b>&laquo;</b>слово<b attr="<test>">&raquo;</b><b>&laquo;слово&raquo;</b>

<b>"</b>слово<b attr='<test>'>"</b><b>"слово"</b>
<b>&laquo;</b>слово<b attr='<test>'>&raquo;</b><b>&laquo;слово&raquo;</b>

<b>"сло<!--script test='>'>во"</b><b>"сло</script -->во"</b>
<b>&laquo;сло<!--script test='>'>во"</b><b>"сло</script -->во&raquo;</b>

"<b>слово</b>"<b>"слово"</b>
&laquo;<b>слово</b>&raquo;<b>&laquo;слово&raquo;</b>

"<b>слово</b>"<b>"сл&amp;ово"</b>
&laquo;<b>слово</b>&raquo;<b>&laquo;сл&amp;ово&raquo;</b>
EOF;
}
