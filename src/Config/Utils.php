<?php
namespace App\Config;


class Utils {
    public static function getStars(float $note) : string {
        $res = '';
        for ($i = 0; $i < $note; $i++) {
            $res .= '★';
        }

        if ($note - (int) $note >= 0.5) {
            $res .= '⯪';
        }

        for ($i = mb_strlen($res); $i < 5; $i++) {
            $res .= '☆';
        }

        return $res;
    }
}