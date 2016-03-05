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
<nowrap>Ну, не знаю</nowrap>.

ну вот и всё
<nowrap>ну вот и всё</nowrap>

Лолики ну вот и всё
Лолики <nowrap>ну вот и всё</nowrap>

Лолики. ну вот и всё
Лолики. <nowrap>ну вот и всё</nowrap>

<nowrap>О компании</nowrap>
<nowrap>О компании</nowrap>

Сегодня будет создан
Сегодня будет создан

закон "Сегодня охране".
закон &laquo;Сегодня охране&raquo;.

сочетание "кавычек"
сочетание &laquo;кавычек&raquo;

"Европа-Азия"
&laquo;Европа-Азия&raquo;

"номер ICQ 123"
&laquo;номер <nowrap>ICQ 123&raquo;</nowrap>

"c:\www\sites\"
&laquo;<nowrap>c:\www\sites\&raquo;</nowrap>

"Справка 09"
&laquo;Справка 09&raquo;

"данные:"
&laquo;данные:&raquo;

"новый тариф*"
&laquo;новый тариф*&raquo;

"Star Flyer Inc."
&laquo;<nowrap>Star Flyer Inc.&raquo;</nowrap>

"слово", слово
&laquo;слово&raquo;, слово

слово, "слово"
слово, &laquo;слово&raquo;

Ответчик. "бла бла" вап. Бла "ввв"
Ответчик. &laquo;<nowrap>бла бла&raquo; вап. Бла &laquo;ввв&raquo;</nowrap>

"Приветствуем Вас"
&laquo;Приветствуем Вас&raquo;

Она добавила: "самый любимый "эсмеральда"".
<nowrap>Она добавила</nowrap>: &laquo;самый любимый &bdquo;эсмеральда&ldquo;&raquo;.

"Фирма "Терминал", "ОблСнаб"
&laquo;Фирма &bdquo;Терминал&ldquo;, &bdquo;ОблСнаб&ldquo;

рассказы "Сердце", "Эвакуация", "Майский жук".
рассказы &laquo;Сердце&raquo;, &laquo;Эвакуация&raquo;, &laquo;Майский жук&raquo;.

абырвалг: "АААААААБ ЫЫЫРРР "ээээ" алг!" фывфыв
абырвалг: &laquo;АААААААБ ЫЫЫРРР &bdquo;ээээ&ldquo; <nowrap>алг!&raquo; фывфыв</nowrap>

("слово")
(&laquo;слово&raquo;)

<div>asd</div><div>"test"</div>asd
<div>asd</div><div>&laquo;test&raquo;</div>asd

Неконвертируются &quot;quot&quot; кавычки велочки, адолжны.
Неконвертируются &laquo;<nowrap>quot&raquo; кавычки</nowrap> велочки, адолжны.

Неконвертируются &laquo;всякие&laquo; кавычки
Неконвертируются &laquo;всякие&raquo; кавычки

&laquo;&laquo;кавычки&raquo;&raquo;
&laquo;&bdquo;кавычки&ldquo;&raquo;

"слово "слово"!"
&laquo;слово &bdquo;слово&ldquo;!&raquo;

<b>"слово"</b> <b>"слово"</b>
<b>&laquo;слово&raquo;</b> <b>&laquo;слово&raquo;</b>

&bdquo;Клиника ФГУ &laquo;КРЭП&raquo; согласно Лицензии&ldquo;
&laquo;Клиника <nowrap>ФГУ &bdquo;КРЭП&ldquo;</nowrap> согласно Лицензии&raquo;
EOF;
}
