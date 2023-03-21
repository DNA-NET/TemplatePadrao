<?php
//==================================================================================================================
// Retorna o nome da Seção de nível desejado
//==================================================================================================================
function Busca_secao_nome2($nivel)
{
    $niveis = "";
    $niveis_total = "";

    try {
        $niveis = $_SESSION["navegacao_nome"];
        $niveis_array = explode(",", $niveis);
        $niveis_total = count($niveis_array);

        if ($niveis_total >= $nivel) {
            return $niveis_array($nivel);
        } else {
            return "";
        }
    } catch (Exception $e) {
        acumula_erro("Erro Busca_secao_nome " . $nivel . "> " . $e->getMessage());
        return 0;
    }
}