<?php

namespace App\Helpers;

trait StringTrait
{
    public function substr($str, $s, $l = null) {
        return implode("", array_slice(preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
    }

    public function strtolower($str) {
        return mb_convert_case($str, MB_CASE_LOWER, "UTF-8");
    }

    public function strtoupper($str) {
        return mb_convert_case($str, MB_CASE_UPPER, "UTF-8");
    }

    public function substr_compare($str1, $str2, $i = 0) {
        for ($i; $i < strlen($str1); $i++) {
            if ($str1[$i] !== $str2[$i]) {
                return $i - 1;
            }
        }
        return 0;
    }
}
