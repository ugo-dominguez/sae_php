<?php
namespace App\Config;


class Utils {
    public static function getStars(float $note) : string {
        $res = '';
        
        $fullStars = (int) $note;
        $halfStar = ($note - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - ($fullStars + $halfStar);

        $res .= str_repeat('★', $fullStars);
        
        if ($halfStar) {
            $res .= '⯪';
        }
        
        $res .= str_repeat('☆', $emptyStars);
        
        return $res;
    }
}
