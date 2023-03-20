<?php
$jsonData = file_get_contents("php://input");

if ($jsonData !== "") {
    $jsonData = json_decode($jsonData, true);

    if (
        key_exists("url", $jsonData) === true &&
        key_exists("displayInfo", $jsonData) === true
    ) {
        require_once('../Manager/conexao.php');
        require_once $_SERVER["DOCUMENT_ROOT"] . getenv("ATUALIZA_MANAGER_DIR") . "/Manager/functions/ado.php";
        require_once $_SERVER["DOCUMENT_ROOT"] . getenv("ATUALIZA_MANAGER_DIR") . "/Manager/functions/atualiza.php";

        Atualiza_Visita(session_id(), $jsonData);
    }
}
