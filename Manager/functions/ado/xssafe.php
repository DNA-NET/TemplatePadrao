<?php



function xssafe($data, $encoding = 'UTF-8')
{
    return tira_aspas(htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding));
}