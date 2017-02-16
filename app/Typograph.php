<?php

namespace DJEM\Typograph;

class Typograph
{
    private $state = 'Text';

    private $string = [];
    private $index = 0;
    private $length = 0;
    private $map = [];


    private $word               = 0;
    private $smallWordPosition  = -1;
    private $smallWordsCount    = 0;
    private $quotesLevel        = 0;
    private $htmlTagStart       = -1;
    private $lastNobrIndex    = false;

    private $replace = [
      'OpenQuote'       => '&laquo;',
      'CloseQuote'      => '&raquo;',
      'OpenSubQuote'    => '&bdquo;',
      'CloseSubQuote'   => '&ldquo;',
      'OpenNobr'        => '<nobr>',
      'CloseNobr'       => '</nobr>',
      'Ignore'          => ''
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

        if (!isset($this->map[$index])) {
            $this->map[$index] = [];
        }
        $this->map[$index] += $command;
    }

    private function next($increment = 1, $length = 1)
    {
        $result = '';
        while ($this->index + $increment < $this->length && $length-- > 0) {
            $result .= $this->string[$this->index + $increment++];
        }
        return $result;
    }

    private function isSpace($letter)
    {
        return preg_match('/[[:space:]]/', $letter) || $letter == '';
    }

    private function isPunct($letter)
    {
        return preg_match('/[[:punct:]]/', $letter);
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

        if ($wordLength = $this->isWord([ '<!--' ])) {
            $length = $wordLength;
            $this->setState('Comment');
        } elseif ($wordLength = $this->isWord([ '<script' ])) {
            $length = $wordLength;
            $this->setState('Script');
        } elseif ($wordLength = $this->isWord([ '<nobr' ])) {
            $length = $wordLength;
            $this->setState('Nobr');
        } elseif ($this->isWord([ '</nobr' ])) {
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
            $this->action([ 'Ignore' => $this->index - $this->htmlTagStart + 1 ], $this->htmlTagStart);
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
        if ($letter == '-' && ($wordLength = $this->isWord([ '-->' ]))) {
            $length = $wordLength;
            $this->setState('Text');
        }
        return $length;
    }

    private function parseScript($letter)
    {
        $length = 1;
        $wordLength = 0;
        if ($letter == '<' && ($wordLength = $this->isWord([ '</script' ]))) {
            $length = $wordLength;
            $this->setState('Html');
        }
        return $length;
    }

    private function processQuotes($length)
    {
        $next = $this->next($length);

        if ((!$this->isSpace($next) && !$this->isPunct($next)) || ($this->isPunct($next) && $this->word == 0)) {
            $this->action(($this->quotesLevel == 0) ? [ 'OpenQuote' => $length ] : [ 'OpenSubQuote' => $length ]);
            ++$this->quotesLevel;
        } else {
            --$this->quotesLevel;
            $this->action(($this->quotesLevel == 0) ? [ 'CloseQuote' => $length ] : [ 'CloseSubQuote' => $length ]);
        }
    }

    private function processNobr($last = false)
    {
        static $maxLetters = 6;

        if ($last && $this->lastNobrIndex + 1 >= $this->length) {
            // проверка, чтобы не запускать повторно анализатор, если он уже был вызван на последнем символе
            return;
        }
        $this->lastNobrIndex = $this->index;

        // print implode($this->string).' --> '.$this->smallWordPosition.' -- '.$this->smallWordsCount.' --word: '.$this->word."\n";
        if ($this->smallWordsCount > 0 && ($last || $this->word > $maxLetters)) {
            if ($this->smallWordPosition >= 0) {
                $this->action([ 'OpenNobr' => 0 ], $this->smallWordPosition);
                $this->action([ 'CloseNobr' => 0 ]);
                $this->smallWordPosition = -1;
                $this->smallWordsCount = 0;
            }
        } else {
            if ($this->word > $maxLetters) {
                $this->smallWordPosition = -1;
                $this->smallWordsCount = 0;
            } elseif ($this->word > 0) {
                ++$this->smallWordsCount;
            }
        }
    }

    private function parseText($letter)
    {
        $length = 1;
        $wordLength = 0;

        if ($letter == '<') {
            return $this->beginHtml($letter);
        } elseif ($this->isSpace($letter)) {
            $this->processNobr();
            $this->word = 0;
        } elseif ($letter == '"' || ($letter == '&' && $wordLength = $this->isWord([ '&quot;', '&laquo;', '&raquo;', '&bdquo;', '&ldquo;' ]))) {
            if ($wordLength) {
                $length = $wordLength;
            }
            $this->processQuotes($length);
        } elseif ($this->isPunct($letter)) {
            $this->processNobr();
            $this->word = 0;
        } else {
            if ($this->word == 0 && $this->smallWordPosition == -1) {
                $this->smallWordPosition = $this->index;
            }
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

            $this->index += call_user_func([ $this, 'parse'.$this->getState() ], $letter);
        }
        $this->processNobr(true);
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
        $typo = new Typograph();
        $typo->string = str_split($string);
        return $typo->run();
    }
}
