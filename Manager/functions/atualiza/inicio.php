<?php



function inicio()
{

    global $Atualiza_caminhoPermissao, $con, $Dominio_url_producao, $Dominio_url_menu;

    $secao_id = "";
    $campo = "";
    if (isset($_REQUEST["secao_id"])) $secao_id = $_REQUEST["secao_id"];
    if (isset($_REQUEST["campo"])) $campo = $_REQUEST["campo"];

    if ($secao_id == "") {
        $Url_amigavel = str_replace($Dominio_url_producao . '/portal/', '/', strtok($_SERVER["REQUEST_URI"], "?"));
        $Url_amigavel = str_replace($Dominio_url_menu . '/', '/', $Url_amigavel);

        $RS1 = mysqli_query($con, "select secao_id, campo, Url_tipo from url_amigavel where Url_amigavel='" . $Url_amigavel . "'");

        if (mysqli_num_rows($RS1) > 0) {
            $row = mysqli_fetch_assoc($RS1);
            $secao_id = $row["secao_id"];
            $_SESSION["secao_id"] = $secao_id;
            $_SESSION["Url_tipo"] = $row["Url_tipo"];
            if ($row["campo"] != null) $campo = $row["campo"];
        }
    }

    $_SESSION["campo"] = $campo;


    if ($secao_id != "") {
        if (strpos($secao_id, ",") === false) {
            try {
                Busca_secao($secao_id, "cinzamediohome");
                if (isset($_REQUEST["secao_dna"])) $_SESSION["inicio"] = "<script>secao_dna=''; id_secao='" . $secao_id . "'; secao_dna='" . $_SESSION["secao_dna"] . "-" . $_SESSION["secao_id"] . "-';</script>";

                if (isset($_SESSION["secao_controle"])) {

                    if (($_SESSION["secao_controle"] != '') && (strpos($_SERVER['SCRIPT_NAME'], "manager") === false)) {
                        if ($_SESSION["secao_controle"] == "Restrita" || $_SESSION["secao_controle"] == "Restrita_Aparente") {
                            $verificaPermissao = VerificaPermissao_Perfil($secao_id);
                            if ($verificaPermissao != "") {
                                $_SESSION["erro"] = $verificaPermissao;
                                header("Location: " . $Atualiza_caminhoPermissao . "?erro=" . $_SESSION["erro"]);
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                echo "<b>OPS</b>:Problema na função inicio: ERRO=" . $e->getMessage();
            }
        }
    }
}