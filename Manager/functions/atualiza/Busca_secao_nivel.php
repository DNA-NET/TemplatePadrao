<?php
//==================================================================================================================
// Retorna o ID da Seção de nível desejado
//==================================================================================================================
function Busca_secao_nivel($nivel)
{
    $niveis = "";
    $niveis_total = 0;

    try {

        if (isset($_SESSION["secao_dna"])) {
            $niveis = $_SESSION["secao_dna"] . "-" . $_SESSION["secao_id"];
            $niveis = substr($niveis, - (strlen($niveis) - 1));
            $niveis_array = explode("--", $niveis);
            $niveis_total = count($niveis_array);


            if ($niveis_total > $nivel) {
                return $niveis_array[$nivel];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    } catch (Exception $e) {
        $_SESSION["erro"] = ("Erro Busca_secao_nivel " . $nivel . "> " . $e->getMessage());
        return 0;
    }
}