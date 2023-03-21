<?php



function encodeHTML($sHTML)
{
    $sHTML = preg_replace('/&/i', '&amp;', $sHTML);
    $sHTML = preg_replace('/</i', '&lt;', $sHTML);
    $sHTML = preg_replace('/>/i', '&gt;', $sHTML);
    return $sHTML;
}