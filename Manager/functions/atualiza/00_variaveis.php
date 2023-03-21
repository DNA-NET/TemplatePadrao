<?php



$i = "";
$n = "";
$strSql = "";
$Linhas_por_pagina_busca  = "50";
$banco_dados  = "mysql";
$Dominio_url_producao = $_SESSION["Dominio_url_producao"];
$Dominio_url_menu  = $Dominio_Pasta_menu;
$Dominio_url_Atualiza  = $_SESSION["Dominio_url_producao"];
$Dominio_id_Atualiza  = "1";
$Caminho_fisico_Cache  = $_SERVER['DOCUMENT_ROOT'] . $_SESSION["Dominio_url_producao"] . "/cache";
$Atualiza_caminhoPermissao = $_SESSION["Dominio_url_producao"] . "/portal/login.php";
$_SESSION["idioma_id"] = "1";