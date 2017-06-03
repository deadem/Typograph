<?php

namespace Test;

use DJEM\Typograph\Typograph;

class NoWrapTest extends \PHPUnit\Framework\TestCase
{
    public function testNoWrap()
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

    private $text = <<<EOF
О компании
О&nbsp;компании

кое-как
кое-как

Ну, я не знаю.
Ну, я&nbsp;не&nbsp;знаю.

ну вот и всё
ну&nbsp;вот&nbsp;и&nbsp;всё

Лолики ну вот и всё
Лолики ну&nbsp;вот&nbsp;и&nbsp;всё

Лолики. ну вот и всё
Лолики. ну&nbsp;вот&nbsp;и&nbsp;всё

<nobr>О компании</nobr>
О&nbsp;компании

Сегодня будет создан
Сегодня будет создан

закон "Сегодня охране".
закон &laquo;Сегодня охране&raquo;.

сочетание "кавычек"
сочетание &laquo;кавычек&raquo;

"Европа-Азия"
&laquo;Европа-Азия&raquo;

"номер ICQ 123"
&laquo;номер ICQ&nbsp;123&raquo;

"c:\www\sites\"
&laquo;c:\www\sites\&raquo;

"Справка 09"
&laquo;Справка 09&raquo;

"данные:"
&laquo;данные:&raquo;

"новый тариф*"
&laquo;новый тариф*&raquo;

"Star Flyer Inc."
&laquo;Star Flyer Inc.&raquo;

"слово", слово
&laquo;слово&raquo;, слово

слово, "слово"
слово, &laquo;слово&raquo;

Ответчик. "бла бла" вап. Бла "ввв"
Ответчик. &laquo;бла&nbsp;бла&raquo;&nbsp;вап. Бла&nbsp;&laquo;ввв&raquo;

"Приветствуем Вас"
&laquo;Приветствуем Вас&raquo;

Она добавила: "самый любимый "эсмеральда"".
Она&nbsp;добавила: &laquo;самый любимый &bdquo;эсмеральда&ldquo;&raquo;.

"Фирма "Терминал", "ОблСнаб"
&laquo;Фирма &bdquo;Терминал&ldquo;, &bdquo;ОблСнаб&ldquo;

рассказы "Сердце", "Эвакуация", "Майский жук".
рассказы &laquo;Сердце&raquo;, &laquo;Эвакуация&raquo;, &laquo;Майский жук&raquo;.

абырвалг: "АААААААБ ЫЫЫРРР "ээээ" алг!" фывфыв
абырвалг: &laquo;АААААААБ ЫЫЫРРР &bdquo;ээээ&ldquo; алг!&raquo; фывфыв

("слово")
(&laquo;слово&raquo;)

<div>asd</div><div>"test"</div>asd
<div>asd</div><div>&laquo;test&raquo;</div>asd

Неконвертируются &quot;quote&quot; кавычки в елочки, а должны.
Неконвертируются &laquo;quote&raquo; кавычки в&nbsp;елочки, а&nbsp;должны.

Неконвертируются &laquo;всякие&laquo; кавычки
Неконвертируются &laquo;всякие&raquo; кавычки

&laquo;&laquo;кавычки&raquo;&raquo;
&laquo;&bdquo;кавычки&ldquo;&raquo;

"слово "слово"!"
&laquo;слово &bdquo;слово&ldquo;!&raquo;

<b>"слово"</b> <b>"слово"</b>
<b>&laquo;слово&raquo;</b> <b>&laquo;слово&raquo;</b>

&bdquo;Клиника ФГУ &laquo;КРЭП&raquo; согласно Лицензии&ldquo;
&laquo;Клиника ФГУ&nbsp;&bdquo;КРЭП&ldquo; согласно Лицензии&raquo;
EOF;
}
