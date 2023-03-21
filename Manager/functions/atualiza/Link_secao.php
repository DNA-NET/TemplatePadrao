<?php



function Link_secao(
    $secao_id,
    $campo
) {

    global $Dominio_url_producao;
    global $Dominio_url_menu;
    global $con;
    $secao_link = "";
    $concatenador = "";
    $Conteudo_retorno = "";

    try {

        if ($campo != "") {
            $RS = mysqli_query($con, "select url_amigavel from url_amigavel where url_tipo = 'conteudo' and campo = " . $campo);
        } else {
            $RS = mysqli_query($con, "select url_amigavel from url_amigavel where url_tipo = 'secao' and secao_id = " . $secao_id);
            //echo "select url_amigavel from url_amigavel where url_tipo = 'secao' and secao_id = " . $secao_id;
        }

        if (mysqli_num_rows($RS) > 0) {
            $row3 = mysqli_fetch_assoc($RS);
            $Conteudo_retorno = $Dominio_url_menu . $row3["url_amigavel"];
        }

        if ($Conteudo_retorno == "") {
            if ($campo == '') {
                $RS2 = mysqli_query($con, "select secao_link, secao_abertura from secao where secao_id = " . $secao_id);
                if (mysqli_num_rows($RS2) > 0) {
                    $row2 = mysqli_fetch_assoc($RS2);
                    //					if ($row2["secao_link"] != "") {
                    if (strpos($row2["secao_link"], "/") !== false) {
                        $secao_link = $row2["secao_link"];
                        $concatenador = "?";
                        if (strpos($secao_link, $concatenador) > 0) $concatenador = "&";

                        $Conteudo_retorno = $secao_link . $concatenador . "secao_id=" . $secao_id;
                    } else {
                        $Conteudo_retorno = "";
                    }
                }
            }
        }
    } catch (Exception $e) {
        $Conteudo_retorno = ("Erro ao montar link da seção (Seção ID = " . $secao_id . ") - " . $e->getMessage());
    }

    return $Conteudo_retorno;
}