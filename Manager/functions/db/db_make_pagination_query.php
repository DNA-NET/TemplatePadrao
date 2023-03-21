<?php



/**
 * A partir de uma consulta SQL e seus parametros de paginação, monta a
 * consulta conforme o banco de dados que está sendo utilizado.
 *
 * @param {String}               $baseQuery          Consulta SQL que será feita.
 * @param {String}               $orderBy            Instrução "order by".
 * @param {Integer}              $start              Indice da primeira linha a ser retornada.
 * @param {Integer}              $limit              Numero máximo de linhas a serem retornadas.
 *
 * @return {!Object}
 */
function db_make_pagination_query($baseQuery, $orderBy, $start, $limit)
{
    global $useCon;
    $strSQL = "";

    switch ($useCon["DataBase"]) {
        case "mysql":
            $strSQL = $baseQuery . " " . $orderBy . " LIMIT " . $start . "," . $limit;
            break;

        case "sql_server":
            $n = 1;
            $baseQuery = str_replace("SELECT ", "SELECT Row_Number() OVER ($orderBy) AS RowIndex, ", $baseQuery, $n);
            $strSQL = "SELECT * FROM
                            ($baseQuery) AS Sub
                        WHERE
                            Sub.RowIndex >= $start AND Sub.RowIndex < " . ($start + $limit);
            break;
    }


    return $strSQL;
}