<?php



function acumula_erro($erro)
{
    try {
        $_SESSION["erro"] = $_SESSION["erro"] . "<br>" . $erro;
    } catch (Exception $e) {
        $_SESSION["erro"] .= "Problemas com a rotina de erros - " . $e->getMessage();
    }

    echo $_SESSION["erro"];
}