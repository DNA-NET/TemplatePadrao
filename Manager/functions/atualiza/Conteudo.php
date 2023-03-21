<?php
//=================================================================================================================
//      Função para impressão do conteúdo dinâmico
//      Datas em oracle: to_date('" . strtotime(Now) . "')
//      Datas em MDB: #" . strtotime(Now) . "#
//      Datas em SQL: Getdate()  ou CONVERT(datetime,'" . Date . " " . FormatDateTime(Now(), 3) . "',103)
//=================================================================================================================
function Conteudo($Conteudo_campo)
{

    global $Dominio_url_producao, $Dominio_url_Atualiza, $Dominio_id_Atualiza, $banco_dados, $Caminho_fisico_Cache, $con;

    $_SESSION["Mascara_id"] = "";

    $CONTEUDO_TIPO = "";
    $Sql = "";
    $CONTEUDO_DEFAULT = "";
    $Secao_atual = "";
    $link_Atualiza = "";
    $template_arquivo = "";
    $template = "";
    $CONTEUDO_CACHE_data = "";
    $reg = "";
    $ordem = "";
    $funcao = "";
    $perfil = "";
    $pagina_atual = "";
    $linhas_por_pagina = 20;
    $numero_maximo = "";
    $Sql_padrao = "";
    $CONTEUDO_TEMPLATE = "";
    $conteudo_tratado = "";
    $CONTEUDO_TEMPLATE_final = "";
    $contador = "";
    $i_max = "";
    $CONTEUDO_ID = "";
    $cab_atualiza = "";
    $pagina_atual_conteudo = "";
    $campo = "";
    $CONT = "";
    $conteudo_secao_descricao = "";
    $conteudo_secao_id = "";
    $conteudo_secao_link = "";
    $conteudo_tamanho = "";
    $conteudo_fonte = "";
    $conteudo_autor = "";
    $conteudo_legenda = "";
    $conteudo_secao_nome = "";
    $conteudo_data = "";
    $conteudo_nome = "";
    $conteudo_descricao = "";
    $conteudo_arquivo = "";
    $conteudo_url = "";
    $conteudo_texto = "";
    $conteudo_imagem_pq = "";
    $conteudo_imagem_gr = "";
    $inicio = "";
    $fim = "";

    if (isset($_REQUEST["pagina_atual_" . $Conteudo_campo])) $pagina_atual_conteudo = $_REQUEST["pagina_atual_" . $Conteudo_campo];
    if (isset($_SESSION["campo"])) $campo = $_SESSION["campo"];

    $Sql_padrao = "";

    $conteudo_data = False;
    $conteudo_nome = False;
    $conteudo_descricao = False;
    $conteudo_texto = False;
    $conteudo_imagem_pq = False;
    $conteudo_imagem_gr = False;
    $conteudo_arquivo = False;
    $conteudo_url = False;
    $conteudo_fonte = False;
    $conteudo_autor = False;
    $conteudo_legenda = False;
    $conteudo_secao_nome = False;
    $conteudo_secao_id = False;
    $conteudo_secao_link = False;
    $conteudo_secao_descricao = False;
    $conteudo_tamanho = False;

    $conteudo_tratado = "";
    $CONTEUDO_TEMPLATE = "";
    $Conteudo = "";
    $cab_atualiza = "";

    $CONTEUDO_TIPO = "Detalhe";


    $CONT = mysqli_query($con, "select * from conteudo where CONTEUDO_CAMPO = '" . $Conteudo_campo . "'");

    $n = mysqli_num_rows($CONT);
    $i = 0;

    $CONTEUDO_DEFAULT = " [Conteúdo Dinâmico] ";
    if ($n > 0) {
        $row = mysqli_fetch_assoc($CONT);
        try {

            if (isset($row["CONTEUDO_TIPO"])) {
                $CONTEUDO_TIPO = $row["CONTEUDO_TIPO"];
                if (isset($row["CONTEUDO_DEFAULT"])) $CONTEUDO_DEFAULT = $row["CONTEUDO_DEFAULT"];
            }
            $cab_atualiza = "";

            try {
                $funcao = "";
                if (isset($_SESSION["Funcao"])) $funcao = $_SESSION["Funcao"];

                $perfil = "";
                if (isset($_SESSION["Perfil"])) $perfil = $_SESSION["Perfil"];

                if ($perfil == "Administrador" || $perfil == "Desenvolvedor" || $funcao == "Publicador") {

                    $link_Atualiza = $Dominio_url_Atualiza . "/Manager/Atualiza_Conteudo_Autentica.php?Atualiza_Conteudo=sim&acao_usuario=ler&CONTEUDO_ID=" . $row["CONTEUDO_ID"] . "&Dominio_id=" . $Dominio_id_Atualiza . "&campo=" . $campo . "&secao_id=" . $_SESSION["secao_id"] . "&Funcionarios_id=" . $_SESSION["Funcionarios_id"] . "&Perfil_id=" . $_SESSION["Perfil_id"] . "&URL_Retorno=" . pagina() . "¨¨" . str_replace(".", "¨", linha_de_comando());

                    $cab_atualiza = "";
                }
            } catch (Exception $e) {
                acumula_erro("Problema ao gerar cabeçalho para atualização dinâmica de conteúdo (Perfil = " . $_SESSION["Perfil"] . ") - " . $e->getMessage());
            }



            $Secao_atual = 0;
            if (isset($row["CONTEUDO_SECAO_ATUAL"])) {
                if ($row["CONTEUDO_SECAO_ATUAL"] == "1") {
                    if (isset($_SESSION["secao_id"])) $Secao_atual = $_SESSION["secao_id"];
                } else {
                    if (isset($row["SECAO_ID"])) $Secao_atual = $row["SECAO_ID"];
                }
            } else {
                if (isset($row["SECAO_ID"])) $Secao_atual = $row["SECAO_ID"];
            }

            try {

                if (isset($row["CONTEUDO_CACHE"]) && isset($row["CONTEUDO_CACHE_data"])) {
                    if (time() > strtotime($row["CONTEUDO_CACHE_data"]) && $row["CONTEUDO_CACHE"] != "") {
                        try {
                            $CONTEUDO_CACHE_data = "";
                            if ($banco_dados == "SQL_SERVER") {
                                $CONTEUDO_CACHE_data = " getdate() ";
                                if ($row["CONTEUDO_CACHE"] == "15 min")  $CONTEUDO_CACHE_data = " getdate() + 0.01 ";
                                if ($row["CONTEUDO_CACHE"] == "hora") $CONTEUDO_CACHE_data = " getdate() + 0.05 ";
                                if ($row["CONTEUDO_CACHE"] == "dia") $CONTEUDO_CACHE_data = " getdate() + 1 ";
                                if ($row["CONTEUDO_CACHE"] == "semana") $CONTEUDO_CACHE_data = " getdate() + 7 ";
                                if ($row["CONTEUDO_CACHE"] == "mes") $CONTEUDO_CACHE_data = " getdate() + 30 ";
                            }
                            if ($banco_dados = "ORACLE") {
                                $CONTEUDO_CACHE_data = " SYSDATE ";
                                if ($row["CONTEUDO_CACHE"] == "15 min") $CONTEUDO_CACHE_data = " SYSDATE + 0.01 ";
                                if ($row["CONTEUDO_CACHE"] == "hora") $CONTEUDO_CACHE_data = " SYSDATE + 0.05 ";
                                if ($row["CONTEUDO_CACHE"] == "dia") $CONTEUDO_CACHE_data = " SYSDATE + 1 ";
                                if ($row["CONTEUDO_CACHE"] == "semana") $CONTEUDO_CACHE_data = " SYSDATE + 7 ";
                                if ($row["CONTEUDO_CACHE"] == "mes") $CONTEUDO_CACHE_data = " SYSDATE + 30 ";
                            }
                            if ($banco_dados = "MySql") {
                                $CONTEUDO_CACHE_data = " NOW() ";
                                if ($row["CONTEUDO_CACHE"] == "15 min") $CONTEUDO_CACHE_data = " NOW() + 15 MINUTE ";
                                if ($row["CONTEUDO_CACHE"] == "hora") $CONTEUDO_CACHE_data = " NOW() + 1 HOUR ";
                                if ($row["CONTEUDO_CACHE"] == "dia") $CONTEUDO_CACHE_data = " NOW() + SETINTERVAL 1 DAY ";
                                if ($row["CONTEUDO_CACHE"] == "semana") $CONTEUDO_CACHE_data = " NOW() + SETINTERVAL 7 DAY ";
                                if ($row["CONTEUDO_CACHE"] == "mes") $CONTEUDO_CACHE_data = " NOW() + SETINTERVAL 30 DAY ";
                            }

                            mysqli_query($con, "update conteudo SET CONTEUDO_CACHE_data = " . $CONTEUDO_CACHE_data . " where CONTEUDO_ID = " . $row["CONTEUDO_ID"]);
                        } catch (Exception $e) {
                            acumula_erro("Erro ao atualizar data do cache template interno (" . $Conteudo_campo . ") - " . $e->getMessage());
                        }
                    } else {
                        if ($row["CONTEUDO_CACHE"] != "") {

                            //template_arquivo = Application("Dominio_caminho_cache") . "/" . $Conteudo_campo . "_" . Secao_atual
                            $template_arquivo = $Caminho_fisico_Cache . $Conteudo_campo . "_" . $Secao_atual;
                            $template = $Conteudo_campo . "_" . $Secao_atual;
                            if ($CONTEUDO_TIPO != "Lista") {
                                $template_arquivo = $template_arquivo . ".htm";
                                $template = $template . ".htm";
                            } else {
                                $pagina_atual = $pagina_atual_conteudo;
                                if ($pagina_atual == "") $pagina_atual = 1;
                                $linhas_por_pagina = 20;
                                if (isset($row["CONTEUDO_LINHAS_POR_PAGINA"])) $linhas_por_pagina = $row["CONTEUDO_LINHAS_POR_PAGINA"];
                                if ($_SESSION["paginacao_" . $Conteudo_campo] == "combo") $pagina_atual = 1;

                                $template_arquivo = $template_arquivo . "_" . $pagina_atual . ".htm";
                                $template = $template . "_" . $pagina_atual . ".htm";
                            }

                            try {

                                if (file_exists($template_arquivo)) {
                                    $handle = fopen($template_arquivo, "r");
                                    $Conteudo = fread($handle, filesize($template_arquivo));
                                    fclose($handle);
                                } else {
                                    //acumula_erro("Erro ao LER cache template interno (" . $Conteudo_campo . ") - Arquivo não encontrado!")
                                }
                            } catch (Exception $e) {
                                acumula_erro("Erro ao LER cache template interno (" . $Conteudo_campo . " - " . $template_arquivo . ") - " . $e->getMessage());
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                acumula_erro("Erro ao iniciar template interno (" . $Conteudo_campo . ") - " . $e->getMessage());
            }


            if (isset($row["CONTEUDO_TEMPLATE"]) || isset($row["CONTEUDO_TEMPLATE_MENU1"])) {

                if ($CONTEUDO_TIPO != "Menu") {

                    $conteudo_tratado = trim($row["CONTEUDO_TEMPLATE"]);

                    $Sql = "select institucional.Institucional_data, Institucional_data_cadastro, Institucional_data_expira, institucional.secao_id_principal, institucional.Funcionarios_id, Institucional_nome, Institucional_fonte, Institucional_autor, Institucional_legenda, institucional.Mascara_id, ";

                    if (strpos($conteudo_tratado, "Institucional_data") !== false) {
                        $conteudo_data = True;
                    }
                    if (strpos($conteudo_tratado, "Institucional_nome") !== false) {
                        $conteudo_nome = True;
                        //$Sql = $Sql . " Institucional_nome, "
                    }
                    if (strpos($conteudo_tratado, "Institucional_descricao") !== false) {
                        $conteudo_descricao = True;
                        $Sql = $Sql . " Institucional_descricao, ";
                    }
                    if (strpos($conteudo_tratado, "Institucional_texto") !== false) {
                        $conteudo_texto = True;
                    }
                    if (strpos($conteudo_tratado, "Institucional_url") !== false) {
                        $conteudo_url = True;
                        $Sql = $Sql . " Institucional_url, ";
                    }
                    if (strpos($conteudo_tratado, "Institucional_autor") !== false) {
                        $conteudo_autor = True;
                        //$Sql = $Sql . " Institucional_autor, "
                    }
                    if (strpos($conteudo_tratado, "Institucional_arquivo") !== false) {
                        $conteudo_arquivo = True;
                        $Sql = $Sql . " Institucional_arquivo2, Institucional_arquivo,";
                    }
                    if (strpos($conteudo_tratado, "Institucional_tamanho") !== false) {
                        $conteudo_tamanho = True;
                        $Sql = $Sql . " Institucional_arquivo2_tamanho, Institucional_arquivo_tamanho, ";
                    }
                    if (strpos($conteudo_tratado, "Institucional_fonte") !== false) {
                        $conteudo_fonte = True;
                        //$Sql = $Sql . " Institucional_fonte, "
                    }
                    if (strpos($conteudo_tratado, "Institucional_legenda") !== false) {
                        $conteudo_legenda = True;
                        //$Sql = $Sql . " Institucional_legenda, "
                    }
                    if (strpos($conteudo_tratado, "secao_nome") !== false) {
                        $conteudo_secao_nome = True;
                    }
                    if (strpos($conteudo_tratado, "secao_link") !== false) {
                        $conteudo_secao_link = True;
                    }
                    if (strpos($conteudo_tratado, "secao_descricao") !== false) {
                        $conteudo_secao_descricao = True;
                    }
                    if (strpos($conteudo_tratado, "conteudo_secao_id") !== false) {
                        $conteudo_secao_id = True;
                    }
                    if (strpos($conteudo_tratado, "Institucional_imagem_pq") !== false) {
                        $conteudo_imagem_pq = True;
                    }
                    if (strpos($conteudo_tratado, "Institucional_imagem_gr") !== false) {
                        $conteudo_imagem_gr = True;
                    }

                    $Sql_padrao = $Sql . " institucional.Institucional_id ";
                }


                try {

                    switch ($CONTEUDO_TIPO) {
                            //========================================
                            // template
                            //========================================
                        case "Template":

                            if ($row["INSTITUCIONAL_ID"] != "" && $row["INSTITUCIONAL_ID"] != "0" && isset($row["INSTITUCIONAL_ID"])) {
                                if ($row["CONTEUDO_SECAO_ATUAL"] == "1") {
                                    $RS_Institucional = mysqli_query($con, "select Institucional_id from conteudo_secao_institucional where conteudo_secao_institucional.Secao_id = " . $Secao_atual . " and conteudo_secao_institucional.CONTEUDO_ID = " . $row["CONTEUDO_ID"]);

                                    if (mysqli_num_rows($RS_Institucional) > 0) {
                                        $Sql = $Sql_padrao . " from institucional, conteudo_secao_institucional where  institucional.Institucional_id = conteudo_secao_institucional.Institucional_id and conteudo_secao_institucional.Secao_id = " . $Secao_atual . " and conteudo_secao_institucional.CONTEUDO_ID = " . $row["CONTEUDO_ID"]  . " ";
                                    } else {
                                        $Sql = $Sql_padrao . " from institucional, institucional_secao where institucional.Institucional_id = institucional_secao.Institucional_id and institucional_secao.secao_id = " . $Secao_atual . " ";
                                    }
                                } else {
                                    $Sql = $Sql_padrao . " from institucional where  institucional.Institucional_id = " . $row["INSTITUCIONAL_ID"] . " ";
                                }
                            } else {
                                if ($row["CONTEUDO_SECAO_ATUAL"] == "1") {
                                    $Sql = $Sql_padrao . " from institucional, institucional_secao where institucional.Institucional_id = institucional_secao.Institucional_id and institucional_secao.secao_id = " . $Secao_atual;
                                } else {
                                    $Sql = $Sql_padrao . " from institucional, institucional_secao, conteudo_secao where institucional.Institucional_id = institucional_secao.Institucional_id and institucional_secao.secao_id = conteudo_secao.secao_id and conteudo_secao.CONTEUDO_ID  = " . $row["CONTEUDO_ID"] . " ";
                                }
                            }

                            if (isset($row["Conteudo_secao_principal"])) {
                                if ($row["Conteudo_secao_principal"] == "2") {
                                    if ($Secao_atual != 0) $Sql = $Sql . " and secao_id_principal = " . $Secao_atual . " ";
                                }
                                if ($row["Conteudo_secao_principal"] == "3") {
                                    if ($Secao_atual != 0) $Sql = $Sql . " and secao_id_principal <> " . $Secao_atual . " ";
                                }
                            }

                            $Institucional_link = "";
                            if (isset($row["Conteudo_relacionado"])) {
                                if ($row["Conteudo_relacionado"] == "1" && isset($_SESSION["Institucional_id"])) {
                                    $Sql = $Sql . "  and (institucional.Institucional_id = 0 ";

                                    $dt = mysqli_query($con, "select Institucional_nome, Institucional_link, secao_id_principal from institucional where Institucional_id = " . $_SESSION["Institucional_id"]);
                                    if (mysqli_num_rows($dt) > 0) {
                                        $row_dt = mysqli_fetch_assoc($dt);
                                        if (isset($row_dt["Institucional_link"])) $Institucional_link = $row_dt["Institucional_link"];
                                    }

                                    if ($Institucional_link != "") {
                                        $Institucional_link = str_replace(",,", ",", $Institucional_link);
                                        $Institucional_link = str_replace(",", " or institucional.Institucional_id = ", $Institucional_link);
                                        $Sql = $Sql . $Institucional_link;
                                    }

                                    $Sql = $Sql . ")";
                                }
                            }


                            if ($row["MASCARA_ID"] != "0") $Sql = $Sql . " and institucional.Mascara_id = " . $row["MASCARA_ID"] . "  ";

                            if ($banco_dados == "SQL_SERVER") $Sql = $Sql . " and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( Getdate() between Institucional_data_inicial and Institucional_data_expira))) ";
                            if ($banco_dados == "ORACLE") $Sql = $Sql . " and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( SYSDATE  between Institucional_data_inicial and Institucional_data_expira))) ";
                            if ($banco_dados == "MySql") $Sql = $Sql . " and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( CURDATE()  between Institucional_data_inicial and Institucional_data_expira))) ";

                            if (isset($_SESSION["where"])) $Sql = $Sql . " " . $_SESSION["where"] . " ";
                            $_SESSION["where"] = "";


                            if (isset($row["Mascara_id_despresar"])) {
                                if ($row["Mascara_id_despresar"] != "") $Sql = $Sql . str_replace(",", " and Mascara_id <> ", $row["Mascara_id_despresar"]);
                            }

                            if ($row["CONTEUDO_ALEATORIO"] == "1") {

                                $Sql =  $Sql . " ORDER BY RAND() limit 1 ";
                            } else {

                                if ($row["CONTEUDO_ATUAL"] == "1") $Sql = $Sql . " ORDER BY Institucional_data DESC ";
                            }

                            //echo ("<!-- QUERY: " . $Sql . " -->");

                            $_SESSION["sql_query"] = $Sql;

                            $RS_Institucional = mysqli_query($con, str_replace("TOP 1", "", $Sql));

                            $n = mysqli_num_rows($RS_Institucional);

                            if ($n > 0) {

                                $row_RS = mysqli_fetch_assoc($RS_Institucional);
                                $_SESSION["Institucional_id"] = $row_RS["Institucional_id"];
                                Verifica_CONTEUDO_EXPIRADO($row["CONTEUDO_AVISA_EDITOR"], $row_RS["Institucional_id"], $row_RS["Funcionarios_id"], $row_RS["Institucional_data_expira"], $row_RS["Institucional_nome"]);

                                if ($row["CONTEUDO_EXPIRADO"] == "1" && strtotime($row_RS["Institucional_data_expira"]) < strtotime(date("Y-m-d"))) {
                                    $Conteudo = $CONTEUDO_DEFAULT;
                                } else {
                                    $CONTEUDO_TEMPLATE = $conteudo_tratado;

                                    $CONTEUDO_TEMPLATE = Atualiza_conteudo($row_RS, $CONTEUDO_TEMPLATE, 0, $conteudo_secao_descricao, $conteudo_secao_id, $conteudo_secao_link, $conteudo_tamanho, $conteudo_fonte, $conteudo_autor, $conteudo_legenda, $conteudo_secao_nome, $conteudo_data, $conteudo_nome, $conteudo_descricao, $conteudo_arquivo, $conteudo_url, $conteudo_texto, $conteudo_imagem_pq, $conteudo_imagem_gr);

                                    if ($conteudo_texto || $conteudo_imagem_pq || $conteudo_imagem_gr) {
                                        $CONTEUDO_TEMPLATE = Atualiza_imagem($CONTEUDO_TEMPLATE, $conteudo_texto, $conteudo_imagem_pq, $conteudo_imagem_gr, $row_RS["Institucional_id"]);
                                    }

                                    if ($conteudo_secao_nome || $conteudo_secao_id || $conteudo_secao_link || $conteudo_secao_descricao) {
                                        $CONTEUDO_TEMPLATE = Atualiza_secao($CONTEUDO_TEMPLATE, $conteudo_secao_nome, $conteudo_secao_id, $conteudo_secao_descricao, $conteudo_secao_link, $row_RS["secao_id_principal"], $row_RS["Institucional_id"]);
                                    }

                                    $CONTEUDO_TEMPLATE = Galeria_arquivos_conteudo_relacionado($CONTEUDO_TEMPLATE, $row_RS["Institucional_id"]);

                                    $Conteudo = $CONTEUDO_TEMPLATE;
                                }
                            } else {
                                $Conteudo = $CONTEUDO_DEFAULT;
                            }

                            //$Conteudo = "<atualiza_conteudo style=\"width: 20px; height: 29px; border:2px;border: 2px;border-color: brown;border-style: solid;float: left; position: absolute;\"></atualiza_conteudo>" . $Conteudo;

                            //========================================
                            // detalhe
                            //========================================

                            break;
                        case "Detalhe":

                            if ($campo != "") {

                                //Verifica se foi selecionado para preview.
                                if (isset($_REQUEST["preview"])) {
                                    $conteudo_template = $conteudo_tratado;
                                    $conteudo_template = str_replace('Institucional_nome', $_REQUEST["nome"], $conteudo_template);
                                    $conteudo_template = str_replace('Institucional_imagem_pq', $Dominio_url_producao . '/Manager/show_image.php?show_arquivo=institucional&show_campo=institucional_imagem_pq&show_chave=Institucional_id=' . $_REQUEST["id"], $conteudo_template);
                                    $conteudo_template = str_replace('Institucional_imagem_gr', $Dominio_url_producao . '/Manager/show_image.php?show_arquivo=institucional&show_campo=institucional_imagem_gr&show_chave=Institucional_id=' . $_REQUEST["id"], $conteudo_template);
                                    if (isset($_REQUEST["descricao"])) $conteudo_template = str_replace('Institucional_descricao', $_REQUEST["descricao"], $conteudo_template);
                                    $conteudo_template = str_replace('Institucional_texto', $_REQUEST["conteudo"], $conteudo_template);
                                    if (isset($_REQUEST["fonte"])) $conteudo_template = str_replace('Institucional_fonte', $_REQUEST["fonte"], $conteudo_template);
                                    if (isset($_REQUEST["legenda"])) $conteudo_template = str_replace('Institucional_legenda', $_REQUEST["legenda"], $conteudo_template);
                                    if (isset($_REQUEST["autor"])) $conteudo_template = str_replace('Institucional_autor', $_REQUEST["autor"], $conteudo_template);

                                    $conteudo_template = Galeria_arquivos_conteudo_relacionado($conteudo_template, $_REQUEST["id"]);
                                    $Conteudo = $conteudo_template;
                                }

                                //Pega detalhes do conteúdo.
                                else {
                                    $_SESSION["Institucional_id"] = $campo;

                                    $Data_consulta = "";
                                    if ($banco_dados == "SQL_SERVER") $Data_consulta = " Getdate() ";
                                    if ($banco_dados == "ORACLE") $Data_consulta = " SYSDATE ";
                                    if ($banco_dados == "MySql") $Data_consulta = " CURDATE() ";

                                    $RS_Institucional = mysqli_query($con, $Sql_padrao . " from institucional where Institucional_id = " . $_SESSION["Institucional_id"] . " and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( " . $Data_consulta . "  between Institucional_data_inicial and Institucional_data_expira)))");

                                    $_SESSION["sql_query"] = $Sql_padrao . " from institucional where Institucional_id = " . $_SESSION["Institucional_id"] . " and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( " . $Data_consulta . "  between Institucional_data_inicial and Institucional_data_expira)))";

                                    if ($banco_dados == "SQL_SERVER" || $banco_dados == "ACCESS" || $banco_dados == "ORACLE" || $banco_dados == "MySql") {

                                        $n =  mysqli_num_rows($RS_Institucional);

                                        if ($n > 0) {
                                            $row_RS = mysqli_fetch_assoc($RS_Institucional);
                                            $CONTEUDO_TEMPLATE = $conteudo_tratado;

                                            $CONTEUDO_TEMPLATE = Atualiza_conteudo($row_RS, $CONTEUDO_TEMPLATE, 0, $conteudo_secao_descricao, $conteudo_secao_id, $conteudo_secao_link, $conteudo_tamanho, $conteudo_fonte, $conteudo_autor, $conteudo_legenda, $conteudo_secao_nome, $conteudo_data, $conteudo_nome, $conteudo_descricao, $conteudo_arquivo, $conteudo_url, $conteudo_texto, $conteudo_imagem_pq, $conteudo_imagem_gr);

                                            if ($conteudo_texto || $conteudo_imagem_pq || $conteudo_imagem_gr) {
                                                $CONTEUDO_TEMPLATE = Atualiza_imagem($CONTEUDO_TEMPLATE, $conteudo_texto, $conteudo_imagem_pq, $conteudo_imagem_gr, $campo);
                                            }

                                            if ($conteudo_secao_nome || $conteudo_secao_id || $conteudo_secao_link || $conteudo_secao_descricao) {
                                                $CONTEUDO_TEMPLATE = Atualiza_secao($CONTEUDO_TEMPLATE, $conteudo_secao_nome, $conteudo_secao_id, $conteudo_secao_descricao, $conteudo_secao_link, $row_RS["secao_id_principal"], $campo);
                                            }

                                            $CONTEUDO_TEMPLATE = Galeria_arquivos_conteudo_relacionado($CONTEUDO_TEMPLATE, $row_RS["Institucional_id"]);

                                            $Conteudo = $CONTEUDO_TEMPLATE;
                                        } else {
                                            $Conteudo = $CONTEUDO_DEFAULT;
                                        }
                                    }
                                }
                            } else {
                                $Conteudo = $CONTEUDO_DEFAULT;
                            }

                            //========================================
                            // lista conteúdo
                            //========================================

                            break;
                        case "Lista":

                            $pagina_atual = $pagina_atual_conteudo;
                            if ($pagina_atual == "") $pagina_atual = 1;
                            $linhas_por_pagina = $row["CONTEUDO_LINHAS_POR_PAGINA"];
                            if (isset($_SESSION["paginacao_" . $Conteudo_campo])) {
                                if ($_SESSION["paginacao_" . $Conteudo_campo] == "combo") $pagina_atual = 1;
                            }
                            if (!is_numeric($linhas_por_pagina) || ($linhas_por_pagina == "")) $linhas_por_pagina = 20;

                            $Sql = str_replace("select", "select distinct", $Sql_padrao);

                            if ($Secao_atual == "") $Secao_atual = 0;

                            if ($row["CONTEUDO_SECAO_ATUAL"] == "1") {
                                $Sql = $Sql . " from institucional, institucional_secao where  institucional.Institucional_id = institucional_secao.Institucional_id and institucional_secao.secao_id = " . $Secao_atual . " ";
                            } else {
                                $Sql = $Sql . " from institucional, institucional_secao, conteudo_secao where institucional.Institucional_id = institucional_secao.Institucional_id and institucional_secao.secao_id = conteudo_secao.secao_id and conteudo_secao.CONTEUDO_ID  = " . $row["CONTEUDO_ID"] . " ";
                            }

                            if (isset($row["Conteudo_secao_principal"])) {
                                if ($row["Conteudo_secao_principal"] == "2") {
                                    if ($Secao_atual != 0) $Sql = $Sql . " and secao_id_principal = " . $Secao_atual . " ";
                                }
                                if ($row["Conteudo_secao_principal"] == "3") {
                                    if ($Secao_atual != 0) $Sql = $Sql . " and secao_id_principal <> " . $Secao_atual . " ";
                                }
                            }

                            $Institucional_link = "";
                            if (isset($row["Conteudo_relacionado"])) {
                                if ($row["Conteudo_relacionado"] == "1" && isset($_SESSION["Institucional_id"])) {
                                    $Sql = $Sql . "  and (institucional.Institucional_id = 0 ";

                                    $dt = mysqli_query($con, "select Institucional_nome, Institucional_link, secao_id_principal from institucional where Institucional_id = " . $_SESSION["Institucional_id"]);
                                    if (mysqli_num_rows($dt) > 0) {
                                        $row_DT = mysqli_fetch_assoc($dt);
                                        if (isset($row_DT["Institucional_link"])) $Institucional_link = $row_DT["Institucional_link"];
                                    }

                                    if ($Institucional_link != "") {
                                        $Institucional_link = str_replace(",,", ",", $Institucional_link);
                                        $Institucional_link = str_replace(",", " or institucional.Institucional_id = ", $Institucional_link);
                                        $Sql = $Sql . $Institucional_link;
                                    }

                                    $Sql = $Sql . ")";
                                }
                            }

                            if ($row["MASCARA_ID"] != "0") $Sql = $Sql . " and institucional.Mascara_id = " . $row["MASCARA_ID"] . "  ";

                            if (isset($_SESSION["where"])) $Sql = $Sql . " " . $_SESSION["where"] . " ";
                            $_SESSION["where"] = "";


                            if (isset($row["Mascara_id_despresar"])) {
                                if ($row["Mascara_id_despresar"] != "") $Sql = $Sql . str_replace(",", " and Mascara_id <> ", $row["Mascara_id_despresar"]);
                            }

                            $ordem = "";
                            if ($row["CONTEUDO_ALEATORIO"] == "1") {
                                $ordem = " RAND() ";
                            } else {
                                if (isset($row["CONTEUDO_ORDEM1"])) {
                                    if ($row["CONTEUDO_ORDEM1"] != "") $ordem = $row["CONTEUDO_ORDEM1"] . " " . $row["CONTEUDO_ORDEM1_TIPO"];
                                }
                                if (isset($row["CONTEUDO_ORDEM2"])) {
                                    if ($row["CONTEUDO_ORDEM2"] != "") $ordem = $ordem . ", " . $row["CONTEUDO_ORDEM2"] . " " . $row["CONTEUDO_ORDEM2_TIPO"];
                                }
                            }

                            if (isset($_SESSION["ordem"])) $ordem = $_SESSION["ordem"];

                            if ($ordem == "") $ordem = " institucional.Institucional_id asc ";

                            if ($banco_dados == "SQL_SERVER") $Sql = $Sql . " and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( Getdate()  between Institucional_data_inicial and Institucional_data_expira))) ORDER BY " . $ordem;
                            if ($banco_dados == "ORACLE") $Sql = $Sql . " and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( SYSDATE  between Institucional_data_inicial and Institucional_data_expira))) ORDER BY " . $ordem;
                            if ($banco_dados == "MySql") $Sql = $Sql . " and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( CURDATE()  between Institucional_data_inicial and Institucional_data_expira))) ORDER BY " . $ordem;

                            $_SESSION["paginacao_" . $Conteudo_campo] = "";
                            $_SESSION["pagina_inicial_" . $Conteudo_campo] = "";
                            $_SESSION["ultima_pagina_" . $Conteudo_campo] = "";
                            $_SESSION["proxima_pagina_" . $Conteudo_campo] = "";
                            $_SESSION["pagina_anterior_" . $Conteudo_campo] = "";
                            $_SESSION["linhas_por_pagina_" . $Conteudo_campo] = "";

                            $da = mysqli_query($con, $Sql);
                            $n = mysqli_num_rows($da);

                            //echo "<!-- TEMPLATE: " . $Conteudo_campo . " QUERY: " . $Sql . "-->";


                            if ($n > 0) {

                                $_SESSION["numero_registros_" . $Conteudo_campo] = $n;
                                $_SESSION["numero_paginas_" . $Conteudo_campo] = $n / $linhas_por_pagina;
                                if (($n / $linhas_por_pagina) > $_SESSION["numero_paginas_" . $Conteudo_campo]) {
                                    $_SESSION["numero_paginas_" . $Conteudo_campo] = $_SESSION["numero_paginas_" . $Conteudo_campo] + 1;
                                }

                                $_SESSION["pagina_atual_" . $Conteudo_campo] = $pagina_atual;
                                $_SESSION["linhas_por_pagina_" . $Conteudo_campo] = $linhas_por_pagina;

                                //echo $Sql . " LIMIT " . (($pagina_atual * $linhas_por_pagina) - $linhas_por_pagina) . "," . $linhas_por_pagina;

                                $da = mysqli_query($con, $Sql . " LIMIT " . (($pagina_atual * $linhas_por_pagina) - $linhas_por_pagina) . "," . $linhas_por_pagina);
                                $n = mysqli_num_rows($da);

                                $CONTEUDO_TEMPLATE_final = "";
                                $contador = 0;
                                $inicio = ($pagina_atual - 1) * $linhas_por_pagina;
                                $fim = $pagina_atual * $linhas_por_pagina;
                                if ($n <= $fim) $fim = $n;


                                while ($RS1 = mysqli_fetch_array($da)) {

                                    $contador = $contador + 1;
                                    $CONTEUDO_TEMPLATE = $conteudo_tratado;
                                    $CONTEUDO_TEMPLATE = str_replace("contador", $contador, $CONTEUDO_TEMPLATE);
                                    if (isset($_SESSION["secao_id"])) $CONTEUDO_TEMPLATE = str_replace("secao_atual", $_SESSION["secao_id"], $CONTEUDO_TEMPLATE);

                                    $CONTEUDO_TEMPLATE = Atualiza_conteudo($RS1, $CONTEUDO_TEMPLATE, $i, $conteudo_secao_descricao, $conteudo_secao_id, $conteudo_secao_link, $conteudo_tamanho, $conteudo_fonte, $conteudo_autor, $conteudo_legenda, $conteudo_secao_nome, $conteudo_data, $conteudo_nome, $conteudo_descricao, $conteudo_arquivo, $conteudo_url, $conteudo_texto, $conteudo_imagem_pq, $conteudo_imagem_gr);

                                    if ($conteudo_texto || $conteudo_imagem_pq || $conteudo_imagem_gr) {
                                        $CONTEUDO_TEMPLATE = Atualiza_imagem($CONTEUDO_TEMPLATE, $conteudo_texto, $conteudo_imagem_pq, $conteudo_imagem_gr, $RS1["Institucional_id"]);
                                    }

                                    if ($conteudo_secao_nome || $conteudo_secao_id || $conteudo_secao_link || $conteudo_secao_descricao) {
                                        $CONTEUDO_TEMPLATE = Atualiza_secao($CONTEUDO_TEMPLATE, $conteudo_secao_nome, $conteudo_secao_id, $conteudo_secao_descricao, $conteudo_secao_link, $RS1["secao_id_principal"], $RS1["Institucional_id"]);
                                    }

                                    $CONTEUDO_TEMPLATE_final = $CONTEUDO_TEMPLATE_final . $CONTEUDO_TEMPLATE;

                                    if ($row["CONTEUDO_QUEBRA_LINHA"] != "") {
                                        if ($row["CONTEUDO_QUEBRA_LINHA"] == $contador) {
                                            $CONTEUDO_TEMPLATE_final = $CONTEUDO_TEMPLATE_final . $row["CONTEUDO_COD_QUEBRA_LINHA"];
                                            $contador = 0;
                                        }
                                    }
                                }

                                if (isset($row["CONTEUDO_CACHE"])) {

                                    try {
                                        $template = $Caminho_fisico_Cache . DIRECTORY_SEPARATOR . $Conteudo_campo . "_" . $Secao_atual . "_" . $pagina_atual . ".htm";

                                        file_put_contents($template, "<!-- conteudo Web Caching - " . $Conteudo_campo . "_" . $Secao_atual . "_" . $pagina_atual . ".htm -->" . $CONTEUDO_TEMPLATE_final . "<!-- Fim do conteudo Web Caching -->");
                                    } catch (Exception $e) {
                                        acumula_erro("Erro ao GRAVAR o cache de paginação (" . $Conteudo_campo . ") em cache - " . $e->getMessage());
                                    }
                                }

                                if ($_SESSION["numero_paginas_" . $Conteudo_campo] > 1) {
                                    $i_max = $pagina_atual;
                                    $_SESSION["pagina_inicial_" . $Conteudo_campo] = pagina() . "?pagina_atual_" . $Conteudo_campo . "=1&" . str_replace(linha_de_comando(), "pagina_atual_" . $Conteudo_campo, "p");
                                    $_SESSION["ultima_pagina_" . $Conteudo_campo] = pagina() . "?pagina_atual_" . $Conteudo_campo . "=" . $_SESSION["numero_paginas_" . $Conteudo_campo] . "&" . str_replace("pagina_atual_" . $Conteudo_campo, "p", linha_de_comando());
                                    if ($pagina_atual != $_SESSION["numero_paginas_" . $Conteudo_campo]) $_SESSION["proxima_pagina_" . $Conteudo_campo] = pagina() . "?pagina_atual_" . $Conteudo_campo . "=" . ($pagina_atual + 1) . "&" . str_replace("pagina_atual_" . $Conteudo_campo, "p", linha_de_comando());
                                    if ($pagina_atual != 1) $_SESSION["pagina_anterior_" . $Conteudo_campo] = pagina() . "?pagina_atual_" . $Conteudo_campo . "=" . ($pagina_atual - 1) . "&" . str_replace("pagina_atual_" . $Conteudo_campo, "p", linha_de_comando());
                                }
                            } else {

                                $CONTEUDO_TEMPLATE_final = $CONTEUDO_DEFAULT;
                            }

                            $Conteudo = $CONTEUDO_TEMPLATE_final;



                            //========================================
                            // menu
                            //========================================

                            break;
                        case "Menu":

                            $CONTEUDO_TEMPLATE = menu($Secao_atual, $row["CONTEUDO_TEMPLATE_MENU1"], $row["CONTEUDO_TEMPLATE_MENU2"], $row["CONTEUDO_TEMPLATE_MENU3"], $row["CONTEUDO_TEMPLATE_MENU4"], $row["CONTEUDO_TEMPLATE_MENU5"]);
                            $Conteudo = $CONTEUDO_TEMPLATE;

                            break;
                        default:
                            $Conteudo = " Conteúdo dinâmico sem associação. ";
                    }
                } catch (Exception $e) {
                    acumula_erro("Erro ao carregar conteúdo para o template (" . $Conteudo_campo . ") - " . $e->getMessage() . " - " . $Sql);
                }
            }

            if (isset($row["CONTEUDO_CACHE"])) {

                try {
                    if ($row["CONTEUDO_CACHE"] != "") {
                        $template_arquivo = $Caminho_fisico_Cache . DIRECTORY_SEPARATOR . $Conteudo_campo . "_" . $Secao_atual;
                        if ($CONTEUDO_TIPO != "Lista") {
                            $template_arquivo = $template_arquivo . ".htm";
                        } else {
                            $pagina_atual = $pagina_atual_conteudo;
                            if ($pagina_atual = "") $pagina_atual = 1;
                            $linhas_por_pagina = $row["CONTEUDO_LINHAS_POR_PAGINA"];
                            if ($_SESSION["paginacao_" . $Conteudo_campo] == "combo") $pagina_atual = 1;
                            if (!is_numeric($linhas_por_pagina) || $linhas_por_pagina == "") $linhas_por_pagina = 5;

                            $template_arquivo = $template_arquivo . "_" . $pagina_atual . ".htm";
                        }

                        file_put_contents($template_arquivo, "<!-- conteudo Web Caching - " . $Conteudo_campo . "_" . $Secao_atual . ".htm -->" . $Conteudo . "<!-- Fim do conteudo Web Caching -->");
                    }
                } catch (Exception $e) {
                    acumula_erro("Erro ao GRAVAR o template interno (" . $Conteudo_campo . ") em cache - " . $e->getMessage());
                }
            }

            if ($cab_atualiza != "") {
                $Conteudo = $cab_atualiza . $Conteudo . "</span>";
            }
        } catch (Exception $e) {
            acumula_erro("Erro ao carregar conteúdo para o template interno (" . $Conteudo_campo . ") - " . $e->getMessage());
        }
    } else {
        if ($Conteudo_campo != "" && (strpos($Conteudo_campo, ".php") === false)) {

            try {
                $CONTEUDO_ID = Seq_MDB("Conteudo");

                mysqli_query($con, "insert into conteudo (CONTEUDO_ID, Conteudo_url, CONTEUDO_TIPO, Conteudo_campo, idioma_id, Mascara_id, CONTEUDO_SECAO_ATUAL, Institucional_id, CONTEUDO_TEMPLATE, CONTEUDO_DEFAULT, CONTEUDO_CACHE, Conteudo_secao_principal, CONTEUDO_ALEATORIO, CONTEUDO_ATUAL, CONTEUDO_EXPIRADO) VALUES ('" . $CONTEUDO_ID . "', '" . 1 . "', '" . $CONTEUDO_TIPO . "', '" . $Conteudo_campo . "', " . $_SESSION["idioma_id"] . ", 0, 0, 0, '','<!-- -->','',1,0,0,'')");
            } catch (Exception $e) {
                acumula_erro("Erro ao criar o template interno (" . $Conteudo_campo . ") - " . $e->getMessage());
            }
        }
    }



    $_SESSION["where"] = "";
    return $Conteudo;
}