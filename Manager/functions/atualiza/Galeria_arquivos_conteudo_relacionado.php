<?php



function Galeria_arquivos_conteudo_relacionado(
    $CONTEUDO_TEMPLATE,
    $id
) {

    global $con, $Dominio_url_producao;

    //=================================================================================================================
    //       Verifica arquivos relacionados
    //=================================================================================================================

    $inicio_anexo = strpos($CONTEUDO_TEMPLATE, '<!--ANEXO');
    $fim_anexo = strpos($CONTEUDO_TEMPLATE, 'ANEXO-->');
    $template_arquivos_final = "";

    if ($inicio_anexo !== false && $fim_anexo !== false) {
        $template_anexo = substr($CONTEUDO_TEMPLATE, $inicio_anexo + 9, $fim_anexo - ($inicio_anexo + 9));
        $template_arquivos = "";
        $RS_Arquivos = mysqli_query($con, "select * from arquivos where Institucional_id = " . $id . " order by Arquivo_id");
        if (mysqli_num_rows($RS_Arquivos) > 0) $template_arquivos_final = "<h2 style='margin-top:15px;'>Arquivos e documentos</h2>";
        while ($RSAnexo = mysqli_fetch_array($RS_Arquivos)) {
            $template_arquivos = str_replace('Anexo_arquivo', $RSAnexo["Arquivo_nome"], $template_anexo);
            $template_arquivos = str_replace('Anexo_data', DataBR($RSAnexo["Arquivo_data"]), $template_arquivos);
            $template_arquivos = str_replace('Anexo_nome', $RSAnexo["Arquivo_titulo"], $template_arquivos);
            $template_arquivos_final .= $template_arquivos;
        }
        $CONTEUDO_TEMPLATE = str_replace('<!--ANEXO', '', $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace($template_anexo, $template_arquivos_final, $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace('ANEXO-->', '', $CONTEUDO_TEMPLATE);
    }


    //=================================================================================================================
    //       Verifica imagens relacionadas
    //=================================================================================================================

    $inicio_anexo = strpos($CONTEUDO_TEMPLATE, '<!--GALERIA');
    $fim_anexo = strpos($CONTEUDO_TEMPLATE, 'GALERIA-->');
    $template_arquivos_final = "";

    if ($inicio_anexo !== false && $fim_anexo !== false) {
        $template_anexo = substr($CONTEUDO_TEMPLATE, $inicio_anexo + 11, $fim_anexo - ($inicio_anexo + 11));
        $template_arquivos = "";
        $RS_Arquivos = mysqli_query($con, "select Imagem_id, Imagem_titulo, Imagem_descricao, Imagem_fonte from galeria_imagem where Institucional_id = " . $id . " order by Imagem_id");

        $count_break = 0;
        while ($RSAnexo = mysqli_fetch_array($RS_Arquivos)) {
            $template_arquivos = str_replace('indice', $RSAnexo["Imagem_id"], $template_anexo);
            $template_arquivos = str_replace('Imagem_nome', $RSAnexo["Imagem_titulo"], $template_arquivos);
            $template_arquivos = str_replace('Imagem_fonte', $RSAnexo["Imagem_fonte"], $template_arquivos);
            $template_arquivos = str_replace('Imagem_descricao', $RSAnexo["Imagem_descricao"], $template_arquivos);
            $template_arquivos = str_replace("Imagem_pq", $Dominio_url_producao . "/Manager/show_image.php?show_arquivo=galeria_imagem&show_campo=Imagem_thumb&show_chave=Imagem_id=" . $RSAnexo["Imagem_id"], $template_arquivos);
            $template_arquivos = str_replace("Imagem_gr", $Dominio_url_producao . "/Manager/show_image.php?show_arquivo=galeria_imagem&show_campo=Imagem&show_chave=Imagem_id=" . $RSAnexo["Imagem_id"], $template_arquivos);
            if ($count_break == 3) {
                $template_arquivos = str_replace("class=\"col-sm-4 col-md-4\"", "class=\"col-sm-4 col-md-4\" style=\"clear:left;\"", $template_arquivos);
                $count_break = 0;
            }
            $count_break = $count_break + 1;
            $template_arquivos_final .= $template_arquivos;
        }
        $CONTEUDO_TEMPLATE = str_replace('<!--GALERIA', '', $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace($template_anexo, $template_arquivos_final, $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace('GALERIA-->', '', $CONTEUDO_TEMPLATE);
    }

    //=================================================================================================================
    //       Verifica conteúdos relacionados
    //=================================================================================================================

    $inicio_anexo = strpos($CONTEUDO_TEMPLATE, '<!--CONTEUDO');
    $fim_anexo = strpos($CONTEUDO_TEMPLATE, 'CONTEUDO-->');
    $template_arquivos_final = "";

    if ($inicio_anexo !== false && $fim_anexo !== false) {
        $template_anexo = substr($CONTEUDO_TEMPLATE, $inicio_anexo + 12, $fim_anexo - ($inicio_anexo + 12));
        $template_arquivos = "";
        $RS_Arquivos = mysqli_query($con, "select institucional_id, institucional_nome, url_amigavel from institucional, url_amigavel where institucional.institucional_id = url_amigavel.campo and institucional_link like '%" . $id . "%' order by institucional_data desc");
        if (mysqli_num_rows($RS_Arquivos) > 0) $template_arquivos_final = "<h4>Veja tamb&eacute;m:</h4><ul class=\"list-icon spaced check-circle\" style=\"margin-bottom: 30px;\">";
        while ($RSAnexo = mysqli_fetch_array($RS_Arquivos)) {
            $template_arquivos = str_replace('conteudo_nome', $RSAnexo["institucional_nome"], $template_anexo);
            $template_arquivos = str_replace('conteudo_url', $Dominio_url_producao . $RSAnexo["url_amigavel"], $template_arquivos);
            $template_arquivos_final .= $template_arquivos;
        }
        $template_arquivos_final .= "</ul>";
        $CONTEUDO_TEMPLATE = str_replace('<!--CONTEUDO', '', $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace($template_anexo, $template_arquivos_final, $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace('CONTEUDO-->', '', $CONTEUDO_TEMPLATE);
    }

    //=================================================================================================================
    //       Verifica template para tags
    //=================================================================================================================

    $inicio_anexo = strpos($CONTEUDO_TEMPLATE, '<!--TAGS');
    $fim_anexo = strpos($CONTEUDO_TEMPLATE, 'TAGS-->');
    $template_arquivos_final = "";

    if ($inicio_anexo !== false && $fim_anexo !== false) {
        $template_anexo = substr($CONTEUDO_TEMPLATE, $inicio_anexo + 8, $fim_anexo - ($inicio_anexo + 8));
        $template_arquivos = "";
        $RS_Arquivos = mysqli_query($con, "select m.metatag_palavra
                                                from institucional i
                                                inner join institucional_metatags im on i.institucional_id=im.institucional_id
                                                inner join metatags m on m.metatag_id=im.metatag_id
                                              where
                                                i.institucional_id=" . $id . " order by m.metatag_palavra asc");
        $n = mysqli_num_rows($RS_Arquivos);
        if ($n > 0) {
            while ($RSAnexo = mysqli_fetch_array($RS_Arquivos)) {
                $template_arquivos = str_replace('metatag_palavra', $RSAnexo["metatag_palavra"], $template_anexo);
                $template_arquivos_final .= $template_arquivos;
            }
        } else {
            $CONTEUDO_TEMPLATE .= "<script type=\"text/javascript\">document.getElementById('listatags').style.display='none';</script>";
        }
        $CONTEUDO_TEMPLATE = str_replace('<!--TAGS', '', $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace($template_anexo, $template_arquivos_final, $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace('TAGS-->', '', $CONTEUDO_TEMPLATE);
    }

    return $CONTEUDO_TEMPLATE;
}