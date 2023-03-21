<?php
//===================================================================================
// Verifica se já existe usuário cadastrado com mesmo CPF, username ou e-mail
//===================================================================================
function User_existente($cpf, $username, $email)
{

    global $con;
    $resultado = "";
    $useCPF = tira_pontos_tracos_cpf(trim(strtolower(tira_aspas($cpf))));
    $useEmail = trim(strtolower(tira_aspas($email)));
    $useUserName = trim(strtolower(tira_aspas($username)));

    $strSQL = " SELECT
                    Funcionarios_cpf, email, username
				FROM
					funcionarios
				WHERE
					( Funcionarios_cpf IS NOT NULL AND Funcionarios_cpf = '$useCPF') OR
					( email IS NOT NULL AND email = '$useEmail') OR
					( username IS NOT NULL AND LOWER(username) = '$useUserName' AND username <> 'palestrante');";


    // Encontrando a conta do usuário...
    $RSUsuario = db_query($con, $strSQL);
    if (db_num_rows($RSUsuario) > 0) {
        $row = db_fetch_assoc($RSUsuario);
        $resultado = "Usuário já cadastrado com mesmo <b>";
        if ($row["Funcionarios_cpf"] == $useCPF) {
            $resultado .= "cpf, ";
        }
        if ($row["email"] == $useEmail) {
            $resultado .= "email, ";
        }
        if ($row["username"] == $useUserName) {
            $resultado .= "username, ";
        }
        $resultado .= "</b> verifique ou localize o usuário cadastrado para alteração.";
    }

    return $resultado;
}