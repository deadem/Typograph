<?php

namespace DJEM;

class Typograph
{
    private $state = 'Text';

    private $string = [];
    private $index = 0;
    private $length = 0;
    private $map = [];


    private $word = 0;
    private $quotesLevel = 0;

    private function getOpenQuote()
    {
        return '&laquo;';
    }

    private function getCloseQuote()
    {
        return '&raquo;';
    }

    private function getOpenSubQuote()
    {
        return '&bdquo;';
    }

    private function getCloseSubQuote()
    {
        return '&ldquo;';
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
        return in_array($letter, [ '', ' ', "\n", "\t", "\r" ]);
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

    public function parseText($letter)
    {
        $length = 1;
        $wordLength = 0;

        if ($this->isSpace($letter)) {
            $this->word = 0;
        } elseif ($letter == '"' || $wordLength = $this->isWord([ '&quot;', '&laquo;', '&raquo;', '&bdquo;', '&ldquo;' ])) {
            if ($wordLength) {
                $length = $wordLength;
            }
            $next = $this->next($length);
            if ((!$this->isSpace($next) && !$this->isPunct($next)) || ($this->isPunct($next) && $this->word == 0)) {
                $this->map[$this->index] = ($this->quotesLevel == 0) ? [ 'OpenQuote' => $length ] : [ 'OpenSubQuote' => $length ];
                ++$this->quotesLevel;
            } else {
                --$this->quotesLevel;
                $this->map[$this->index] = ($this->quotesLevel == 0) ? [ 'CloseQuote' => $length ] : [ 'CloseSubQuote' => $length ];
            }
            ++$this->word;
        } elseif ($this->isPunct($letter)) {
            $this->word = 0;
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

            $this->index += call_user_func([ $this, 'parse'.$this->state ], $letter);
        }
        return $this->build();
    }

    public function build()
    {
        $result = '';
        for ($index = 0; $index < $this->length;) {
            if (!isset($this->map[$index])) {
                $result .= $this->string[$index++];
            } else {
                foreach ($this->map[$index] as $key => $value) {
                    $result .= call_user_func([ $this, 'get'.$key]);
                    $index += $value;
                }
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
