<?php



function Prepara_URL_Amigavel($url)
{
    $url_tratada = str_replace(",", "/", str_replace(">", "/", str_replace(" ", "-", trim($url))));
    setlocale(LC_CTYPE, 'pt_BR');
    return strtolower(preg_replace('/[`^~\'"]/', "", iconv('UTF-8', 'ASCII//TRANSLIT', $url_tratada)));
}