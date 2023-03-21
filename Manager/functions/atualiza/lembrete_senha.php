<?php
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