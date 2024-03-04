<?php

namespace frontend\components;

class Service
{
    //    public static function formatPrice($price): string
    public static function formatPrice($price)
    {
        $decimals = 2;
        if ($price - floor($price) == 0) {
            $decimals = 0;
        }
        return number_format($price, $decimals, '.', ' ') . ' ₽';
    }

//    public static function formatWeight($weight): string
    public static function formatWeight($weight)
    {
        return $weight . ' г.';
    }

    //string split
    public static function strSplit($str)
    {
        $result = '';
        $str_words = preg_split('/ /', $str, -1, PREG_SPLIT_NO_EMPTY);
        $i = 0;

        $result .= '<div class="anim_title">';

        foreach ($str_words as $word) {
            $result .= '<div class="word">';
            $signs = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($signs as $sign) {
                $result .= '<span class="sign" style="transition-delay: ' . $i . 's;">' . $sign . '</span>';
                $i = $i + 0.05;
            }
            $result .= '<span class="sign">&nbsp</span>';
            $result .= '</div>';
        }
        $result .= '</div>';

        return $result;
    }

}