<?php
//=================================================================================================================
//      Função para montagem de menus dinâmicos até 5 níveis
//=================================================================================================================
function menu(
    $secao_id,
    $nivel1,
    $nivel2,
    $nivel3,
    $nivel4,
    $nivel5
) {

    global $con;

    $query = "";
    $CONTEUDO_TEMPLATE = "";
    $Sql = "";
    $i = "";
    $ii = "";
    $iii = "";
    $iiii = "";
    $iiiii = "";

    $CONTEUDO_TEMPLATE = "";

    if ($nivel1 != "") {
        try {
            $query = " Secao_id < 0";

            $Sql = "";
            if (isset($_SESSION["where"])) $Sql = " " . $_SESSION["where"] . " ";
            $limite = "";
            if (isset($_SESSION["limite"])) $limite = " " . $_SESSION["limite"] . " ";

            $_SESSION["where"] = "";

            $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $secao_id . "-") . ") = '-" . $secao_id . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem " . $limite;
            $Rs_secao = mysqli_query($con, $Query_Secao);

            if (mysqli_num_rows($Rs_secao) > 0) {
                while ($row1 = mysqli_fetch_array($Rs_secao)) {
                    $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel1, $row1, $i);

                    if ($nivel2 != "") {
                        $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row1["secao_id"] . "-") . ") = '-" . $row1["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem " . $limite;
                        $Rs_secao2 = mysqli_query($con, $Query_Secao);

                        if (mysqli_num_rows($Rs_secao2) > 0) {
                            while ($row2 = mysqli_fetch_array($Rs_secao2)) {
                                $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel2, $row2, $ii);

                                if ($nivel3 != "") {
                                    $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row2["secao_id"] . "-") . ") = '-" . $row2["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                    $Rs_secao3 = mysqli_query($con, $Query_Secao);

                                    if (mysqli_num_rows($Rs_secao3) > 0) {
                                        while ($row3 = mysqli_fetch_array($Rs_secao3)) {
                                            $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel3, $row3, $iii);

                                            if ($nivel4 != "") {
                                                $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row3["secao_id"] . "-") . ") = '-" . $row3["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                                $Rs_secao4 = mysqli_query($con, $Query_Secao);

                                                if (mysqli_num_rows($Rs_secao4) > 0) {
                                                    while ($row4 = mysqli_fetch_array($Rs_secao4)) {
                                                        $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel4, $row4, $iiii);

                                                        if ($nivel5 != "") {
                                                            $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row4["secao_id"] . "-") . ") = '-" . $row4["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                                            $Rs_secao5 = mysqli_query($con, $Query_Secao);

                                                            if (mysqli_num_rows($Rs_secao5) > 0) {
                                                                while ($row5 = mysqli_fetch_array($Rs_secao5)) {
                                                                    $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel5, $row5, $iiiii);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            acumula_erro("Erro ao montar menu (secao_id = " . $secao_id . ") - " . $e->getMessage());
        }
    }

    return $CONTEUDO_TEMPLATE;
}