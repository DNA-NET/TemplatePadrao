<?php



function Formata_nome($tabela, $campo, $clausula)
{
    global $con;
    $resultado = "";
    $RS_Campo = db_query($con, "SELECT $campo FROM $tabela WHERE $clausula");

    if (db_num_rows($RS_Campo)) {
        $row = db_fetch_assoc($RS_Campo);
        $resultado = $row[$campo];
    }

    return $resultado;
}