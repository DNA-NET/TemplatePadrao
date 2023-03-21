<?php
//==================================================================================================================
//	Busca a secao corrente da navegação pelos menus dinâmicos
//==================================================================================================================
function Busca_secao($secao_id_navegacao, $classe)
{

    global $con;

    $secao_dna = "";
    $secao_link = "";
    $secao_nome = "";
    $secao_id = "";
    $SECAO_APARECE_SITE = "";

    $_SESSION["erro"] = "";

    if ($secao_id_navegacao != "") {

        try {

            $_SESSION["secao_id"] = $secao_id_navegacao;

            $RS1 = mysqli_query(
                $con,
                "SELECT
                    secao_id, secao_nome, secao_nome_ordem, secao_ordem, secao_link, secao_dna,
                    secao_controle, secao_descricao, secao_visitas, SECAO_APARECE_SITE, secao_menu
                FROM
                    secao
                WHERE
                    secao_id=$secao_id_navegacao"
            );

            if (mysqli_num_rows($RS1) > 0) {
                $row = mysqli_fetch_assoc($RS1);
                $_SESSION["secao_nome"] = $row["secao_nome"];
                $_SESSION["secao_ordem"] = $row["secao_ordem"];
                $_SESSION["secao_link"] = $row["secao_link"];
                $_SESSION["secao_dna"] = $row["secao_dna"];
                $_SESSION["secao_descricao"] = $row["secao_descricao"];
                $_SESSION["secao_visitas"] = $row["secao_visitas"];
                $_SESSION["secao_controle"] = $row["secao_controle"];
                $_SESSION["secao_nome_ordem"] = $row["secao_nome_ordem"];
                $_SESSION["secao_menu"] = $row["secao_menu"];
                $_SESSION["navegacao"] = "";
                $_SESSION["navegacao_nome"] = "";
                $secao_dna = $row["secao_dna"];
                $secao_link = $row["secao_link"];
                $secao_nome = $row["secao_nome"];
                $secao_nome_ordem = $row["secao_nome_ordem"];
                $secao_id = $row["secao_id"];
                $SECAO_APARECE_SITE = $row["SECAO_APARECE_SITE"];

                while ($secao_dna != "") {
                    $sdna = substr($secao_dna, 0, -1);
                    $id_dna = strrev(substr(strrev($sdna), 0, strpos(strrev($sdna), "-")));


                    if ($SECAO_APARECE_SITE != "1") {
                        if ($secao_link != "") {
                            if (strpos($secao_link, ".php") !== false) {
                                $_SESSION["navegacao"] = "<li><a href=\"" . $secao_link . "\">" . $secao_nome . "</a></li>" . $_SESSION["navegacao"];
                            } else {
                                $_SESSION["navegacao"] = "<li><a href=\"" . Link_secao($secao_id, "") . "\">" . $secao_nome . "</a></li>" . $_SESSION["navegacao"];
                            }
                        } else {
                            $_SESSION["navegacao"] = "<li>" . $secao_nome . "</li>  " . $_SESSION["navegacao"];
                        }
                    }

                    $_SESSION["navegacao_nome"] = $secao_nome . "," . $_SESSION["navegacao_nome"];

                    $RS2 = mysqli_query(
                        $con,
                        "SELECT
                            secao_id, secao_nome, secao_ordem, secao_link, secao_dna, SECAO_APARECE_SITE
                        FROM
                            secao
                        WHERE
                            secao_id=$id_dna"
                    );

                    if (mysqli_num_rows($RS2) > 0) {
                        $row2 = mysqli_fetch_assoc($RS2);
                        $secao_dna = $row2["secao_dna"];
                        $secao_link = $row2["secao_link"];
                        $secao_nome = $row2["secao_nome"];
                        $secao_id = $row2["secao_id"];
                        $SECAO_APARECE_SITE = $row2["SECAO_APARECE_SITE"];
                    } else {
                        $secao_dna = "";
                    }
                }

                if ($_SESSION["navegacao_nome"] != "") $_SESSION["navegacao_nome"] = left($_SESSION["navegacao_nome"], strlen($_SESSION["navegacao_nome"]) - 1);
                $_SESSION["navegacao"] = str_replace("Portal", "Home", $_SESSION["navegacao"]);

                mysqli_query(
                    $con,
                    "UPDATE
                        secao
                    SET
                        secao_visitas=secao_visitas+1
                    WHERE
                        Secao_id =" . $_SESSION["secao_id"]
                );
            }
        } catch (Exception $e) {
            echo "<b>OPS</b>:Problema na função Busca_secao: ERRO=" . $e->getMessage();
        }
    }
}