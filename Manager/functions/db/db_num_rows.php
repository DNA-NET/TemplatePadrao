<?php



/**
 * Encapsula as funções "_num_rows".
 *
 * @param {ObjResult}            $result             Resultado de uma consulta.
 *
 * @return {Integer}
 */
function db_num_rows($result)
{
    global $useCon;
    if ($result === NULL || $result === FALSE) {
        return 0;
    }

    switch ($useCon["DataBase"]) {
        case "mysql":
            return mysqli_num_rows($result);
            break;

        case "sql_server":
            return sqlsrv_num_rows($result);
            break;
    }
}