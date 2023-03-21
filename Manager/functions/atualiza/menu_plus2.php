<?php
//=================================================================================================================
//      2ª Função para montagem de menus dinâmicos até 5 níveis
//=================================================================================================================
function menu_plus2(
    $secao_id,
    $nivel1_antes,
    $nivel1,
    $nivel1_depois,
    $nivel2_antes,
    $nivel2,
    $nivel2_depois,
    $nivel3_antes,
    $nivel3,
    $nivel3_depois,
    $nivel4_antes,
    $nivel4,
    $nivel4_depois,
    $nivel5_antes,
    $nivel5,
    $nivel5_depois
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
            if (isset($_SESSION["Secoes_permitidas"])) {
                if ($_SESSION["Secoes_permitidas"] != '') $query = " Secao_id in (" . $_SESSION["Secoes_permitidas"] . ") ";
            }

            $Sql = "";
            if (isset($_SESSION["where"])) $Sql = " " . $_SESSION["where"] . " ";
            $_SESSION["where"] = "";

            $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $secao_id . "-") . ") = '-" . $secao_id . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem ";

            //echo $Query_Secao . " -- " . $_SESSION["Secoes_permitidas"];
            //exit;

            $Rs_secao = mysqli_query($con, $Query_Secao);

            if (mysqli_num_rows($Rs_secao) > 0) {
                while ($row1 = mysqli_fetch_array($Rs_secao)) {
                    if ($nivel1_antes != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel1_antes . "\r\n";

                    if ($nivel2 != "") {
                        $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row1["secao_id"] . "-") . ") = '-" . $row1["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                        $Rs_secao2 = mysqli_query($con, $Query_Secao);

                        if (mysqli_num_rows($Rs_secao2) > 0) {
                            //$CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu(str_replace("<a ","<a ", $nivel1), $row1, $i); Comentado Geraldo *14/06/2018

                            $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu(str_replace("<a ", "<a ", str_replace("menu_pai", "glyphicon glyphicon-chevron-down float-right", str_replace("list-group-item", "list-group-item menu_link", $nivel1))), $row1, $i) . "\r\n"; //*Edição Menu Lateral Abre e Fecha - Geraldo 14/06/2018

                            if ($nivel2_antes != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel2_antes . "\r\n";
                            while ($row2 = mysqli_fetch_array($Rs_secao2)) {
                                $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel2, $row2, $ii) . "\r\n";

                                if ($nivel3 != "") {
                                    $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row2["secao_id"] . "-") . ") = '-" . $row2["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                    $Rs_secao3 = mysqli_query($con, $Query_Secao);

                                    if (mysqli_num_rows($Rs_secao3) > 0) {

                                        if ($nivel3_antes != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel3_antes . "\r\n";
                                        while ($row3 = mysqli_fetch_array($Rs_secao3)) {
                                            $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel3, $row3, $iii) . "\r\n";

                                            if ($nivel4 != "") {
                                                $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row3["secao_id"] . "-") . ") = '-" . $row3["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                                $Rs_secao4 = mysqli_query($con, $Query_Secao);

                                                if (mysqli_num_rows($Rs_secao4) > 0) {
                                                    if ($nivel4_antes != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel4_antes . "\r\n";
                                                    while ($row4 = mysqli_fetch_array($Rs_secao4)) {
                                                        $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel4, $row4, $iiii) . "\r\n";

                                                        if ($nivel5 != "") {
                                                            $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row4["secao_id"] . "-") . ") = '-" . $row4["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                                            $Rs_secao5 = mysqli_query($con, $Query_Secao);

                                                            if (mysqli_num_rows($Rs_secao5) > 0) {
                                                                if ($nivel5_antes != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel5_antes . "\r\n";
                                                                while ($row5 = mysqli_fetch_array($Rs_secao5)) {
                                                                    $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel5, $row5, $iiiii) . "\r\n";
                                                                }
                                                                if ($nivel5_depois != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel5_depois . "\r\n";
                                                            }
                                                        }
                                                    }
                                                    if ($nivel4_depois != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel4_depois . "\r\n";
                                                }
                                            }
                                        }
                                        if ($nivel3_depois != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel3_depois . "\r\n";
                                    }
                                }
                            }
                            if ($nivel2_depois != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel2_depois . "\r\n";
                        } else {
                            $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel1, $row1, $i);
                        }
                    } else {
                        $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel1, $row1, $i);
                    }
                    if ($nivel1_depois != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel1_depois . "\r\n";
                }
            }
        } catch (Exception $e) {
            acumula_erro("Erro ao montar menu (secao_id = " . $secao_id . ") - " . $e->getMessage());
        }
    }

    return $CONTEUDO_TEMPLATE;
}