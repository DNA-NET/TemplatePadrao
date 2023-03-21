<?php



/**
 * Encapsula as funções "_fetch_array".
 *
 * @param {ObjResult}            $result             Resultado de uma consulta.
 *
 * @return {Integer}
 */
function db_fetch_array($result)
{
    global $useCon;
    if ($result === NULL || $result === FALSE) {
        return NULL;
    }

    switch ($useCon["DataBase"]) {
        case "mysql":
            return mysqli_fetch_array($result);
            break;

        case "sql_server":
            if ($result === NULL || $result === FALSE) {
                return NULL;
            }
            return sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            break;
    }
}