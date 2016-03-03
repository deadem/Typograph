<?php

namespace Test;

use DJEM\Typograph;

class QuotesTest extends \PHPUNIT_Framework_Testcase
{
    public function testQuotes()
    {
        $values = preg_split('/\r?\n/', $this->text);
        do {
            do {
                $first = array_shift($values);
            } while ($first == '');
            $second = array_shift($values);

            $this->assertEquals($second, Typograph::parse($first));
        } while (!empty($values));
    }

    private $text = <<<EOF
Сегодня будет создан
Сегодня будет создан

закон "Сегодня охране".
закон &laquo;Сегодня охране&raquo;.

some code to "change". for real
some code to &laquo;change&raquo;. for real

сочетание "кавычек"
сочетание &laquo;кавычек&raquo;

button "English Pages".
button &laquo;English Pages&raquo;.

button "English Pages"?
button &laquo;English Pages&raquo;?

word "go out" doubleword
word &laquo;go out&raquo; doubleword

word "go out"
word &laquo;go out&raquo;

"go out!"
&laquo;go out!&raquo;

"go out?"
&laquo;go out?&raquo;

"go out."
&laquo;go out.&raquo;

123 "go out!"
123 &laquo;go out!&raquo;

123 "go out?"
123 &laquo;go out?&raquo;

123 "go out."
123 &laquo;go out.&raquo;

123 "go out!" 123
123 &laquo;go out!&raquo; 123

123 "go out?" 232
123 &laquo;go out?&raquo; 232

123 "go out." 321
123 &laquo;go out.&raquo; 321

"go out!" 123
&laquo;go out!&raquo; 123

"go out?" 232
&laquo;go out?&raquo; 232

"go out." 321
&laquo;go out.&raquo; 321

word "quoted" word
word &laquo;quoted&raquo; word

"quoted" word word
&laquo;quoted&raquo; word word

word word "quoted"
word word &laquo;quoted&raquo;

word "quo ted" word
word &laquo;quo ted&raquo; word

"quo ted" word word
&laquo;quo ted&raquo; word word

word word "quo ted"
word word &laquo;quo ted&raquo;

"Европа-Азия"
&laquo;Европа-Азия&raquo;

"ICQ #"
&laquo;ICQ #&raquo;

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

ОТветь. "бла бла" вап. Бла "ввв"
ОТветь. &laquo;бла бла&raquo; вап. Бла &laquo;ввв&raquo;


"Приветствуем Вас"
&laquo;Приветствуем Вас&raquo;

Она добавила: "самый любимый "эсмеральда"".
Она добавила: &laquo;самый любимый &bdquo;эсмеральда&ldquo;&raquo;.

"Фирма "Терминал", "ОблСнаб"
&laquo;Фирма &bdquo;Терминал&ldquo;, &bdquo;ОблСнаб&ldquo;

рассказы "Сердце", "Эвакуация", "Майский жук".
рассказы &laquo;Сердце&raquo;, &laquo;Эвакуация&raquo;, &laquo;Майский жук&raquo;.

абырвалг: "АААААААБ ЫЫЫРРР "ээээ" алг!" фывфыв
абырвалг: &laquo;АААААААБ ЫЫЫРРР &bdquo;ээээ&ldquo; алг!&raquo; фывфыв

"word "word" word"
&laquo;word &bdquo;word&ldquo; word&raquo;

("слово")
(&laquo;слово&raquo;)

asd "test", asd
asd &laquo;test&raquo;, asd

Неконвертируются &quot;quot&quot; кавычки велочки, адолжны.
Неконвертируются &laquo;quot&raquo; кавычки велочки, адолжны.

Неконвертируются &laquo;всякие&laquo; кавычки
Неконвертируются &laquo;всякие&raquo; кавычки

&laquo;&laquo;кавычки&raquo;&raquo;
&laquo;&bdquo;кавычки&ldquo;&raquo;

"слово "слово"!"
&laquo;слово &bdquo;слово&ldquo;!&raquo;

<b>"слово"</b> <b>"слово"</b>
<b>&laquo;слово&raquo;</b> <b>&laquo;слово&raquo;</b>

&bdquo;Клиника ФГУ &laquo;КРЭП&raquo; согласно Лицензии&ldquo;
&laquo;Клиника ФГУ &bdquo;КРЭП&ldquo; согласно Лицензии&raquo;
EOF;
}
