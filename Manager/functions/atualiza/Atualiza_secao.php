<?php
//=================================================================================================================
//      Função para atualização de dados de seçao no template
//=================================================================================================================
function Atualiza_secao(
    $CONTEUDO_TEMPLATE,
    $conteudo_secao_nome,
    $conteudo_secao_id,
    $conteudo_secao_descricao,
    $conteudo_secao_link,
    $secao_id_principal,
    $Institucional_id
) {

    global $Dominio_url_producao, $con;

    $secao_link = "";
    $concatenador = "";

    try {

        $da2 = mysqli_query($con, "select secao.secao_id, secao_nome, secao_link, secao_descricao, secao_imagem from secao where secao.secao_id =" . $secao_id_principal);
        $n = mysqli_num_rows($da2);

        if ($n <= 0) {
            $da2 = mysqli_query($con, "select secao.secao_id, secao_nome, secao_link, secao_descricao from institucional_secao, secao where institucional_secao.secao_id = secao.secao_id and institucional_id=" . $Institucional_id);
            $n = mysqli_num_rows($da2);
        }
        if ($n > 0) {
            $rowI = mysqli_fetch_assoc($da2);
            if ($conteudo_secao_nome) $CONTEUDO_TEMPLATE = str_replace("secao_nome", $rowI["secao_nome"], $CONTEUDO_TEMPLATE);
            if ($conteudo_secao_descricao) $CONTEUDO_TEMPLATE = str_replace("secao_descricao", $rowI["secao_descricao"], $CONTEUDO_TEMPLATE);
            if ($conteudo_secao_id) $CONTEUDO_TEMPLATE = str_replace("conteudo_secao_id", $rowI["secao_id"], $CONTEUDO_TEMPLATE);
            if ($conteudo_secao_link) {
                $CONTEUDO_TEMPLATE = str_replace("secao_link", Link_secao($secao_id_principal, $Institucional_id), $CONTEUDO_TEMPLATE);
            }

            if (isset($rowI["secao_imagem"])) {
                $CONTEUDO_TEMPLATE = str_replace("secao_imagem", 'data:image/jpg;base64,' . base64_encode($rowI["secao_imagem"]), $CONTEUDO_TEMPLATE);
            } else {
                $CONTEUDO_TEMPLATE = str_replace("secao_imagem", $Dominio_url_producao . '/Manager/imagens/pixel.gif', $CONTEUDO_TEMPLATE);
            }
        }
    } catch (Exception $e) {
        acumula_erro("Erro ao atualizar os dados de seção para o conteúdo (Institucional_id = " . $Institucional_id . ") - " . $e->getMessage());
    }

    return $CONTEUDO_TEMPLATE;
}