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

            $this->assertEquals(Typograph::parse($first), $second);
        } while ($first !== null);
    }

    private $text = <<<EOF
Сегодня будет создан
Сегодня будет создан

закон "Сегодня охране".
закон «Сегодня охране».

some code to "change". for real
some code to «change». for real

сочетание "кавычек"
сочетание «кавычек»

button "English Pages".
button «English Pages».

button "English Pages"?
button «English Pages»?

word "go out" doubleword
word «go out» doubleword

word "go out"
word «go out»

"go out!"
«go out!»

"go out?"
«go out?»

"go out."
«go out.»

123 "go out!"
123 «go out!»

123 "go out?"
123 «go out?»

123 "go out."
123 «go out.»

123 "go out!" 123
123 «go out!» 123

123 "go out?" 232
123 «go out?» 232

123 "go out." 321
123 «go out.» 321

"go out!" 123
«go out!» 123

"go out?" 232
«go out?» 232

"go out." 321
«go out.» 321

word "quoted" word
word «quoted» word

"quoted" word word
«quoted» word word

word word "quoted"
word word «quoted»

word "quo ted" word
word «quo ted» word

"quo ted" word word
«quo ted» word word

word word "quo ted"
word word «quo ted»

"Европа-Азия"
«Европа-Азия»

"ICQ #"
«ICQ #»

"c:\www\sites\"
«c:\www\sites\»

"Справка 09"
«Справка 09»

"данные:"
«данные:»

"новый тариф*"
«новый тариф*»

"Star Flyer Inc."
«Star Flyer Inc.»

"слово", слово
«слово», слово

слово, "слово"
слово, «слово»

ОТветь. "бла бла" вап. Бла "ввв"
ОТветь. «бла бла» вап. Бла «ввв»

„Клиника ФГУ «ФБМСЭ» согласно Лицензии“
«Клиника ФГУ „ФБМСЭ“ согласно Лицензии»

"Приветствуем Вас"
«Приветствуем Вас»

совместно с художником Рерихом
совместно с&nbsp;художником Рерихом

Она добавила: "самый любимый "эсмеральда"".
Она добавила: «самый любимый „эсмеральда“».

"Фирма "Терминал", "ОблСнабВротКомпот"
«Фирма «Терминал», «ОблСнабВротКомпот»

рассказы "Сердце", "Эвакуация", "Майский жук".
рассказы «Сердце», «Эвакуация», «Майский жук».

абырвалг: "АААААААБ ЫЫЫРРР "ээээ" алг!" фывфыв
абырвалг: «АААААААБ ЫЫЫРРР „ээээ“ алг!» фывфыв

"word "word" word"
«word „word“ word»

("слово")
(«слово»)

asd"test"asd
asd «test» asd

Неконвертируются &quot;quot&quot; кавычки велочки, адолжны.
Неконвертируются «quot» кавычки велочки, адолжны.

Неконвертируются «всякие« кавычки
Неконвертируются «всякие» кавычки

««кавычки»»
«„кавычки“»

"слово "слово"!"
«слово „слово“!»

<b>"слово"</b> <b>"слово"</b>  
<b>«слово»</b> <b>«слово»</b>

EOF;
}
