<?php



/**
 * Gera uma conexão baseada nas configurações iniciais ou, no set encontrado
 * na variável de seção.
 *
 * @return {Object}
 */
function db_get_connection()
{
    $con = NULL;
    global $useCon;

    // Conforme o banco de dados que será utilizado
    switch (strtolower($useCon["DataBase"])) {
        case "mysql":
            $con = mysqli_connect(
                $useCon["db_host"],
                $useCon["db_username"],
                $useCon["db_password"],
                $useCon["db_basename"]
            );
            $con->set_charset("utf8");
            break;

        case "sql_server":
            $conInfo = array(
                "Database" => $useCon["db_basename"],
                "UID" => $useCon["db_username"],
                "PWD" => $useCon["db_password"],
                "CharacterSet" => "UTF-8"
            );

            $con = sqlsrv_connect($useCon["db_host"], $conInfo);
            break;
    }

    return $con;
}