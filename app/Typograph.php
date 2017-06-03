<?php

namespace DJEM\Typograph;

class Typograph
{
    private $state = 'Text';

    private $string = [];
    private $index = 0;
    private $length = 0;
    private $map = [];

    private $word = 0;
    private $quotesLevel = 0;
    private $htmlTagStart = -1;

    private $maxSmallWordLetters = 3;

    private $replace = [
        'OpenQuote'     => '&laquo;',
        'CloseQuote'    => '&raquo;',
        'OpenSubQuote'  => '&bdquo;',
        'CloseSubQuote' => '&ldquo;',
        'Mdash'         => '&mdash;',
        'Nbsp'          => '&nbsp;',
        'Ignore'        => '',
    ];

    private function __construct()
    {
    }

    private function setState($state)
    {
        $this->state = $state;
    }

    private function getState()
    {
        return $this->state;
    }

    private function action($command, $index = false)
    {
        $index = ($index === false) ? $this->index : $index;

        if (! isset($this->map[$index])) {
            $this->map[$index] = [];
        }
        $this->map[$index] += $command;
    }

    private function next($increment = 1, $length = 1)
    {
        $result = '';
        $length = min($length, count($this->string) - $this->index - $increment);
        while ($this->index + $increment < $this->length && $length-- > 0) {
            $result .= $this->string[$this->index + $increment++];
        }

        return $result;
    }

    private function isSpace($letter)
    {
        return preg_match('/[[:space:]]/u', $letter) || $letter == '';
    }

    private function isPunct($letter)
    {
        return preg_match('/[[:punct:]]/u', $letter);
    }

    private function isWord($words)
    {
        foreach ($words as $word) {
            if ($word == $this->next(0, strlen($word))) {
                return strlen($word);
            }
        }

        return false;
    }

    private function beginHtml($letter)
    {
        $letter;

        $length = 1;
        $wordLength = 0;
        $this->htmlTagStart = $this->index;

        if ($wordLength = $this->isWord(['<!--'])) {
            $length = $wordLength;
            $this->setState('Comment');
        } elseif ($wordLength = $this->isWord(['<script'])) {
            $length = $wordLength;
            $this->setState('Script');
        } elseif ($wordLength = $this->isWord(['<nobr'])) {
            $length = $wordLength;
            $this->setState('Nobr');
        } elseif ($this->isWord(['</nobr'])) {
            $length = $wordLength;
            $this->setState('Nobr');
        } else {
            $this->setState('Html');
        }

        return $length;
    }

    private function parseNobr($letter)
    {
        // внутри nobr не разбираем атрибуты
        $length = 1;
        if ($letter == '>') {
            $this->action(['Ignore' => $this->index - $this->htmlTagStart + 1], $this->htmlTagStart);
            $this->setState('Text');
        }

        return $length;
    }

    private function parseHtml($letter)
    {
        $length = 1;
        if ($letter == '"') {
            $this->setState('HtmlQuote');
        } elseif ($letter == '\'') {
            $this->setState('HtmlSingleQuote');
        } elseif ($letter == '>') {
            $this->setState('Text');
        }

        return $length;
    }

    private function parseHtmlQuote($letter)
    {
        $length = 1;
        if ($letter == '"') {
            $this->setState('Html');
        }

        return $length;
    }

    private function parseHtmlSingleQuote($letter)
    {
        $length = 1;
        if ($letter == '\'') {
            $this->setState('Html');
        }

        return $length;
    }

    private function parseComment($letter)
    {
        $length = 1;
        $wordLength = 0;
        if ($letter == '-' && ($wordLength = $this->isWord(['-->']))) {
            $length = $wordLength;
            $this->setState('Text');
        }

        return $length;
    }

    private function parseScript($letter)
    {
        $length = 1;
        $wordLength = 0;
        if ($letter == '<' && ($wordLength = $this->isWord(['</script']))) {
            $length = $wordLength;
            $this->setState('Html');
        }

        return $length;
    }

    private function parseAmpEntity($letter)
    {
        $length = 1;
        if ($letter == ';') {
            $this->setState('Text');
        }

        return $length;
    }

    private function processQuotes($length)
    {
        $next = $this->next($length);

        if ((! $this->isSpace($next) && ! $this->isPunct($next)) || ($this->isPunct($next) && $this->word == 0)) {
            $this->action(($this->quotesLevel == 0) ? ['OpenQuote' => $length] : ['OpenSubQuote' => $length]);
            ++$this->quotesLevel;
        } else {
            --$this->quotesLevel;
            $this->action(($this->quotesLevel == 0) ? ['CloseQuote' => $length] : ['CloseSubQuote' => $length]);
        }
    }

    private function processNobr($letter)
    {
        $wordLength = 1;
        if ($this->isSpace($letter) || ($letter == '&' && $wordLength = $this->isWord(['&nbsp;']))) {
            if ($this->word && $this->word <= $this->maxSmallWordLetters) {
                $this->action(['Nbsp' => $wordLength]);
            } elseif ($this->next() == '-') {
                $this->action(['Nbsp' => $wordLength]);
            } else {
                $dash = $this->next(1, 7);
                if ($dash == '&mdash;' || $dash == '&ndash;') {
                    $this->action(['Nbsp' => $wordLength]);
                }
            }
        }
        $this->word = 0;

        return $wordLength;
    }

    private function parseText($letter)
    {
        $length = 1;
        $wordLength = 0;

        if ($letter == '<') {
            return $this->beginHtml($letter);
        } elseif ($this->isSpace($letter) || ($letter == '&' && $this->isWord(['&nbsp;']))) {
            return $this->processNobr($letter);
        } elseif ($letter == '"' ||
            $letter == '«' || $letter == '»' ||
            ($letter == '&' && $wordLength = $this->isWord(['&quot;', '&laquo;', '&raquo;', '&bdquo;', '&ldquo;']))
        ) {
            if ($wordLength) {
                $length = $wordLength;
            }
            $this->processQuotes($length);
        } elseif (($letter == '-' && $wordLength = $this->isWord(['- '])) ||
            $letter == '&' && $wordLength = $this->isWord(['&mdash; ', '&ndash; '])
        ) {
            $length = $wordLength - 1;
            $this->action(['Mdash' => $wordLength - 1]);
        } elseif ($letter == '&') {
            $this->setState('AmpEntity');
        } elseif ($this->isPunct($letter)) {
            return $this->processNobr($letter);
        } else {
            ++$this->word;
        }

        return $length;
    }

    private function run()
    {
        $this->map = [];

        $this->length = count($this->string);
        for ($this->index = 0; $this->index < $this->length;) {
            $letter = $this->string[$this->index];

            $this->index += call_user_func([$this, 'parse'.$this->getState()], $letter);
        }

        return $this->build();
    }

    public function build()
    {
        $result = '';
        for ($index = 0; $index <= $this->length;) {
            $step = 0;
            if (isset($this->map[$index])) {
                foreach ($this->map[$index] as $key => $value) {
                    $result .= $this->replace[$key];
                    $step = max($step, $value);
                }
                $index += $step;
            }
            if ($step == 0) {
                if ($index < $this->length) {
                    $result .= $this->string[$index];
                }
                ++$index;
            }
        }

        return $result;
    }

    public static function parse($string)
    {
        $typo = new self();
        $typo->string = preg_split('//u', $string, null, PREG_SPLIT_NO_EMPTY);

        return $typo->run();
    }
}
