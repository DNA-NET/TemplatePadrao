<?php
	ini_set("register_globals", "1");
	ini_set('session.gc_maxlifetime', 3600);

    if(!isset($_SESSION)) 
    { 
        session_cache_expire(3600);
        session_start();

		if(!isset($_SESSION["Funcionarios_id"])) {
			$_SESSION["idioma_id"]  = "";
			$_SESSION["Funcionarios_id"]  = "";
			$_SESSION["idioma_id"]  = "1";
			$_SESSION["Perfil"]  = "";
			$_SESSION["perfil"]  = "";
			$_SESSION["Perfil_id"]  = "";
			$_SESSION["funcao"]  = "";
			$_SESSION["username"]  = "";
			$_SESSION["Dominio_local_arquivos"]  = "";
			$_SESSION["Dominio_url_producao"]  = "";
			$_SESSION["Dominio_banco_dados"]  = "";
			$_SESSION["bd_conn"]  = "";	
		}
    }


    $GLOBALS["PageTitle"] = "::. template .::";
    $GLOBALS["Dominio_url_producao"] = "/template";
    $GLOBALS["_Gerenciador_url"] = "/template/Default.php";
    $GLOBALS["servidor_email"] = "192.168.1.206";
    $GLOBALS["email_Administrador"] = "adriano@dnanet.com.br";
    $GLOBALS["Dominio_id"] = "5";
    $GLOBALS["caminho_fisico_Cache"] = "d:\wamp\www\ww2\template\cache";
    $GLOBALS["caminho_fisico"] = "d:\wamp\www\ww2\template";
    $GLOBALS["URL_Login"] = "/template/Manager/Login.php";
    $GLOBALS["caminhoPermissao"] = "/template/Manager/Permissao.php";
    $GLOBALS["Linhas_por_pagina_busca"] = "10";
    $GLOBALS["Dominio_url_producao"] = "/template";
    $GLOBALS["DefaultCulture"] = "pt-BR";
    $GLOBALS["PageSize"] = "20";
    $GLOBALS["GaleriaImagem_PageSize"] = "16";
	$GLOBALS["bd"] = "template";

?>
<script>
//window.location.href = "Manager/Default.php";
window.location.href = "home";
</script>