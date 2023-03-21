<?php



/**
 * Convert a string to an array.
 *
 * @param mixed $string
 * @param mixed $split_length
 *
 * @return mixed
 */
function mb_str_split($string, $string_length = 1)
{
    $charset = "utf-8";
    $string_length = ($string_length <= 0) ? 1 : $string_length;

    if (mb_strlen($string, $charset) > $string_length) {
        do {
            $c = mb_strlen($string, $charset);
            $parts[] = mb_substr($string, 0, $string_length, $charset);
            $string = mb_substr($string, $string_length, $c - $string_length, $charset);
        } while (!empty($string));
    } else {
        $parts = array($string);
    }

    return $parts;
}