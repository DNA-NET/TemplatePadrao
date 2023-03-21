<?php



/**
 * Encapsula as funções "_fetch_assoc".
 *
 * @param {ObjResult}            $result             Resultado de uma consulta.
 *
 * @return {Integer}
 */
function db_fetch_assoc($result)
{
    global $useCon;
    if ($result === NULL || $result === FALSE) {
        return NULL;
    }

    switch ($useCon["DataBase"]) {
        case "mysql":
            return mysqli_fetch_assoc($result);
            break;

        case "sql_server":
            $r = NULL;

            // Simulação do método "_fetch_assoc", pois o mesmo é
            // inexistente para a DLL "sqlsrv"
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                $r = $row;
                break;
            }
            return $r;

            break;
    }
}