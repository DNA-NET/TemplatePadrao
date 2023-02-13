<?php

use Elastic\Elasticsearch\ClientBuilder;

$i = "";
$n = "";
$strSql = "";
$Linhas_por_pagina_busca  = "50";
$banco_dados  = "MySql";
$servidor_email  = $Dominio_SMTP;
$Dominio_url_producao = $_SESSION["Dominio_url_producao"];
$Dominio_url_menu  = $Dominio_Pasta_menu;
$Dominio_url_Atualiza  = $_SESSION["Dominio_url_producao"];
$Dominio_id_Atualiza  = "1";
$Caminho_fisico_Cache  = $_SERVER['DOCUMENT_ROOT'] . $_SESSION["Dominio_url_producao"] . "/cache";
$Atualiza_caminhoPermissao = $_SESSION["Dominio_url_producao"] . "/portal/login.php";
$_SESSION["idioma_id"] = "1";


function inicio()
{

    global $Atualiza_caminhoPermissao, $con, $Dominio_url_producao, $Dominio_url_menu, $Dominio_url_viagem, $Dominio_url_museu;

    $secao_id = "";
    $campo = "";
    if (isset($_REQUEST["secao_id"])) $secao_id = $_REQUEST["secao_id"];
    if (isset($_REQUEST["campo"])) $campo = $_REQUEST["campo"];

    if ($secao_id == "") {
        $Url_amigavel = str_replace($Dominio_url_producao . '/portal/', '/', strtok($_SERVER["REQUEST_URI"], "?"));
        $Url_amigavel = str_replace($Dominio_url_menu . '/', '/', $Url_amigavel);
        //$Url_amigavel = str_replace($Dominio_url_museu . '/', '/', $Url_amigavel);
        //$Url_amigavel = str_replace($Dominio_url_viagem . '/', '/sesc-viagens/', $Url_amigavel);

        //echo "url=" . $Url_amigavel;
        //exit;
        $RS1 = mysqli_query($con, "select secao_id, campo, Url_tipo from url_amigavel where Url_amigavel='" . $Url_amigavel . "'");

        if (mysqli_num_rows($RS1) > 0) {
            $row = mysqli_fetch_assoc($RS1);
            $secao_id = $row["secao_id"];
            $_SESSION["secao_id"] = $secao_id;
            $_SESSION["Url_tipo"] = $row["Url_tipo"];
            if ($row["campo"] != null) $campo = $row["campo"];
        } else {
            //header("Location: /site/portal/default.php?erro=pagina-inexistente");
            //if ((strpos($_SERVER["REQUEST_URI"], ".php") === false) && (strpos($_SERVER["REQUEST_URI"], "=") === false)) echo "<script>window.location='/site/portal/pagina-nao-encontrada?p=" . $_SERVER["REQUEST_URI"] . "';</script>";
        }
    }

    $_SESSION["campo"] = $campo;


    if ($secao_id != "") {
        if (strpos($secao_id, ",") === false) {
            try {
                Busca_secao($secao_id, "cinzamediohome");
                if (isset($_REQUEST["secao_dna"])) $_SESSION["inicio"] = "<script>secao_dna=''; id_secao='" . $secao_id . "'; secao_dna='" . $_SESSION["secao_dna"] . "-" . $_SESSION["secao_id"] . "-';</script>";

                //if ($secao_id != null) Estatistica("Seção", $secao_id.ToString());
                //if ($_REQUEST["campo"] != null) Estatistica("Conteúdo", $_REQUEST["campo"].ToString());

                if (isset($_SESSION["secao_controle"])) {

                    if (($_SESSION["secao_controle"] != '') && (strpos($_SERVER['SCRIPT_NAME'], "manager") === false)) {
                        if ($_SESSION["secao_controle"] == "Restrita" || $_SESSION["secao_controle"] == "Restrita_Aparente") {
                            $verificaPermissao = VerificaPermissao_Perfil($secao_id);
                            if ($verificaPermissao != "") {
                                //subst redirect por transfer.
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

function VerificaPermissao_Perfil($secao_id)
{
}

//==================================================================================================================
//	Seleciona o idioma do site
//==================================================================================================================

function Idioma()
{

    $_SESSION["idioma_id"] = "1";
}



//==================================================================================================================
//	Busca a secao corrente da navegação pelos menus dinâmicos
//==================================================================================================================

function Busca_secao($secao_id_navegacao, $classe)
{

    global $con;

    $secao_principal = "";
    $secao_nivel_1 = "";
    $secao_nivel_2 = "";
    $secao_dna = "";
    $secao_link = "";
    $secao_nome = "";
    $secao_id = "";
    $concatenador = "";
    $SECAO_APARECE_SITE = "";
    $tamanho  = "";
    $secao_nome_ordem = "";
    $secao_menu = "";

    $_SESSION["erro"] = "";

    if ($secao_id_navegacao != "") {

        try {

            $_SESSION["secao_id"] = $secao_id_navegacao;
            $secao_principal = "";
            $secao_nivel_1 = "";
            $secao_nivel_2 = "";

            $RS1 = mysqli_query($con, "select secao_id, secao_nome, secao_nome_ordem, secao_ordem, secao_link, secao_dna, secao_controle, secao_descricao, secao_visitas, SECAO_APARECE_SITE, secao_menu from secao where secao_id=" . $secao_id_navegacao);

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
                    $concatenador = "?";

                    //$tamanho = 4;
                    //If (strlen($secao_dna) > 0) $tamanho = strlen($secao_dna) - strrpos("-" . $secao_dna, "--");

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

                    $RS2 = mysqli_query($con, "select secao_id, secao_nome, secao_ordem, secao_link, secao_dna, SECAO_APARECE_SITE from secao where secao_id = " . $id_dna);

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

                //If ($_SESSION["navegacao"] != "") $_SESSION["navegacao"] = left($_SESSION["navegacao"], strlen($_SESSION["navegacao"]) - 2);
                if ($_SESSION["navegacao_nome"] != "") $_SESSION["navegacao_nome"] = left($_SESSION["navegacao_nome"], strlen($_SESSION["navegacao_nome"]) - 1);
                $_SESSION["navegacao"] = str_replace("Portal", "Home", $_SESSION["navegacao"]);

                mysqli_query($con, "update secao SET secao_visitas=secao_visitas+1 where Secao_id =" . $_SESSION["secao_id"]);
            }
        } catch (Exception $e) {
            echo "<b>OPS</b>:Problema na função Busca_secao: ERRO=" . $e->getMessage();
        }
    }
}

function Busca_secao_menu()
{

    global $con;

    $secao_menu = '';

    if (isset($_SESSION["secao_id"]) && isset($_SESSION["secao_dna"])) {

        $niveis = $_SESSION["secao_dna"] . "-" . $_SESSION["secao_id"];
        $niveis = substr($niveis, - (strlen($niveis) - 1));
        $niveis_array = str_replace("--", ",", $niveis);

        $RS2 = mysqli_query($con, "select secao_id from secao where secao_menu = 'Sim' and secao_id in(" . $niveis_array . ")");

        if (mysqli_num_rows($RS2) > 0) {
            $row2 = mysqli_fetch_assoc($RS2);
            $secao_menu =  $row2["secao_id"];
        }
    }

    return $secao_menu;
}

function Busca_secao_dna($secao_id)
{

    global $con;

    $RS2 = mysqli_query($con, "select secao_id, secao_dna from secao where secao_id = " . $secao_id);

    if (mysqli_num_rows($RS2) > 0) {
        $row2 = mysqli_fetch_assoc($RS2);
        return $row2["secao_dna"];
    } else {
        return "";
    }
}

function Busca_secao_nome($secao_id)
{

    global $con;

    $RS2 = mysqli_query($con, "select secao_id, secao_nome from secao where secao_id = " . $secao_id);

    if (mysqli_num_rows($RS2) > 0) {
        $row2 = mysqli_fetch_assoc($RS2);
        return $row2["secao_nome"];
    } else {
        return "";
    }
}

//==================================================================================================================
//       Retorna o ID da Seção de nível desejado
//==================================================================================================================

function Busca_secao_nivel($nivel)
{
    $niveis = "";
    $niveis_total = 0;

    try {

        if (isset($_SESSION["secao_dna"])) {
            $niveis = $_SESSION["secao_dna"] . "-" . $_SESSION["secao_id"];
            $niveis = substr($niveis, - (strlen($niveis) - 1));
            $niveis_array = explode("--", $niveis);
            $niveis_total = count($niveis_array);


            if ($niveis_total > $nivel) {
                return $niveis_array[$nivel];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    } catch (Exception $e) {
        $_SESSION["erro"] = ("Erro Busca_secao_nivel " . $nivel . "> " . $e->getMessage());
        return 0;
    }
}

//==================================================================================================================
//       Retorna o nome da Seção de nível desejado
//==================================================================================================================

function Busca_secao_nome2($nivel)
{
    $niveis = "";
    $niveis_total = "";

    try {

        $niveis = $_SESSION["navegacao_nome"];
        $niveis_array = explode(",", $niveis);
        $niveis_total = count($niveis_array);

        if ($niveis_total >= $nivel) {
            return $niveis_array($nivel);
        } else {
            return "";
        }
    } catch (Exception $e) {
        acumula_erro("Erro Busca_secao_nome " . nivel . "> " . $e->getMessage());
        return 0;
    }
}

//==================================================================================================================
//       Indexação autom&aacute;tica de tabelas
//==================================================================================================================

function Seq_MDB($tabela_nome)
{

    global $con;

    $RSTestaSeq = mysqli_query($con, "select Seq_Tabela_id from seq where Seq_Tabela='$tabela_nome'");
    if (!@mysqli_num_rows($RSTestaSeq)) {
        mysqli_query($con, "insert INTO seq (Seq_Tabela_id, Seq_Tabela) VALUES (1,'$tabela_nome')");
        $Seq_Tabela_id = 1;
    } else {

        while ($RSTesteResultado = mysqli_fetch_array($RSTestaSeq)) {
            $Seq_Tabela_id = $RSTesteResultado["Seq_Tabela_id"] + 1;
        }
        mysqli_query($con, "update seq SET Seq_Tabela_id='$Seq_Tabela_id' where Seq_Tabela ='$tabela_nome'");
    }
    $Seq = $Seq_Tabela_id;

    return $Seq;
}

//=================================================================================================================
//      Função para impressão do conteúdo dinâmico
//      Datas em oracle: to_date('" . strtotime(Now) . "')
//      Datas em MDB: #" . strtotime(Now) . "#
//      Datas em SQL: Getdate()  ou CONVERT(datetime,'" . Date . " " . FormatDateTime(Now(), 3) . "',103)
//=================================================================================================================

function Conteudo($Conteudo_campo)
{

    global $Dominio_url_producao, $banco_dados, $Caminho_fisico_Cache, $con;

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
                            if ($banco_dados == "ACCESS") {
                                $CONTEUDO_CACHE_data = " #" . strtotime(Today()) . "# ";
                                if ($row["CONTEUDO_CACHE"] == "15 min") $CONTEUDO_CACHE_data = " #" . strtotime(Today()) . "# + 0.01 ";
                                if ($row["CONTEUDO_CACHE"] == "hora") $CONTEUDO_CACHE_data = " #" . strtotime(Today()) . "# + 0.05 ";
                                if ($row["CONTEUDO_CACHE"] == "dia") $CONTEUDO_CACHE_data = " #" . strtotime(Today()) . "# + 1 ";
                                if ($row["CONTEUDO_CACHE"] == "semana") $CONTEUDO_CACHE_data = " #" . strtotime(Today()) . "# + 7 ";
                                if ($row["CONTEUDO_CACHE"] == "mes") $CONTEUDO_CACHE_data = " #" . strtotime(Today()) . "# + 30 ";
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
                            if ($banco_dados == "ACCESS") $Sql = $Sql . " and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( #" . strtotime(Today()) . "# between Institucional_data_inicial and Institucional_data_expira))) ";
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
                                    if ($banco_dados == "ACCESS") $Data_consulta = " #" . strtotime(Today()) . "# ";
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
                            if ($banco_dados == "ACCESS") $Sql = $Sql . " and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( #" . strtotime(Today()) . "#  between Institucional_data_inicial and Institucional_data_expira))) ORDER BY " . $ordem;
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

function pagina()
{

    return "";
}

function linha_de_comando()
{

    return "";
}

function mes_extenso($mes_numerico)
{

    $mes = "";

    switch ($mes_numerico) {
        case "01":
            $mes = "Janeiro";
            break;
        case "02":
            $mes = "Fevereiro";
            break;
        case "03":
            $mes = "Março";
            break;
        case "04":
            $mes = "Abril";
            break;
        case "05":
            $mes = "Maio";
            break;
        case "06":
            $mes = "Junho";
            break;
        case "07":
            $mes = "Julho";
            break;
        case "08":
            $mes = "Agosto";
            break;
        case "09":
            $mes = "Setembro";
            break;
        case "10":
            $mes = "Outubro";
            break;
        case "11":
            $mes = "Novembro";
            break;
        case "12":
            $mes = "Dezembro";
            break;
    }

    return $mes;
}


//=================================================================================================================
//       Função para atualização de dados do conteúdo no template
//=================================================================================================================

function Atualiza_conteudo($rowC, $CONTEUDO_TEMPLATE, $i, $conteudo_secao_descricao, $conteudo_secao_id, $conteudo_secao_link, $conteudo_tamanho, $conteudo_fonte, $conteudo_autor, $conteudo_legenda, $conteudo_secao_nome, $conteudo_data, $conteudo_nome, $conteudo_descricao, $conteudo_arquivo, $conteudo_url, $conteudo_texto, $conteudo_imagem_pq, $conteudo_imagem_gr)
{


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
            //Exibe o título do conteúdo na URL para facilitar a compreensão e indexação nas ferramentas de busca -- 22/01/2012
            //$CONTEUDO_TEMPLATE = str_replace($CONTEUDO_TEMPLATE, "$campo=", "c=" . removeAcentos($rowC["Institucional_nome"].str_replace(" ", "-")) . ".$campo=")
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

function Galeria_arquivos_conteudo_relacionado($CONTEUDO_TEMPLATE, $id)
{

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
        //if(mysqli_num_rows($RS_Arquivos) > 0) $template_arquivos_final = "<h3 style='margin-left:15px; margin-top:15px;'>Galeria de Imagens</h3>";
        $count_break = 0;
        while ($RSAnexo = mysqli_fetch_array($RS_Arquivos)) {
            $template_arquivos = str_replace('indice', $RSAnexo["Imagem_id"], $template_anexo);
            $template_arquivos = str_replace('Imagem_nome', $RSAnexo["Imagem_titulo"], $template_arquivos);
            $template_arquivos = str_replace('Imagem_fonte', $RSAnexo["Imagem_fonte"], $template_arquivos);
            $template_arquivos = str_replace('Imagem_descricao', $RSAnexo["Imagem_descricao"], $template_arquivos);
            //$template_arquivos = str_replace('Imagem_pq', 'data:image/jpg;base64,' . base64_encode($RSAnexo["Imagem_thumb"]), $template_arquivos);
            $template_arquivos = str_replace("Imagem_pq", $Dominio_url_producao . "/Manager/show_image.php?show_arquivo=galeria_imagem&show_campo=Imagem_thumb&show_chave=Imagem_id=" . $RSAnexo["Imagem_id"], $template_arquivos);
            //$template_arquivos = str_replace('Imagem_gr', 'data:image/jpg;base64,' . base64_encode($RSAnexo["Imagem"]), $template_arquivos);
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
        //if(mysqli_num_rows($RS_Arquivos) > 0) {$template_arquivos_final = "<h2 style='margin-top:15px;'>Veja tamb&eacute;m</h2><ul class=\"list-icon spaced check-circle\">";}
        $n = mysqli_num_rows($RS_Arquivos);
        if ($n > 0) {
            while ($RSAnexo = mysqli_fetch_array($RS_Arquivos)) {
                $template_arquivos = str_replace('metatag_palavra', $RSAnexo["metatag_palavra"], $template_anexo);
                $template_arquivos_final .= $template_arquivos;
            }
        } else {
            $CONTEUDO_TEMPLATE .= "<script type=\"text/javascript\">document.getElementById('listatags').style.display='none';</script>";
        }
        //$template_arquivos_final .= "</ul>";
        $CONTEUDO_TEMPLATE = str_replace('<!--TAGS', '', $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace($template_anexo, $template_arquivos_final, $CONTEUDO_TEMPLATE);
        $CONTEUDO_TEMPLATE = str_replace('TAGS-->', '', $CONTEUDO_TEMPLATE);
    }

    return $CONTEUDO_TEMPLATE;
}


//=================================================================================================================
//       Função para atualização de imagens e textos no template
//=================================================================================================================

function Atualiza_imagem($CONTEUDO_TEMPLATE, $conteudo_texto, $conteudo_imagem_pq, $conteudo_imagem_gr, $Institucional_id)
{

    $campos = "";
    global $Dominio_url_producao, $con;

    try {

        if ($conteudo_texto) $campos = $campos . "Institucional_texto, ";
        if ($conteudo_imagem_pq) $campos = $campos . "Institucional_imagem_pq, ";
        if ($conteudo_imagem_gr) $campos = $campos . "Institucional_imagem_gr";
        if (right($campos, 2) == ", ") $campos = left($campos, strlen($campos) - 2);

        $da2 = mysqli_query($con, "select " . $campos . " from institucional where Institucional_id = " . $Institucional_id);

        //echo "select " . $campos . " from institucional where Institucional_id = " . $Institucional_id;

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
                    //$CONTEUDO_TEMPLATE = str_replace("Institucional_imagem_pq", 'data:image/jpg;base64,' . base64_encode($rowI["Institucional_imagem_pq"]), $CONTEUDO_TEMPLATE);
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
                    //$CONTEUDO_TEMPLATE = str_replace("Institucional_imagem_gr", 'data:image/jpg;base64,' . base64_encode($rowI["Institucional_imagem_gr"]), $CONTEUDO_TEMPLATE);
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

//=================================================================================================================
//      Função para atualização de dados de seçao no template
//=================================================================================================================

function Atualiza_secao($CONTEUDO_TEMPLATE, $conteudo_secao_nome, $conteudo_secao_id, $conteudo_secao_descricao, $conteudo_secao_link, $secao_id_principal, $Institucional_id)
{

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

//=================================================================================================================
//      Função para montagem de menus dinâmicos até 5 níveis
//=================================================================================================================

function menu($secao_id, $nivel1, $nivel2, $nivel3, $nivel4, $nivel5)
{

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
            //if (isset($_SESSION["Secoes_permitidas"])) $query = "(Secao_id = " . str_replace(",", " or Secao_id = ", $_SESSION["Secoes_permitidas"]) . ")";

            $Sql = "";
            if (isset($_SESSION["where"])) $Sql = " " . $_SESSION["where"] . " ";
            $limite = "";
            if (isset($_SESSION["limite"])) $limite = " " . $_SESSION["limite"] . " ";

            $_SESSION["where"] = "";

            $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $secao_id . "-") . ") = '-" . $secao_id . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem " . $limite;
            $Rs_secao = mysqli_query($con, $Query_Secao);

            if (mysqli_num_rows($Rs_secao) > 0) {
                while ($row1 = mysqli_fetch_array($Rs_secao)) {
                    $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel1, $row1, $i);

                    if ($nivel2 != "") {
                        $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row1["secao_id"] . "-") . ") = '-" . $row1["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem " . $limite;
                        $Rs_secao2 = mysqli_query($con, $Query_Secao);

                        if (mysqli_num_rows($Rs_secao2) > 0) {
                            while ($row2 = mysqli_fetch_array($Rs_secao2)) {
                                $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel2, $row2, $ii);

                                if ($nivel3 != "") {
                                    $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row2["secao_id"] . "-") . ") = '-" . $row2["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                    $Rs_secao3 = mysqli_query($con, $Query_Secao);

                                    if (mysqli_num_rows($Rs_secao3) > 0) {
                                        while ($row3 = mysqli_fetch_array($Rs_secao3)) {
                                            $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel3, $row3, $iii);

                                            if ($nivel4 != "") {
                                                $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row3["secao_id"] . "-") . ") = '-" . $row3["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                                $Rs_secao4 = mysqli_query($con, $Query_Secao);

                                                if (mysqli_num_rows($Rs_secao4) > 0) {
                                                    while ($row4 = mysqli_fetch_array($Rs_secao4)) {
                                                        $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel4, $row4, $iiii);

                                                        if ($nivel5 != "") {
                                                            $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row4["secao_id"] . "-") . ") = '-" . $row4["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                                                            $Rs_secao5 = mysqli_query($con, $Query_Secao);

                                                            if (mysqli_num_rows($Rs_secao5) > 0) {
                                                                while ($row5 = mysqli_fetch_array($Rs_secao5)) {
                                                                    $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu($nivel5, $row5, $iiiii);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            acumula_erro("Erro ao montar menu (secao_id = " . $secao_id . ") - " . $e->getMessage());
        }
    }

    return $CONTEUDO_TEMPLATE;
}


//=================================================================================================================
//      Função para montagem de menus dinâmicos até 5 níveis
//=================================================================================================================

function menu_plus($secao_id, $nivel1_antes, $nivel1, $nivel1_depois, $nivel2_antes, $nivel2, $nivel2_depois, $nivel3_antes, $nivel3, $nivel3_depois, $nivel4_antes, $nivel4, $nivel4_depois, $nivel5_antes, $nivel5, $nivel5_depois)
{

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
            //if (isset($_SESSION["Secoes_permitidas"])) $query = "(Secao_id = " . str_replace(",", " or Secao_id = ", $_SESSION["Secoes_permitidas"]) . ")";

            $Sql = "";
            if (isset($_SESSION["where"])) $Sql = " " . $_SESSION["where"] . " ";
            $_SESSION["where"] = "";

            $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $secao_id . "-") . ") = '-" . $secao_id . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem ";
            $Rs_secao = mysqli_query($con, $Query_Secao);

            if (mysqli_num_rows($Rs_secao) > 0) {
                while ($row1 = mysqli_fetch_array($Rs_secao)) {
                    if ($nivel1_antes != "") $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . $nivel1_antes . "\r\n";

                    if ($nivel2 != "") {
                        $Query_Secao = "select SECAO_APARECE_SITE, secao_id, secao_nome, secao_dna, secao_link, secao_descricao, secao_abertura, secao_imagem from secao where SECAO_APARECE_SITE != '1' and RIGHT(secao_dna," . strlen("-" . $row1["secao_id"] . "-") . ") = '-" . $row1["secao_id"] . "-'   and (secao_controle = 'Livre' or secao_controle = 'Restrita_Aparente' or (secao_controle = 'Restrita' and (" . $query . "))) " . $Sql . " order by secao_ordem";
                        $Rs_secao2 = mysqli_query($con, $Query_Secao);

                        if (mysqli_num_rows($Rs_secao2) > 0) {
                            $CONTEUDO_TEMPLATE = $CONTEUDO_TEMPLATE . Atualiza_menu(str_replace("<a ", "<a class=\"dropdown-toggle\" ", $nivel1), $row1, $i);
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


//=================================================================================================================
//      2ª Função para montagem de menus dinâmicos até 5 níveis
//=================================================================================================================

function menu_plus2($secao_id, $nivel1_antes, $nivel1, $nivel1_depois, $nivel2_antes, $nivel2, $nivel2_depois, $nivel3_antes, $nivel3, $nivel3_depois, $nivel4_antes, $nivel4, $nivel4_depois, $nivel5_antes, $nivel5, $nivel5_depois)
{

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


function Imagem_Secao($secao_id, $imagem_default)
{

    global $con;

    $Rs_imagem = mysqli_query($con, "select secao_id, secao_imagem from secao where secao_id="  . $secao_id);

    if (mysqli_num_rows($Rs_imagem) > 0) {
        $row2 = mysqli_fetch_assoc($Rs_imagem);
        if (isset($row2["secao_imagem"])) {
            $imagem_default =  ('data:image/jpg;base64,' . base64_encode($row2["secao_imagem"]));
        }
    }

    return ($imagem_default);
}

function Atualiza_menu($nivel, $row2, $i)
{

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

function Link_secao($secao_id, $campo)
{

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


//=================================================================================================================
//       Função para envio de e-mail de aviso para o Publicador no caso de conteúdo expirado
//=================================================================================================================

function Verifica_CONTEUDO_EXPIRADO($CONTEUDO_AVISA_EDITOR, $Institucional_id, $Funcionarios_id, $Institucional_data_expira, $Institucional_nome)
{

    $data_expira_conteudo = "";
    $Corpo_email = "";

    $todays_date = date("Y-m-d");
    $today = strtotime($todays_date);
    $expiration_date = strtotime($Institucional_data_expira);

    try {

        $data_expira_conteudo = 7;
        if (DataBR($Institucional_data_expira)) {
            if ($CONTEUDO_AVISA_EDITOR == "1" && $Institucional_data_expira > $today) {
                $Corpo_email = "Conteúdo Expirado:" . Chr(13) . Chr(13);
                $Corpo_email = $Corpo_email . "Conteúdo ID:" . $Institucional_id . Chr(13);
                $Corpo_email = $Corpo_email . "Data Expiração:" . $Institucional_data_expira . Chr(13);
                $Corpo_email = $Corpo_email . "Conteúdo:" . $Institucional_nome . Chr(13);

                $RS_Adm = mysqli_query($con, "select email from funcionarios where Funcionarios_id = " . $Funcionarios_id);

                if (mysqli_num_rows($RS_Adm) > 0) {
                    while ($RS_email = mysqli_fetch_array($RS_Adm)) {
                        Envia_email($RS_email["email"], $RS_email["email"], "", "", "Conteúdo expirado ", 1, 1, $Corpo_email, "");
                    }
                }
            }
        }
    } catch (Exception $e) {
        acumula_erro("Erro ao enviar mensagem de conteúdo expirado para o Publicador (Funcionarios_id = " . $Funcionarios_id . ") do conteúdo (Institucional_id = " . $Institucional_id . ") - " . $e->getMessage());
    }
}



//=============================================================================================================
// Retorna resultado da busca em conteúdo por palavra chave
//=============================================================================================================

function Busca($String_busca, $pagina_atual, $nome_pagina, $secao_dna)
{

    global $Linhas_por_pagina_busca;
    global $con, $Dominio_url_producao;
    global $Dominio_url_menu;

    $resposta = "";
    $texto_final = "";
    $pode = "";
    $texto_meio = "";
    $query = "";
    $Sql = "";
    $inicio = "1";
    $fim = "";
    $paginas_final = "1";
    $linhas_por_pagina = "50";
    $passo_inicio = "";
    $inicio_texto = "";
    $passo_fim = "";
    $inicio_descricao = "";
    $i2 = "";

    $resposta = "Nenhum resultado encontrado para a busca!";

    try {
        $linhas_por_pagina = $Linhas_por_pagina_busca;
        if ($pagina_atual < 1) $pagina_atual = 1;

        $query = " secao.Secao_id = 0 ";
        $Sql = "";
        $sessaoDNA = str_replace("-", "", right($secao_dna, 4));
        $n = 0;
        $esClient = null;
        $esWhere = null;


        if (getenv("ELASTIC_SEARCH_ENABLED") === "1") {
            $elasticServerHostIP = "https://" . getenv("ELASTIC_SEARCH_IP") . ":" . getenv("ELASTIC_SERVER_PORT");

            $esClient = ClientBuilder::create()
                ->setHosts([$elasticServerHostIP])
                ->setBasicAuthentication("elastic", getenv("ELASTIC_SEARCH_PASSWORD"))
                ->setCABundle(getenv("ELASTIC_SEARCH_HTTP_CA_CRT_PATH"))
                ->build();

            $esWhere = [
                "index" => strtolower(getenv("ELASTIC_SEARCH_MAIN_INDEX")),
                "body"  => [
                    "query" => [
                        "combined_fields" => [
                            "query" => $String_busca,
                            "fields" => [
                                "institucional_nome",
                                "institucional_descricao",
                                "institucional_texto",
                                "institucional_arquivo_txt"
                            ]
                        ]
                    ]
                ]
            ];

            $response = $esClient->count($esWhere);
            $jsonResponse = json_decode($response->getBody(), true);
            $n = $jsonResponse["count"];
        } else {
            $strSQLWhere = "
                url_tipo = 'conteudo' AND
                (
                    secao_dna LIKE '%$secao_dna%' OR
                    secao.secao_id = $sessaoDNA
                ) AND
                secao.secao_id = institucional.secao_id_principal AND
                (
                    (
                        institucional_texto LIKE '%$String_busca%'
                    ) OR
                    (
                        institucional_descricao LIKE '%$String_busca%'
                    ) OR
                    (
                        institucional_nome LIKE '%$String_busca%'
                    )
                ) AND
                Institucional_status='Publicado' AND
                (
                    secao_controle = 'Livre' OR
                    (
                        secao_controle = 'Restrita' AND
                        ($query)
                    )
                )";


            $strSQLCount = "SELECT
                COUNT(Ids) as count FROM (
                    SELECT
                        institucional.institucional_id as Ids
                    FROM
                        institucional,
                        secao,
                        url_amigavel
                    WHERE
                        $strSQLWhere
                    GROUP BY
                        institucional.institucional_id
            ) as t1";

            $dt = mysqli_query($con, $strSQLCount);
            $n = (int)mysqli_fetch_assoc($dt)["count"];
        }


        if ($n > 0) {
            if ($n < $linhas_por_pagina) $linhas_por_pagina = $n;
            $resposta = '<p>Foram encontrados ' . $n . ' resultados para <b>' . $String_busca . '</b></p>';
            $paginas_final = $pagina_atual * $linhas_por_pagina;
            if ($paginas_final > $n) $paginas_final = $n;

            if ($n > 1) {
                $resposta = $resposta . "<b>P&aacute;gina " . $pagina_atual . " de " . ceil($n / $linhas_por_pagina) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;";
                if ($pagina_atual > 1) {
                    $resposta = $resposta . '<a href="' . $nome_pagina . '?palavra=' . $String_busca . '&pagina_atual=' . ($pagina_atual - 1) . '">Anterior</a> ';
                }
                if ($pagina_atual < ($n / $linhas_por_pagina)) {
                    $resposta = $resposta . '&nbsp;&nbsp;&nbsp;<a href="' . $nome_pagina . '?palavra=' . $String_busca . '&pagina_atual=' . ($pagina_atual + 1) . '">Pr&oacute;xima</a> ';
                }
            }

            $inicio = ($pagina_atual - 1) * $linhas_por_pagina;
            $fim = $pagina_atual * $linhas_por_pagina;
            if ($n <= $fim) $fim = $n;


            if (getenv("ELASTIC_SEARCH_ENABLED") === "1") {
                $response = $esClient->search($esWhere);

                $tmpDT = [];
                foreach ($response->asArray()["hits"]["hits"] as $RSPag) {
                    $tmpDT[] = $RSPag["_source"];
                }
                $dt = $tmpDT;
            } else {
                $Sql = "SELECT DISTINCT
                            institucional.institucional_id,
                            institucional_nome,
                            institucional_arquivo,
                            secao_nome,
                            secao_nome_ordem,
                            secao_link,
                            secao.secao_id,
                            url_amigavel
                        FROM
                            institucional,
                            secao,
                            url_amigavel
                        WHERE
                            $strSQLWhere
                        ORDER BY
                            secao_nome_ordem
                        LIMIT
                            $inicio,$linhas_por_pagina";


                $dt = mysqli_query($con, $Sql);
                $tmpDT = [];
                while ($RSPag = mysqli_fetch_array($dt)) {
                    $tmpDT[] = $RSPag;
                }
                $dt = $tmpDT;
            }


            $i = 0;
            $resposta = $resposta . "<p></p>";

            foreach ($dt as $RSPag) {
                $i += 1;
                if ((string)$RSPag["institucional_arquivo"] != "" && $RSPag["institucional_arquivo"] != "migradas.jpg") {
                    $downloadURL = $Dominio_url_producao . "/conteudo/" . $RSPag["institucional_arquivo"];
                    $resposta = $resposta . ("<h4>" . $i . '- <a href="' . $downloadURL . '"  target="_blank">' . str_replace(">", " > ", $RSPag["secao_nome_ordem"]) . "</a></h4><p>" . $RSPag["institucional_nome"] . "</p>");
                } else {
                    $resposta = $resposta . ("<h4 style=\"font-size:1.3em; margin-bottom:2px;\">" . $i . '- <a href="' . $Dominio_url_menu . $RSPag["url_amigavel"] . '"  >' . str_replace(">", " > ", str_replace("> HC > Home ", "", $RSPag["secao_nome_ordem"])) . "</a></h4><p>" . $RSPag["institucional_nome"] . "</p>");
                }
            }

            if ($n > 1) {
                $resposta = $resposta . "<br><br><b>P&aacute;gina " . $pagina_atual . " de " . ceil($n / $linhas_por_pagina) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;";
                if ($pagina_atual > 1) {
                    $resposta = $resposta . '<a href="' . $nome_pagina . "?palavra=" . $String_busca . "&pagina_atual=" . ($pagina_atual - 1) . '">Anterior</a> ';
                }
                if ($pagina_atual < ($n / $linhas_por_pagina)) {
                    $resposta = $resposta . '&nbsp;&nbsp;&nbsp;<a href="' . $nome_pagina . "?palavra=" . $String_busca . "&pagina_atual=" . ($pagina_atual + 1) . '">Pr&oacute;xima</a> ';
                }
            }
        } else {
            $resposta = '<span class="cinzamediohome"><b>Nenhum resultado encontrado para a busca!</b></span>';
        }
    } catch (Exception $e) {
        acumula_erro("Erro na busca > " . $e->getMessage());
    }

    return $resposta;
}


//=============================================================================================================
// Envio de emails
//=============================================================================================================

function Verifica_email_valido($emailAddress)
{

    if (strpos($emailAddress, '@') !== false) {
        return  True;
    } else {
        return False;
    }
}


function Envia_email_atualiza($from, $para, $cc, $bcc, $subject, $BodyFormat, $MailFormat, $Body, $AttachFile, $responder_para)
{

    global $servidor_email;

    try {
        ini_set("SMTP", $servidor_email);

        $to = $para;

        $message = "
			<html>
			<head>
			<title>" . $subject . "</title>
			</head>
			<body>" . $Body . "</body>
			</html>
			";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";

        // More headers
        $headers .= 'from: <' . $from . '>' . "\r\n";
        if ($cc != "") $headers .= 'Cc: ' . $cc . "\r\n";
        if ($bcc != "") $headers .= 'Bcc: ' . $bcc . "\r\n";
        if ($responder_para != "") $headers .= 'Reply-To: ' . $responder_para . "\r\n";

        mail($to, $subject, $message, $headers);
    } catch (Exception $e) {
        acumula_erro("Erro no envio de e-mail > " . $e->getMessage());
    }
}

function Envia_email_area($from, $para_area, $cc, $bcc, $subject, $BodyFormat, $MailFormat, $Body, $AttachFile)
{

    global $con;

    $dtA = mysqli_query($con, "select email_email from email where email_area='" . $para_area . "'");

    $n = mysqli_num_rows($dtA);

    if ($n > 0) {
        while ($RSE = mysqli_fetch_array($dtA)) {
            if (Verifica_email_valido($RSE["email_email"])) Envia_email($from, $RSE["email_email"], $cc, $bcc, $subject, $BodyFormat, $MailFormat, $Body, $AttachFile);
        }
    }
}


//==================================================================================================================
//       Função para montar combo com valores fixos de campo da mascara
//==================================================================================================================


function Combo_Mascara($Mascara_id, $Mascara_campo, $Mascara_campo_valor, $Condicao)
{

    global $con;
    $valores_combo = "";

    $dtA = mysqli_query($con, "select " . $Mascara_campo . " from mascara where Mascara_id=" . $Mascara_id . " order by " . $Mascara_campo);

    $n = mysqli_num_rows($dtA);

    if ($n > 0) {

        $rowA = mysqli_fetch_assoc($dtA);
        $parte = explode("\n", $rowA[$Mascara_campo]);

        for ($i = 0; $i < count($parte); $i++) {
            $selecionado = "";
            if (trim($Mascara_campo_valor) == trim($parte[$i])) $selecionado = " selected ";
            if (trim($Condicao . " - " . $Mascara_campo_valor) == trim($parte[$i])) $selecionado = " selected ";
            if ($Condicao != "") {
                if (strpos($parte[$i], $Condicao) !== false) $valores_combo .= "<option value='" . trim($parte[$i]) . "' " . $selecionado . ">" . trim($parte[$i]) . "</option>";
            } else {
                $valores_combo .= "<option value='" . trim($parte[$i]) . "' " . $selecionado . ">" . trim($parte[$i]) . "</option>";
            }
        }
    }

    return $valores_combo;
}


//==================================================================================================================
//       Função para ligin e acesso de usu&aacute;rio
//==================================================================================================================

function Autentica_usuario($username, $senha)
{

    global $con;

    $Logon = "";
    $perfil_acumulado = "";
    $query = "";

    $Logon = "";

    try {

        $dtL = mysqli_query($con, "select distinct Funcionarios_empresa, Funcionarios_telefone, inibe_geral, inibe_tabela, inibe_corretor, inibe_impressao, inibe_imagem, inibe_format, inibe_command, email, Funcionarios_arquivo_tamanho, Funcionarios_arquivo_ext, Funcionarios_editor, Funcionarios_nome, Funcionarios_telefone, Funcionarios.senha, Funcionarios.Funcionarios_id, Funcionarios_cpf from funcionarios where  username= '" . tira_aspas($username) . "' or ( email != '' and email= '" . tira_aspas($username) . "') or ( Funcionarios_cpf != '' and Funcionarios_cpf = '" . tira_aspas($username) . "')");

        if (mysqli_num_rows($dtL) > 0) {
            $row_log = mysqli_fetch_assoc($dtL);
            if (trim(strtoupper($row_log["senha"])) == trim(strtoupper($senha))) {
                $_SESSION["UserName"] = $username;
                $_SESSION["Password"] = $senha;

                $_SESSION["Funcionarios_nome"] = $row_log["Funcionarios_nome"];
                $_SESSION["Funcionarios_empresa"] = $row_log["Funcionarios_empresa"];
                $_SESSION["Funcionarios_telefone"] = $row_log["Funcionarios_telefone"];
                $_SESSION["Funcionarios_cpf"] = $row_log["Funcionarios_cpf"];
                $_SESSION["Funcionarios_editor"] = $row_log["Funcionarios_editor"];
                $_SESSION["Funcionarios_id"] = $row_log["Funcionarios_id"];
                $_SESSION["Funcionarios_arquivo_ext"] = $row_log["Funcionarios_arquivo_ext"];
                $_SESSION["Funcionarios_arquivo_tamanho"] = $row_log["Funcionarios_arquivo_tamanho"];
                $_SESSION["email"] = $row_log["email"];
                $_SESSION["Funcionarios_telefone"] = $row_log["Funcionarios_telefone"];
                $_SESSION["inibe_command"] = $row_log["inibe_command"];
                $_SESSION["inibe_format"] = $row_log["inibe_format"];
                $_SESSION["inibe_imagem"] = $row_log["inibe_imagem"];
                $_SESSION["inibe_impressao"] = $row_log["inibe_impressao"];
                $_SESSION["inibe_corretor"] = $row_log["inibe_corretor"];
                $_SESSION["inibe_tabela"] = $row_log["inibe_tabela"];
                $_SESSION["inibe_geral"] = $row_log["inibe_geral"];
                $Logon = "";
            } else {
                $Logon = "Senha Inv&aacute;lida.";
            }
        } else {
            $Logon = "Acesso não encontrado.";
        }

        if ($_SESSION["Funcionarios_id"] != "") {
            $dtL = mysqli_query($con, "select PERFIL.Perfil_id,  PERFIL.Superior_id, PERFIL.Perfil_nome, PERFIL.Perfil_funcao from perfil, funcionarios_perfil where Funcionarios_perfil.Perfil_id = PERFIL.Perfil_id and Funcionarios_perfil.Funcionarios_id=" . $_SESSION["Funcionarios_id"]);
            $n = mysqli_fetch_assoc($dtL);
            if ($n > 0) $row_log = mysqli_fetch_assoc($dtL);

            if (!isset($_SESSION["Perfil_id"]) && $n > 0) {
                $_SESSION["Superior_id"] = $row_log["Superior_id"];
                $_SESSION["Perfil_id"] = $row_log["Perfil_id"];
                $_SESSION["Perfil"] = $row_log["Perfil_nome"];
                $_SESSION["Funcao"] = $row_log["Perfil_funcao"];
            }
            $perfil_acumulado = "";

            while ($RS_perfil = mysqli_fetch_array($dtL)) {
                $perfil_acumulado = $perfil_acumulado . "-" . $RS_perfil["Perfil_id"];
            }
            $_SESSION["perfil_acumulado"] = $perfil_acumulado;

            if ($perfil_acumulado != "") {
                $query = "(Perfil_id = " . str_replace("-", " or Perfil_id = ", $perfil_acumulado) . ")";
                $_SESSION["Secoes_permitidas"] = "";
                $dtP = mysqli_query($con, "select Secao_id from perfil_secao_acesso where " . $query);
                while ($RS_acesso = mysqli_fetch_array($dtP)) {
                    $_SESSION["Secoes_permitidas"] = $_SESSION["Secoes_permitidas"] . $RS_acesso["secao_id"] . ",";
                }
                if (strlen($_SESSION["Secoes_permitidas"]) > 0) $_SESSION["Secoes_permitidas"] = left($_SESSION["Secoes_permitidas"], strlen($_SESSION["Secoes_permitidas"]) - 1);
            }
        }
    } catch (Exception $e) {
        acumula_erro("Erro na autenticação do usu&aacute;rio > " . $username . " - " . $e->getMessage());
    }

    return $Logon;
}


//==================================================================================================================
//	Envia Lembrete de senha
//==================================================================================================================

function lembrete_senha($username)
{

    $mensagem = "";
    global $con;

    if ($username != "") {

        $dt_log = mysqli_query($con, "select * from funcionarios where username = '" . tira_aspas($username) . "'");
        $n = mysqli_num_rows($dt_log);

        if ($n > 0) {
            $mensagem = "Senha enviada por email, confira.";
            $row_log = mysqli_fetch_assoc($dt_log);
            Envia_email("adm@adm.com.br", $row_log["email"], $row_log["email"], "", "Lembrete de senha de acesso", 0, 0, "Sr(a): " . $row_log["Funcionarios_nome"] . "<br><br>Lembrete de senha:<br><br>" . $row_log["senha"] . "<br><br>Administração<br>", "");
        } else {
            $mensagem = "Seu cadastro não foi encontrado!";
        }
    }
}

function acumula_erro_atualiza($erro)
{
    try {
        $_SESSION["erro"] = $_SESSION["erro"] . "<br>" . $erro;
    } catch (Exception $e) {
        $_SESSION["erro"] .= "Problemas com a rotina de erros - " . $e->getMessage();
    }

    echo $_SESSION["erro"];
}

function removeAcentos_atualiza($texto)
{

    $array1 = array("&aacute;", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "&oacute;", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "&aacute;", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "&oacute;", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
    $array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C");
    return str_replace($array1, $array2, $texto);
}

function DataBR($date)
{

    $date = str_replace("/", "-", substr($date, 0, 10));
    list($year, $month, $day) = explode('-', $date);
    if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) {
        return false;
    }
    if ($day > 1000) {
        $aux = $day;
        $day = $year;
        $year = $aux;
    }

    if (checkdate($month, $day, $year)) {
        return ($day . "/" . $month . "/" . $year);
    } else {
        return false;
    }
}


function paginacao($Conteudo_campo)
{

    if (isset($_SESSION["numero_registros_" . $Conteudo_campo])) {
        $pagina = 1;
        if (isset($_SESSION["pagina_atual_" . $Conteudo_campo])) $pagina = $_SESSION["pagina_atual_" . $Conteudo_campo];

        $total_registros = $_SESSION["numero_registros_" . $Conteudo_campo];
        $limite = $_SESSION["linhas_por_pagina_" . $Conteudo_campo];
        $controles = "<div style=\"clear: both; font-size: 16px;\">";

        if (!isset($_SESSION["secao_link"])) {
            $_SESSION["secao_link"] = "";
        }

        if ($total_registros > 0 &&  $limite > 0) {
            $useSessaoId = "secao_id=" . $_SESSION["secao_id"] . "&";
            if ($_SESSION["secao_id"] == 0) {
                $useSessaoId = "";
            }
            $total_paginas = Ceil($total_registros / $limite);

            $inicio = $pagina - 5;
            if ($inicio <= 0) $inicio = 1;

            $fim = $pagina + 5;
            if ($fim >= $total_paginas) $fim = $total_paginas;


            //if($pagina != 1) $controles .= '<a href="' . $_SESSION["secao_link"] . '?' . $useSessaoId . 'pagina_atual_'. $Conteudo_campo . '=1" OnClick="" class="btn btn-primary">Primeira p&aacute;gina</a>';
            if ($pagina != 1) $controles .= '<a href="?' . $useSessaoId . 'pagina_atual_' . $Conteudo_campo . '=1" OnClick="" class="btn btn-primary">Primeira p&aacute;gina</a>';
            for ($i = $inicio; $i <= $fim; $i++) {
                if ($pagina == $i) {
                    $controles .=  " | <b>" . $i . "</b> ";
                } else {
                    //$controles .=  '| <a href="' . $_SESSION["secao_link"] . '?' . $useSessaoId . 'pagina_atual_'. $Conteudo_campo . '='.$i.'" OnClick="Paginacao('.$i.');"> '.$i.'</a> ';
                    $controles .=  '| <a href="?' . $useSessaoId . 'pagina_atual_' . $Conteudo_campo . '=' . $i . '" OnClick="Paginacao(' . $i . ');"> ' . $i . '</a> ';
                }
            }
            //$controles .=  ' | <a href="' . $_SESSION["secao_link"] . '?' . $useSessaoId . 'pagina_atual_'. $Conteudo_campo . '='.$total_paginas.'" class="btn btn-primary"> &Uacute;ltima p&aacute;gina</a>';
            $controles .=  ' | <a href="?' . $useSessaoId . 'pagina_atual_' . $Conteudo_campo . '=' . $total_paginas . '" class="btn btn-primary"> &Uacute;ltima p&aacute;gina</a>';
            $controles .=  '<br><br>Total de <b>' . $total_registros . '</b> registros';
        }

        if (isset($_REQUEST["palavra"])) $controles = str_replace("?", "?palavra=" . $_REQUEST["palavra"] . "&", $controles);
        if (isset($_REQUEST["periodo"])) $controles = str_replace("?", "?periodo=" . $_REQUEST["periodo"] . "&", $controles);
        if (isset($_REQUEST["ano"])) $controles = str_replace("?", "?ano=" . $_REQUEST["ano"] . "&", $controles);

        $controles .=  '</div>';
        return $controles;
    } else {
        return '';
    }
}

//=============================================================================================================
// Gera URL Amigavel
//=============================================================================================================

function Gera_URL_Amigavel()
{

    global $con;

    if (isset($_REQUEST["secao_id"])) {
        if (strpos($_REQUEST["secao_id"], ",") === false) {
            try {
                $URL_amigavel = '/ipen';
                $URL_Original = $_SERVER["REQUEST_URI"];
                $dtA = mysqli_query($con, "select url_amigavel from url_amigavel where url_original = '" . $URL_Original . "'");

                if (mysqli_num_rows($dtA) > 0) {
                    $row1 = mysqli_fetch_assoc($dtA);
                    $URL_amigavel = $row1["url_amigavel"];
                } else {
                    $dtS = mysqli_query($con, "select secao_nome_ordem as nome from secao where secao_id = " . $_REQUEST["secao_id"]);

                    if (mysqli_num_rows($dtS) > 0) {
                        $row = mysqli_fetch_assoc($dtS);
                        $URL_amigavel .= Prepara_URL_Amigavel($row["nome"]);
                    }

                    $campo = "0";
                    if (isset($_REQUEST["campo"])) {
                        $campo = $_REQUEST["campo"];
                        $dtC = mysqli_query($con, "select institucional_nome as nome from institucional where institucional_id = " . $_REQUEST["campo"]);

                        if (mysqli_num_rows($dtC) > 0) {
                            $row = mysqli_fetch_assoc($dtC);
                            $URL_amigavel .= "/" . Prepara_URL_Amigavel($row["nome"]);
                        }
                    }

                    $URL_amigavel = str_replace("/portal_por/ipen", "/ipen", $URL_amigavel);

                    mysqli_query($con, "insert into url_amigavel (secao_id, campo, url_original, url_amigavel, url_data) values (" . $_REQUEST["secao_id"] . ", " . $campo . ", '" . $URL_Original . "', '" . $URL_amigavel . "', CURDATE())");
                }

?>

                <script type="text/javascript">
                    var stateObj = {
                        foo: "<?php echo $URL_amigavel ?>"
                    };

                    function change_my_url() {
                        history.pushState(stateObj, "page 2", "<?php echo $URL_amigavel ?>");
                    }

                    window.onload = function() {
                        change_my_url()
                    };
                </script>

<?php

            } catch (Exception $e) {
                $_SESSION["erro"] .= "Problemas com gerção da URL amigavel - " . $e->getMessage();
            }
        }
    }
}

function Prepara_URL_Amigavel_atualiza($url)
{
    $url_tratada = str_replace(",", "/", str_replace(">", "/", str_replace(" ", "-", trim($url))));
    return strtolower(preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $url_tratada)));
}


//==================================================================================================================
//       Registra a visita do usuário
//==================================================================================================================

function Registra_Visita($sessionID)
{
    global $con;
    $stmt = null;
    $visitante_id = null;

    $visitante_session_hash = $sessionID;
    $visitante_navegador = $_SERVER["HTTP_USER_AGENT"];
    $visitante_ip = get_client_ip();


    if ($visitante_ip !== "UNKNOW") {
        $yesterday = new DateTime();
        $yesterday->sub(new DateInterval("P1D"));
        $yesterday = $yesterday->format("Y-m-d") . " 00:00:00";

        $strSQL = " SELECT
                        visitante_id
                    FROM
                        visitante
                    WHERE
                        visitante_session_hash='$visitante_session_hash' AND
                        visitante_data_inicio>='$yesterday'
                    ORDER BY
                        visitante_id DESC
                    LIMIT 1;";

        $dt = mysqli_query($con, $strSQL);
        if (mysqli_num_rows($dt) > 0) {
            $row = mysqli_fetch_assoc($dt);
            $visitante_id = (int)$row["visitante_id"];
        }


        if ($visitante_id === null) {
            $visitante_ip_cidade = null;
            $visitante_ip_regiao = null;
            $visitante_ip_pais = null;
            $visitante_ip_postal = null;
            $visitante_ip_loc = null;
            $visitante_resolucao = null;

            $strSQL = " INSERT INTO visitante
                            (
                                visitante_session_hash, visitante_navegador, visitante_ip,
                                visitante_ip_cidade, visitante_ip_regiao, visitante_ip_pais, visitante_ip_postal, visitante_ip_loc,
                                visitante_resolucao
                            )
                            VALUES
                            (
                                ?, ?, ?,
                                ?, ?, ?, ?, ?,
                                ?
                            );";
            $stmt = $con->prepare($strSQL);
            $stmt->bind_param(
                "sssssssss",
                $visitante_session_hash,
                $visitante_navegador,
                $visitante_ip,

                $visitante_ip_cidade,
                $visitante_ip_regiao,
                $visitante_ip_pais,
                $visitante_ip_postal,
                $visitante_ip_loc,

                $visitante_resolucao
            );


            $curlRequest = curl_init();
            curl_setopt($curlRequest, CURLOPT_URL, getenv("ATUALIZA_IP_DATA_PROVIDER") . $visitante_ip);
            curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlRequest, CURLOPT_CONNECTTIMEOUT, 5);
            $curlResponse = curl_exec($curlRequest);
            $curlErrno = curl_errno($curlRequest);
            curl_close($curlRequest);

            if ($curlErrno === 0) {
                $curlResponse = json_decode($curlResponse, true);
                if ($curlResponse !== null && key_exists("city", $curlResponse) === true) {
                    $visitante_ip_cidade = $curlResponse["city"];
                    $visitante_ip_regiao = $curlResponse["region"];
                    $visitante_ip_pais = $curlResponse["country"];
                    $visitante_ip_postal = $curlResponse["postal"];
                    $visitante_ip_loc = $curlResponse["loc"];

                    $stmt->execute();
                }
            }

            $stmt->close();
        } else {
            $funcionarios_id = (isset($_SESSION["Funcionarios_id"]) === true) ? (int)$_SESSION["Funcionarios_id"] : "NULL";
            $strSQL = " UPDATE visitante
                            SET
                                visitante_data_fim=NOW(), funcionarios_id=$funcionarios_id
                            WHERE
                                visitante_id=$visitante_id;";
            mysqli_query($con, $strSQL);
        }
    }
}



function Atualiza_Visita($sessionID, $jsonData)
{
    global $con;
    $stmt = null;
    $visitante_id = null;
    $visitante_session_hash = $sessionID;
    $visitante_pagina_url = $jsonData["url"];

    $yesterday = new DateTime();
    $yesterday->sub(new DateInterval("P1D"));
    $yesterday = $yesterday->format("Y-m-d") . " 00:00:00";


    $strSQL = " SELECT
                    visitante_id
                FROM
                    visitante
                WHERE
                    visitante_session_hash='$visitante_session_hash' AND
                    visitante_data_inicio>='$yesterday'
                ORDER BY
                    visitante_id DESC
                LIMIT 1;";

    $dt = mysqli_query($con, $strSQL);
    if (mysqli_num_rows($dt) > 0) {
        $row = mysqli_fetch_assoc($dt);
        $visitante_id = (int)$row["visitante_id"];

        if ($visitante_id !== null) {
            $displayInfo = json_encode($jsonData["displayInfo"]);
            $strSQL = " UPDATE visitante
                            SET
                                visitante_resolucao='$displayInfo'
                            WHERE
                                visitante_id=$visitante_id AND
                                visitante_resolucao IS NULL;";
            mysqli_query($con, $strSQL);



            // Encerra data de visita de uma pagina anteriormente aberta.
            $strSQL = " UPDATE visitante_pagina
                            SET
                                visitante_pagina_data_fim=NOW()
                            WHERE
                                visitante_id=$visitante_id AND
                                visitante_pagina_data_fim IS NULL;";
            mysqli_query($con, $strSQL);



            // Cria o registro do acesso para a página atual
            $strSQL = " INSERT INTO visitante_pagina
                            (
                                visitante_pagina_url, visitante_id
                            )
                            VALUES
                            (
                                ?, ?
                            );";
            $stmt = $con->prepare($strSQL);
            $stmt->bind_param(
                "ss",
                $visitante_pagina_url,
                $visitante_id
            );

            $stmt->execute();
            $stmt->close();
        }
    }
}
?>