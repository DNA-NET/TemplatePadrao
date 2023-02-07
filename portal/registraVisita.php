<?php
$jsonData = file_get_contents("php://input");

if ($jsonData !== "") {
    $jsonData = json_decode($jsonData, true);

    if (
        key_exists("url", $jsonData) === true &&
        key_exists("displayInfo", $jsonData) === true
    ) {
        require_once('../Manager/conexao.php');
        require_once('../Manager/ado.php');
        require_once('../Manager/Atualiza.php');

        Atualiza_Visita(session_id(), $jsonData);
    }
}
