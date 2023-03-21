<?php



/**
 * Encapsula as funções "_query".
 *
 * @param {Connection}           $link               Objeto de conexão que será utilizado.
 * @param {String}               $query              Consulta SQL que será feita.
 *
 * @return {!Object}
 */
function db_query($link, $query, $mostraErro = 0)
{
    global $useCon;

    switch ($useCon["DataBase"]) {
        case "mysql":
            $ret = mysqli_query($link, $query);
            $err = mysqli_error($link);
            if ($err > "") {
                // MOSTRA O ERRO REAL, DO COMANDO SQL, ARNALDO 12/17
                if ($mostraErro > 0) {
                    echo $err . "<Br>";
                    if ($mostraErro > 1) {
                        echo "SQL: " . $query . "<Br>";
                    }
                }
            }
            return $ret;
            break;

        case "sql_server":
            $query = str_replace("CURDATE()", "GETDATE()", $query);
            return sqlsrv_query($link, $query, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
            break;
    }
}