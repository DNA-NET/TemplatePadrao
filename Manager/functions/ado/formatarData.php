<?php



function formatarData($data)
{
    $rData = implode("-", array_reverse(explode("/", trim($data))));
    return $rData;
}