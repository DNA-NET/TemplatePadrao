<?php
//=================================================================================================================
//       Função para atualização de imagens e textos no template
//=================================================================================================================
function Atualiza_imagem(
    $CONTEUDO_TEMPLATE,
    $conteudo_texto,
    $conteudo_imagem_pq,
    $conteudo_imagem_gr,
    $Institucional_id
) {
    $campos = "";
    global $Dominio_url_producao, $con;

    try {

        if ($conteudo_texto) $campos = $campos . "Institucional_texto, ";
        if ($conteudo_imagem_pq) $campos = $campos . "Institucional_imagem_pq, ";
        if ($conteudo_imagem_gr) $campos = $campos . "Institucional_imagem_gr";
        if (right($campos, 2) == ", ") $campos = left($campos, strlen($campos) - 2);

        $da2 = mysqli_query($con, "select " . $campos . " from institucional where Institucional_id = " . $Institucional_id);


        $n = mysqli_num_rows($da2);

        if ($n > 0) {
            $rowI = mysqli_fetch_assoc($da2);
            if ($conteudo_texto) {
                if (isset($rowI["Institucional_texto"])) {
                    if (strpos($rowI["Institucional_texto"], "<!--GALERIA") !== false) $CONTEUDO_TEMPLATE = str_replace("<!--GALERIA", "<!--GGAALLEERRIIAA", $CONTEUDO_TEMPLATE);
                    $CONTEUDO_TEMPLATE = str_replace("conteudo_texto", "sim", str_replace("Institucional_texto", $rowI["Institucional_texto"], $CONTEUDO_TEMPLATE));
                } else {
                    $CONTEUDO_TEMPLATE = str_replace("Institucional_texto", "", $CONTEUDO_TEMPLATE);
                }
            }
            if ($conteudo_imagem_pq) {
                if (isset($rowI["Institucional_imagem_pq"])) {
                    $imagem_cache = $Dominio_url_producao . '/cache/imagens/institucional_imagem_pq_Institucional_id_' . $Institucional_id . '.jpg';
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagem_cache) || file_exists($imagem_cache)) {
                        $CONTEUDO_TEMPLATE = str_replace("Institucional_imagem_pq", $imagem_cache, $CONTEUDO_TEMPLATE);
                    } else {
                        $CONTEUDO_TEMPLATE = str_replace("Institucional_imagem_pq", $Dominio_url_producao . "/Manager/show_image.php?show_arquivo=institucional&show_campo=institucional_imagem_pq&show_chave=Institucional_id=" . $Institucional_id, $CONTEUDO_TEMPLATE);
                    }
                } else {
                    $CONTEUDO_TEMPLATE = str_replace("Institucional_imagem_pq", $Dominio_url_producao . '/Manager/imagens/pixel.gif', $CONTEUDO_TEMPLATE);
                }
            }
            if ($conteudo_imagem_gr) {
                if (isset($rowI["Institucional_imagem_gr"])) {
                    $imagem_cache = $Dominio_url_producao . '/cache/imagens/Institucional_imagem_gr_Institucional_id_' . $Institucional_id . '.jpg';
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagem_cache)) {
                        $CONTEUDO_TEMPLATE = str_replace("Institucional_imagem_gr", $imagem_cache, $CONTEUDO_TEMPLATE);
                    } else {
                        $CONTEUDO_TEMPLATE = str_replace("Institucional_imagem_gr", $Dominio_url_producao . "/Manager/show_image.php?show_arquivo=institucional&show_campo=institucional_imagem_gr&show_chave=Institucional_id=" . $Institucional_id, $CONTEUDO_TEMPLATE);
                    }
                } else {
                    $CONTEUDO_TEMPLATE = str_replace("Institucional_imagem_gr", $Dominio_url_producao . '/Manager/imagens/pixel.gif', $CONTEUDO_TEMPLATE);
                }
            }
        }
    } catch (Exception $e) {
        $CONTEUDO_TEMPLATE = ("Erro ao atualizar os dados de imagens e/ou textos para o conteúdo (Institucional_id = " . $Institucional_id . ") - " . $e->getMessage());
    }

    return $CONTEUDO_TEMPLATE;
}