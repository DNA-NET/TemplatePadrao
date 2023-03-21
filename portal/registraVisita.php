<?php
$jsonData = file_get_contents("php://input");

if ($jsonData !== "") {
    $jsonData = json_decode($jsonData, true);

    if (
        key_exists("url", $jsonData) === true &&
        key_exists("displayInfo", $jsonData) === true
    ) {
        require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
        Atualiza_Visita(
            session_id(),
            $jsonData
        );
    }
}