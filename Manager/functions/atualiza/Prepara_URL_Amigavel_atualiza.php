<?php



function Prepara_URL_Amigavel_atualiza($url)
{
    $url_tratada = str_replace(",", "/", str_replace(">", "/", str_replace(" ", "-", trim($url))));
    return strtolower(preg_replace('/[`^~\'"]/', "", iconv('UTF-8', 'ASCII//TRANSLIT', $url_tratada)));
}