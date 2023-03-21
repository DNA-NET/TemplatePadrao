<?php
//=================================================================================================================
//       Função para atualização de dados do conteúdo no template
//=================================================================================================================
function Atualiza_conteudo(
    $rowC,
    $CONTEUDO_TEMPLATE,
    $i,
    $conteudo_secao_descricao,
    $conteudo_secao_id,
    $conteudo_secao_link,
    $conteudo_tamanho,
    $conteudo_fonte,
    $conteudo_autor,
    $conteudo_legenda,
    $conteudo_secao_nome,
    $conteudo_data,
    $conteudo_nome,
    $conteudo_descricao,
    $conteudo_arquivo,
    $conteudo_url,
    $conteudo_texto,
    $conteudo_imagem_pq,
    $conteudo_imagem_gr
) {


    global $con, $Dominio_url_producao;

    if ($conteudo_data) {
        $Institucional_data_cadastro = DataBR($rowC["Institucional_data_cadastro"]);
        if ($Institucional_data_cadastro) {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data_cadastro_ddmm", left($Institucional_data_cadastro, 5), $CONTEUDO_TEMPLATE);
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data_cadastro", $Institucional_data_cadastro, $CONTEUDO_TEMPLATE);
        }
        $Institucional_data = DataBR($rowC["Institucional_data"]);
        if ($Institucional_data) {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data_ddmm", left($Institucional_data, 5), $CONTEUDO_TEMPLATE);
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data_dia", date('d', strtotime($rowC["Institucional_data"])), $CONTEUDO_TEMPLATE);
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data_mes_extenso", mes_extenso(date('m', strtotime($rowC["Institucional_data"]))), $CONTEUDO_TEMPLATE);
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data_mes", date('m', strtotime($rowC["Institucional_data"])), $CONTEUDO_TEMPLATE);
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data_ano", date('Y', strtotime($rowC["Institucional_data"])), $CONTEUDO_TEMPLATE);
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data_hora", date('H:i', strtotime($rowC["Institucional_data"])), $CONTEUDO_TEMPLATE);
            $CONTEUDO_TEMPLATE = str_replace("Institucional_data", $Institucional_data, $CONTEUDO_TEMPLATE);
        }
    }


    if ($rowC["Institucional_id"] > 0) $CONTEUDO_TEMPLATE = str_replace("Institucional_id", $rowC["Institucional_id"], $CONTEUDO_TEMPLATE);
    $CONTEUDO_TEMPLATE = str_replace("Mascara_id", $rowC["Mascara_id"], $CONTEUDO_TEMPLATE);
    $_SESSION["Mascara_id"] = $rowC["Mascara_id"];

    if ($conteudo_url) {
        if (isset($rowC["Institucional_url"])) {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_url", $rowC["Institucional_url"], $CONTEUDO_TEMPLATE);
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_url", "", $CONTEUDO_TEMPLATE);
        }
    }

    if ($conteudo_arquivo) {
        if (isset($rowC["Institucional_arquivo2"])) {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_arquivo2", $rowC["Institucional_arquivo2"], $CONTEUDO_TEMPLATE);
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_arquivo2", "", $CONTEUDO_TEMPLATE);
        }
        if (isset($rowC["Institucional_arquivo"])) {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_arquivo", $rowC["Institucional_arquivo"], $CONTEUDO_TEMPLATE);
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_arquivo", "", $CONTEUDO_TEMPLATE);
        }
    }
    if ($conteudo_tamanho) {
        if (isset($rowC["Institucional_arquivo2_tamanho"])) {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_tamanho2", $rowC["Institucional_arquivo2_tamanho"], $CONTEUDO_TEMPLATE);
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_tamanho2", "", $CONTEUDO_TEMPLATE);
        }
        if (isset($rowC["Institucional_arquivo_tamanho"])) {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_tamanho", $rowC["Institucional_arquivo_tamanho"], $CONTEUDO_TEMPLATE);
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_tamanho", "", $CONTEUDO_TEMPLATE);
        }
    }
    if ($conteudo_autor) {
        if (isset($rowC["Institucional_autor"])) {
            if ($rowC["Institucional_autor"] != "") {
                $CONTEUDO_TEMPLATE = str_replace("conteudo_autor", "sim", str_replace("Institucional_autor", str_replace(Chr(13), "<br>", $rowC["Institucional_autor"]), $CONTEUDO_TEMPLATE));
                $CONTEUDO_TEMPLATE = str_replace(Chr(10), "", $CONTEUDO_TEMPLATE);
            } else {
                $CONTEUDO_TEMPLATE = str_replace("Institucional_autor", "", $CONTEUDO_TEMPLATE);
            }
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_autor", "", $CONTEUDO_TEMPLATE);
        }
    }
    if ($conteudo_fonte) {
        if (isset($rowC["Institucional_fonte"])) {
            if ($rowC["Institucional_fonte"] != "") {
                $CONTEUDO_TEMPLATE = str_replace("conteudo_fonte", "sim", str_replace("Institucional_fonte", str_replace(Chr(13), "<br>", $rowC["Institucional_fonte"]), $CONTEUDO_TEMPLATE));
                $CONTEUDO_TEMPLATE = str_replace(Chr(10), "", $CONTEUDO_TEMPLATE);
            } else {
                $CONTEUDO_TEMPLATE = str_replace("Institucional_fonte", "", $CONTEUDO_TEMPLATE);
            }
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_fonte", "", $CONTEUDO_TEMPLATE);
        }
    }
    if ($conteudo_nome) {
        if (isset($rowC["Institucional_nome"])) {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_nome", $rowC["Institucional_nome"], $CONTEUDO_TEMPLATE);
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_nome", "", $CONTEUDO_TEMPLATE);
        }
    }
    if ($conteudo_legenda) {
        if (isset($rowC["Institucional_legenda"])) {
            if ($rowC["Institucional_legenda"] != "") {
                $CONTEUDO_TEMPLATE = str_replace("conteudo_legenda", "sim", str_replace("Institucional_legenda", str_replace(Chr(13), "<br>", $rowC["Institucional_legenda"]), $CONTEUDO_TEMPLATE));
                $CONTEUDO_TEMPLATE = str_replace(Chr(10), "", $CONTEUDO_TEMPLATE);
            } else {
                $CONTEUDO_TEMPLATE = str_replace("Institucional_legenda", "", $CONTEUDO_TEMPLATE);
            }
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_legenda", "", $CONTEUDO_TEMPLATE);
        }
    }
    if ($conteudo_descricao) {
        if (isset($rowC["Institucional_descricao"])) {
            if ($rowC["Institucional_descricao"] != "") {
                $CONTEUDO_TEMPLATE = str_replace("conteudo_descricao", "sim", str_replace("Institucional_descricao", $rowC["Institucional_descricao"], $CONTEUDO_TEMPLATE));
            } else {
                $CONTEUDO_TEMPLATE = str_replace("Institucional_descricao", "", $CONTEUDO_TEMPLATE);
            }
        } else {
            $CONTEUDO_TEMPLATE = str_replace("Institucional_descricao", "", $CONTEUDO_TEMPLATE);
        }
    }

    //=================================================================================================================
    //       Verifica Informações do Autor
    //=================================================================================================================

    if (strpos($CONTEUDO_TEMPLATE, "Funcionarios") !== false) {
        $daFunc = mysqli_query($con, "select Funcionarios_nome, email, username from funcionarios where Funcionarios_id = " . $rowC["Funcionarios_id"]);

        if (mysqli_num_rows($daFunc) > 0) {
            $rowF = mysqli_fetch_assoc($daFunc);
            $CONTEUDO_TEMPLATE = str_replace('Funcionarios_nome', $rowF["Funcionarios_nome"], $CONTEUDO_TEMPLATE);
            $CONTEUDO_TEMPLATE = str_replace('Funcionarios_email', $rowF["email"], $CONTEUDO_TEMPLATE);
        }
    }


    return $CONTEUDO_TEMPLATE;
}