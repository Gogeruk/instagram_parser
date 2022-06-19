<?php

namespace App\Parser;


/**
 * Class ParsedDataProcessor
 * @package App\Service\Parser
 */
class ParsedDataProcessor
{
    /**
     * @param $string
     * @return array|string|string[]|null
     */
    public function removeEmojis
    (
        $string
    )
    {
        $symbols =
            "\x{1F100}-\x{1F1FF}" // Enclosed Alphanumeric Supplement
            ."\x{1F300}-\x{1F5FF}" // Miscellaneous Symbols and Pictographs
            ."\x{1F600}-\x{1F64F}" //Emoticons
            ."\x{1F680}-\x{1F6FF}" // Transport And Map Symbols
            ."\x{1F900}-\x{1F9FF}" // Supplemental Symbols and Pictographs
            ."\x{2600}-\x{26FF}" // Miscellaneous Symbols
            ."\x{2700}-\x{27BF}"// Dingbats
        ;

        return preg_replace('/['. $symbols . ']+/u', '', $string);
    }


    /**
     * @param string $string
     * @param int $len
     * @return string
     */
    public function getCorrectStrLen(string $string, int $len) : string
    {
        return mb_strlen($string) > $len
            ? mb_substr($string, 0, $len - 3)."..." : $string;
    }
}