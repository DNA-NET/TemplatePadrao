<?php

//===================================================================================
// Verifica permissão de acesso ao aplicativo
//===================================================================================

function Permissao_Aplicativo()
{
    global $con, $Dominio_Pasta;
    $resultado = "";
    $_SESSION["Permissao_Acesso_inclui"] = "";
    $_SESSION["Permissao_Acesso_altera"] = "";
    $_SESSION["Permissao_Acesso_exclui"] = "";
    $_SESSION["Permissao_Acesso_auditoria"] = "";

    $RSApp = db_query(
        $con,
        "SELECT
            permissao_tabela.permissao_tabela_id, permissao_tabela_area, permissao_tabela_link,
            permissao_tabela_Nome, permissao_tabela_descricao, permissao_acesso_inclui,
            permissao_acesso_altera, permissao_acesso_exclui, permissao_acesso_auditoria
        FROM
            permissao_tabela, permissao_acesso
        WHERE
            permissao_tabela.permissao_tabela_id = permissao_acesso.permissao_tabela_id AND
            (
                permissao_tabela_link = '" . str_replace($Dominio_Pasta, '', $_SERVER['REQUEST_URI']) . "' OR
                permissao_tabela_link = '" . str_replace($Dominio_Pasta, '', $_SERVER['PHP_SELF']) . "'
            ) AND
            Perfil_id=" . $_SESSION["Perfil_id"]
    );

    if (db_num_rows($RSApp) > 0) {
        while ($RS3 = db_fetch_array($RSApp)) {

            $resultado = "<section class=\"page-title\">";
            $resultado .= "<div class=\"container\">";
            $resultado .= "<header>";
            $resultado .= "<ul class=\"breadcrumb\" style=\"float: right;\">";
            $resultado .= $RS3["permissao_tabela_area"] . ' > <a href="' . $Dominio_Pasta .  $RS3["permissao_tabela_link"] . '">' . $RS3["permissao_tabela_Nome"] . '</a>';
            $resultado .= "</ul>";
            $resultado .= "<h2>";
            $resultado .= $RS3["permissao_tabela_descricao"];
            $resultado .= "</h2>";
            $resultado .= "</header>";
            $resultado .= "</div>";
            $resultado .= "</section>";

            if ($RS3["permissao_acesso_inclui"] == 'Sim') $_SESSION["Permissao_Acesso_inclui"] = $RS3["permissao_acesso_inclui"];
            if ($RS3["permissao_acesso_altera"] == 'Sim') $_SESSION["Permissao_Acesso_altera"] = $RS3["permissao_acesso_altera"];
            if ($RS3["permissao_acesso_exclui"] == 'Sim') $_SESSION["Permissao_Acesso_exclui"] = $RS3["permissao_acesso_exclui"];
            if ($RS3["permissao_acesso_auditoria"] == 'Sim') $_SESSION["Permissao_Acesso_auditoria"] = $RS3["permissao_acesso_auditoria"];
        }

        if ($_SESSION["Permissao_Acesso_auditoria"] != 'Sim' && $_SESSION["Perfil_id"] != "1") {
            $_SESSION["mensagem"] = "Você não tem acesso a este aplicativo!";
        }
    } else {
        $_SESSION["mensagem"] = "Aplicativo <strong>" . str_replace($Dominio_Pasta, '', $_SERVER['REQUEST_URI']) . "</strong> não cadastrado!";
    }

    return $resultado;
}