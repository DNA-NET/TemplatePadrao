<?php



foreach (\scandir(__DIR__ . "/db") as $function_file) {
    if (
        $function_file !== "." &&
        $function_file !== ".." &&
        \function_exists(\str_replace(".php", "", $function_file)) === false
    ) {
        require_once __DIR__ . "/db" . DIRECTORY_SEPARATOR . $function_file;
    }
}
unset($function_file);






// Inicia a conexão a ser usada
$useCon = [];
if ($Dominio_conexao !== "") {
    $conData = array_map("trim", explode(",", $Dominio_conexao));
    if (count($conData) === 4) {
        $useCon["db_host"]      = $conData[0];
        $useCon["db_username"]  = $conData[1];
        $useCon["db_password"]  = $conData[2];
        $useCon["db_basename"]  = $conData[3];
        $useCon["DataBase"] = strtolower($_SESSION["Dominio_banco_dados"]);

        try {
            $con = db_get_connection();
        } catch (Exception $ex) {
            $con = null;
        }
    }
}