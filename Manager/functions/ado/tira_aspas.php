<?php



function tira_aspas($s)
{
    return str_replace(Chr(39), "&#039;", str_replace(Chr(34), "&quot;", $s));
}