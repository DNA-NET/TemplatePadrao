<?php



/**
 * Executa uma consulta no banco de dados do tipo "COUNT" e retorna seu resultado
 * SELECT COUNT(Id) FROM TableName;
 *
 * @param {Connection}           $link               Objeto de conexão que será utilizado.
 * @param {String}               $query              Consulta SQL que será feita.
 *
 * @return {Integer}
 */
function db_count_regs($link, $query)
{
    global $useCon;

    $result = db_query($link, $query);
    $row = db_fetch_array($result);
    return $row[0];
}