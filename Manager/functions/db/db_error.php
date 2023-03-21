<?php



/**
 * Encapsula as funções "_error".
 *
 * @return {Exception}
 */
function db_error()
{
    global $useCon;
    global $con;

    switch ($useCon["DataBase"]) {
        case "mysql":
            mysqli_error($con);
            break;

        case "sql_server":
            sqlsrv_errors($con);
            break;
    }
}