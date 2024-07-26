<?php

namespace App\Service;

class Censurator
{

    public function purify($string)
    {
        $motsACensurer = ['merde', 'connasse', 'connard', 'salop', 'salope', 'pute'];

        foreach ($motsACensurer as $mot) {
            if (stripos($string, $mot) !== false) { //
                $censoredWord = str_repeat('*', strlen($mot));
                $string = str_ireplace($mot, $censoredWord, $string);
            }
        }

        return $string;
    }

}