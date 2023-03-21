<?php



//
// Nao deve exigir login se a pagina for o portal
if (strlen(stristr($_SERVER["PHP_SELF"], "Manager")) > 0) {
    // Verifica se o usuário está logado... se não estiver redireciona-o à tela de login
    $acao = isset($_REQUEST["acao"])  ? $_REQUEST["acao"] : "";

    if ((!isset($_SESSION["Funcionarios_id"]) || $_SESSION["Funcionarios_id"] == "") &&
        (strtolower($_SERVER["PHP_SELF"]) != $Dominio_Pasta . "/manager/login.php") &&
        (strtolower($_SERVER["PHP_SELF"]) != $Dominio_Pasta . "/manager/show_image.php") &&
        (strtolower($_SERVER["PHP_SELF"]) != $Dominio_Pasta . "/manager/docs/wiki.php") &&
        $acao != "logon"
    ) {
        header("Location:" . $Dominio_Pasta . "/Manager/Login.php");
        exit();
    }
}





//
// Se o usuário desejar fazer logout, encerra sua sessão e o envia para o início da aplicação
$logout = (isset($_REQUEST["logout"])) ? strtolower($_REQUEST["logout"]) : NULL;
if ($logout == "s" || $logout == "sim") {

    if (isset($con)) {
        $DataDaAcao = new DateTime();
        $DataDaAcao = $DataDaAcao->format("Y-m-d H:i:s");

        db_query(
            $con,
            "UPDATE
                log_acesso
            SET
                log_acesso_data_logout='$DataDaAcao'
            WHERE
                log_acesso_id=" . $_SESSION["log_acesso_id"]
        );
    }

    session_unset();
    session_destroy();

    $redirectTo = getenv("ATUALIZA_MANAGER_MAIN_DIR") . getenv("ATUALIZA_MANAGER_URL_LOGIN");
    header("Location: $redirectTo?mensagem=Logout realizado com sucesso!");

    exit();
}
