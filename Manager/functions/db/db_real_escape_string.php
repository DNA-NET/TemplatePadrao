<?php



/**
 * Encapsula as funções "db_real_escape_string".
 *
 * @param {Connection}           $link               Objeto de conexão que será utilizado.
 * @param {String}               $escapestr          Valor que será tratado.
 *
 * @return {String}
 */
function db_real_escape_string($link, $escapestr)
{
    global $useCon;

    switch ($useCon["DataBase"]) {
        case "mysql":
            return mysqli_real_escape_string($link, $escapestr);
            break;

        case "sql_server":
            // Não há no driver do "sqlsrv" uma função analoga à "mysqli_real_escape_string".
            // Para fazer a adaptação no código para o uso de parametros (que é a forma correta de proteger
            // contra SQL Injection e outros problemas) exige refatorar todos os pontos onde o banco
            // é usado. Por hora, optou-se por utilizar um método // que apenas trata os caracteres "maliciosos".
            return sqlsrv_real_escape_string();
            break;
    }
}