<?php



function formataEnter($variavel)
{
    $variavel = trim($variavel);
    $variavel = str_replace("\r", "", $variavel);
    $variavel = str_replace("\n", "", $variavel);
    $variavel = str_replace("\r\n", "", $variavel);
    $variavel = str_replace("\t", "", $variavel);
    $variavel = preg_replace("/(<br.*?>)/i", "", $variavel);
return $variavel;
}