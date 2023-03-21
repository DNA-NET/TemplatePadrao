<?php
//==================================================================================================================
//       Função para ligin e acesso de usu&aacute;rio
//==================================================================================================================
function Autentica_usuario(
    $username,
    $senha
) {

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