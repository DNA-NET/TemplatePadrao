<?php



/**
 * Transforma todos caracteres com glifos para seu equivalente sem glifo.
 * Caracteres que não forem letras ou não ocidentais serão convertidos em _
 *
 * @param {String}           $str                    String original.
 *
 * @return {String}
 */
function ReplaceGlyphs($str)
{
    $rem = mb_str_split("ÄÅÁÂÀÃäáâàãÉÊËÈéêëèÍÎÏÌíîïìÖÓÔÒÕöóôòõÜÚÛüúûùÇç");
    $sub = mb_str_split("AAAAAAaaaaaEEEEeeeeIIIIiiiiOOOOOoooooUUUuuuuCc");

    for ($i = 0; $i < count($rem); $i++) {
        $str = mb_str_replace($rem[$i], $sub[$i], $str);
    }

    $num = mb_str_split("0123456789");
    $upp = mb_str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
    $low = mb_str_split("abcdefghijklmnopqrstuvwxyz");

    $str = mb_str_replace(" ", "_", $str);

    return $str;
}