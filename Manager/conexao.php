<?php

// Seta amostragem de erros na tela.
\ini_set("display_errors", 1);
// Seta report para todos os erros.
\error_reporting(E_ALL);


if (is_file("../../vendor/autoload.php") === true) {
    require "../../vendor/autoload.php";
} elseif (is_file("../vendor/autoload.php") === true) {
    require "../vendor/autoload.php";
}

// Inicia a sessão
if (!isset($_SESSION)) {
    $redisServer = [
        "host" => getenv("REDIS_IP"),
        "port" => (int)getenv("REDIS_PORT"),
        "database" => 0,
        "password" => getenv("REDIS_PASSWORD")
    ];


    // Instantiate a new client just like you would normally do. Using a prefix for
    // keys will effectively prefix all session keys with the specified string.
    $redisClient = new Predis\Client($redisServer, ["prefix" => "sessions:"]);

    // Set `gc_maxlifetime` to specify a time-to-live of 5 seconds for session keys.
    $redisHandler = new Predis\Session\Handler($redisClient, ["gc_maxlifetime" => (int)getenv("PHP_SESSION_MAX_TIME_LIFE")]);

    // Register the session handler.
    $redisHandler->register();

    session_start();
}



include_once "config.php";

if ($Exibir_erros == "sim") {
    // Seta amostragem de erros na tela.
    ini_set("display_errors", 1);

    // Seta report para todos os erros.
    error_reporting(E_ALL);
}

// Seta DateTime para o Brasil
date_default_timezone_set("America/Sao_Paulo");

// CONEXÃO INICIAL do Banco de Dados
$_SESSION["Dominio_url_producao"] = $Dominio_Pasta_site;
$_SESSION["Dominio_banco_dados"] = "mysql";

$conData = str_replace(" ", "", $Dominio_conexao);
$conData = explode(",", $conData);

if (count($conData) === 4) {
    $useCon["db_host"] = $conData[0];
    $useCon["db_username"] = $conData[1];
    $useCon["db_password"] = $conData[2];
    $useCon["db_basename"] = $conData[3];

    $useCon["DataBase"] = strtolower($_SESSION["Dominio_banco_dados"]);
}

$servidor_email = $Dominio_SMTP;

// Nao deve exigir login se a pagina for o portal
$temp = strlen(stristr($_SERVER["PHP_SELF"], "Manager"));
if ($temp > 0) {
    // Verifica se o usuário está logado... se não estiver redireciona-o à tela de login
    $acao = isset($_REQUEST["acao"])  ? $_REQUEST["acao"] : "";

    if ((!isset($_SESSION["Funcionarios_id"]) || $_SESSION["Funcionarios_id"] == "") &&
        (strtolower($_SERVER["PHP_SELF"]) != $Dominio_Pasta . "/manager/login.php") &&
        (strtolower($_SERVER["PHP_SELF"]) != $Dominio_Pasta . "/manager/show_image.php") &&
        (strtolower($_SERVER["PHP_SELF"]) != $Dominio_Pasta . "/manager/docs/wiki.php") &&
        $acao != "logon"
    ) {
        header("Location:" . $Dominio_Pasta . "/Manager/Login.php");
        exit();
    }
}


// Se no objeto session há uma definição de banco de dados a ser usado
if (isset($_SESSION["bd_conn"]) && isset($_SESSION["Dominio_banco_dados"])) {

    // Devem haver exatos 4 valores definidos.
    $conData = str_replace(" ", "", $_SESSION["bd_conn"]);
    $conData = explode(",", $conData);

    if (count($conData) === 4) {
        $useCon["db_host"] = $conData[0];
        $useCon["db_username"] = $conData[1];
        $useCon["db_password"] = $conData[2];
        $useCon["db_basename"] = $conData[3];

        $useCon["DataBase"] = strtolower($_SESSION["Dominio_banco_dados"]);
    }
}





if (!function_exists("sp_retrieve_pessoal")) {
    /**
     * Resgata o resultado da consulta de pessoal do centro informado.
     *
     * @param {String}       $siglaCentro            Sigla do centro.
     *
     * @return {!ResultSet}
     */
    function sp_retrieve_pessoal($siglaCentro)
    {
        global $odbcCon;
        $result = NULL;

        $connect = odbc_connect($odbcCon["NameConnection"], $odbcCon["User"], $odbcCon["Password"]) or die("couldn't connect");

        if ($connect) {
            $result = odbc_exec($connect, "EXEC P0100_Site_Pessoal_Consulta '$siglaCentro', 'internet'");

            // Mantem o resultado NULL para casos onde nenhum item foi retornado.
            if (odbc_num_rows($result) == 0) {
                $result == NULL;
            }
        }

        return $result;
    }
}

if (!function_exists("db_get_connection")) {
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
        switch ($useCon["DataBase"]) {
            case "mysql":
                //echo ($useCon["db_host"] . $useCon["db_username"] . $useCon["db_password"]. $useCon["db_basename"]);
                $con = mysqli_connect($useCon["db_host"], $useCon["db_username"], $useCon["db_password"], $useCon["db_basename"]);
                $con->set_charset("utf8");
                break;

            case "MySql":
                //echo ($useCon["db_host"] . $useCon["db_username"] . $useCon["db_password"]. $useCon["db_basename"]);
                $con = mysqli_connect($useCon["db_host"], $useCon["db_username"], $useCon["db_password"], $useCon["db_basename"]);
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


        if ($con === NULL) {
            throw new Exception("Failed to connect to " . $useCon["DataBase"]);
        }

        return $con;
    }
}

if (!function_exists("db_query")) {
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
}

if (!function_exists("db_num_rows")) {
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
}

if (!function_exists("db_fetch_assoc")) {
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
}

if (!function_exists("db_fetch_array")) {
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
}

if (!function_exists("db_count_regs")) {
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
}

if (!function_exists("db_error")) {
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
}

if (!function_exists("db_real_escape_string")) {
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
}

if (!function_exists("db_escape_string")) {
    /**
     * Função que serve para substituir chamadas "inline" de uma função "_real_escape_string"
     * este método não faz o tratamento adequado mas permite manter a compatibilidade com a forma como o
     * código foi originalmente escrito.
     * Não deve ser usado para proteger de Sql Injection.
     * Para renovar o código previnindo contra Sql Injection todas as chamadas para uso do banco de dados
     * devem ser alteradas por versões que utilizam parametros e não o código direto.
     *
     * @param {String}               $escapestr          Valor que será tratado.
     *
     * @return {String}
     */
    function db_escape_string($escapestr)
    {
        if (is_numeric($escapestr)) {
            return $escapestr;
        }

        $unpacked = unpack("H*hex", $escapestr);
        return "0x" . $unpacked["hex"];
    }
}

if (!function_exists("db_make_pagination_query")) {
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
                $baseQuery = str_replace("SELECT ", "SELECT Row_Number() OVER (" . $orderBy . ") AS RowIndex, ", $baseQuery, $n);
                $strSQL = "SELECT * FROM
                               (" . $baseQuery . ") AS Sub
                           WHERE
                               Sub.RowIndex >= " . $start . " and Sub.RowIndex < " . ($start + $limit);
                break;
        }


        return $strSQL;
    }
}

if (!function_exists("db_field_to_date")) {
    /**
     * Converte o campo do banco de dados passado em uma string em formato de data.
     *
     * @param {String}               $format             Formato que a data deve ter.
     * @param {String}               $field              Consulta SQL que será feita.
     *
     * @return {String}
     */
    function db_field_to_date($format, $field)
    {
        global $useCon;


        switch ($useCon["DataBase"]) {
            case "mysql":
                return date($format, strtotime((string)$field));
                break;

            case "sql_server":
                return $field->format("d/m/Y");
                break;
        }


        return "";
    }
}


/**
 * Conexão padrão.
 */
$con = db_get_connection();



// Se o usuário desejar fazer logout, encerra sua sessão e o envia para o início da aplicação
$logout = (isset($_REQUEST["logout"])) ? strtolower($_REQUEST["logout"]) : NULL;
if ($logout == "s" || $logout == "sim") {

    //Atualiza tempo de seção do Usuário
    if (isset($con)) {
        $DataDaAcao = new DateTime();
        $DataDaAcao = $DataDaAcao->format("Y-m-d H:i:s");
        db_query($con, "update log_acesso set log_acesso_data_logout = '$DataDaAcao' where log_acesso_id = " . $_SESSION["log_acesso_id"]);
    }

    session_unset();
    session_destroy();

    header("Location:Login.php?mensagem=Logout realizado com sucesso!");
    exit();
}
