<?php

namespace Test;

use DJEM\Typograph\Typograph;

class NoWrapTest extends \PHPUnit_Framework_TestCase
{
    public function testNoWrap()
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
О компании
<nobr>О компании</nobr>

кое-как
<nobr>кое-как</nobr>

Ну, я не знаю.
<nobr>Ну, я не знаю</nobr>.

ну вот и всё
<nobr>ну вот и всё</nobr>

Лолики ну вот и всё
Лолики <nobr>ну вот и всё</nobr>

Лолики. ну вот и всё
Лолики. <nobr>ну вот и всё</nobr>

<nobr>О компании</nobr>
<nobr>О компании</nobr>

Сегодня будет создан
Сегодня будет создан

закон "Сегодня охране".
закон &laquo;Сегодня охране&raquo;.

сочетание "кавычек"
сочетание &laquo;кавычек&raquo;

"Европа-Азия"
&laquo;Европа-Азия&raquo;

"номер ICQ 123"
&laquo;номер <nobr>ICQ 123&raquo;</nobr>

"c:\www\sites\"
&laquo;<nobr>c:\www\sites</nobr>\&raquo;

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
Ответчик. &laquo;<nobr>бла бла&raquo; вап. Бла &laquo;ввв&raquo;</nobr>

"Приветствуем Вас"
&laquo;Приветствуем Вас&raquo;

Она добавила: "самый любимый "эсмеральда"".
<nobr>Она добавила</nobr>: &laquo;самый любимый &bdquo;эсмеральда&ldquo;&raquo;.

"Фирма "Терминал", "ОблСнаб"
&laquo;Фирма &bdquo;Терминал&ldquo;, &bdquo;ОблСнаб&ldquo;

рассказы "Сердце", "Эвакуация", "Майский жук".
рассказы &laquo;Сердце&raquo;, &laquo;Эвакуация&raquo;, &laquo;Майский жук&raquo;.

абырвалг: "АААААААБ ЫЫЫРРР "ээээ" алг!" фывфыв
абырвалг: &laquo;АААААААБ ЫЫЫРРР &bdquo;ээээ&ldquo; <nobr>алг!&raquo; фывфыв</nobr>

("слово")
(&laquo;слово&raquo;)

<div>asd</div><div>"test"</div>asd
<div>asd</div><div>&laquo;test&raquo;</div>asd

Неконвертируются &quot;quote&quot; кавычки в елочки, а должны.
Неконвертируются &laquo;quote&raquo; кавычки <nobr>в елочки</nobr>, <nobr>а должны</nobr>.

Неконвертируются &laquo;всякие&laquo; кавычки
Неконвертируются &laquo;всякие&raquo; кавычки

&laquo;&laquo;кавычки&raquo;&raquo;
&laquo;&bdquo;кавычки&ldquo;&raquo;

"слово "слово"!"
&laquo;слово &bdquo;слово&ldquo;!&raquo;

<b>"слово"</b> <b>"слово"</b>
<b>&laquo;слово&raquo;</b> <b>&laquo;слово&raquo;</b>

&bdquo;Клиника ФГУ &laquo;КРЭП&raquo; согласно Лицензии&ldquo;
&laquo;Клиника <nobr>ФГУ &bdquo;КРЭП&ldquo;</nobr> согласно Лицензии&raquo;
EOF;
}
