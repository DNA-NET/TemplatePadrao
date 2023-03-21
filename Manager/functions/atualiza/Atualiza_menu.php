<?php



function Atualiza_menu(
    $nivel,
    $row2,
    $i
) {

    global $Dominio_url_producao;
    global $Dominio_url_menu;
    global $con;
    $secao_link = "";
    $concatenador = "";
    $Conteudo_retorno = "";

    $Conteudo_retorno = "";

    try {

        $nivel = str_replace("secao_id", $row2["secao_id"], $nivel);

        if (strpos($row2["secao_link"], ".") !== false || strpos($row2["secao_link"], "/") !== false) {
            if ($row2["secao_abertura"] == "Nova Janela") {
                $nivel = str_replace('href="secao_link', 'target="_blank" href="secao_link', $nivel);
            }
            $nivel = str_replace("secao_link", $row2["secao_link"], $nivel);
        } else {
            $RS = mysqli_query($con, "SELECT url_amigavel FROM url_amigavel WHERE url_tipo = 'secao' and secao_id = " . $row2["secao_id"]);
            if (mysqli_num_rows($RS) > 0 && $row2["secao_link"] != "") {
                $row3 = mysqli_fetch_assoc($RS);
                $nivel = str_replace("secao_link", $Dominio_url_menu . $row3["url_amigavel"], $nivel);
            } else {
                if ($row2["secao_link"] != "") {
                    $secao_link = $row2["secao_link"];
                    $concatenador = "?";
                    if (strpos($secao_link, $concatenador) !== false) $concatenador = "&";
                    if ($row2["secao_abertura"] == "Nova Janela") {
                        $nivel = str_replace('href="secao_link', 'target="_blank" href="secao_link', $nivel);
                    }
                    $nivel = str_replace("secao_link", $secao_link . $concatenador . "secao_id=" . $row2["secao_id"], $nivel);
                } else {
                    $nivel = str_replace("href", "", str_replace("secao_link", "", $nivel));
                }
            }
        }

        $nivel = str_replace("secao_descricao", $row2["secao_descricao"], $nivel);
        $nivel = str_replace("secao_dna", $row2["secao_dna"], $nivel);
        $nivel = str_replace("secao_idd", $row2["secao_id"], str_replace("secao_nome", $row2["secao_nome"], $nivel));

        if (isset($row2["secao_imagem"])) {
            $nivel = str_replace("secao_imagem", 'data:image/jpg;base64,' . base64_encode($row2["secao_imagem"]), $nivel);
        } else {
            $nivel = str_replace("secao_imagem", $Dominio_url_producao . '/Manager/imagens/pixel.gif', $nivel);
        }

        $Conteudo_retorno = $nivel;
    } catch (Exception $e) {
        $Conteudo_retorno = ("Erro ao montar menu (Indice = " . $i . ") - " . $e->getMessage());
    }

    return $Conteudo_retorno;
}