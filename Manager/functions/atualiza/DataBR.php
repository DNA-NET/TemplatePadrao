<?php



function DataBR($date)
{
    $date = str_replace("/", "-", substr($date, 0, 10));
    list($year, $month, $day) = explode('-', $date);
    if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) {
        return false;
    }
    if ($day > 1000) {
        $aux = $day;
        $day = $year;
        $year = $aux;
    }

    if (checkdate($month, $day, $year)) {
        return ($day . "/" . $month . "/" . $year);
    } else {
        return false;
    }
}