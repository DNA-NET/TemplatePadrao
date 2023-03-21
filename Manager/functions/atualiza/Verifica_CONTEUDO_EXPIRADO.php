<?php
//=================================================================================================================
//       Função para envio de e-mail de aviso para o Publicador no caso de conteúdo expirado
//=================================================================================================================
function Verifica_CONTEUDO_EXPIRADO(
    $CONTEUDO_AVISA_EDITOR,
    $Institucional_id,
    $Funcionarios_id,
    $Institucional_data_expira,
    $Institucional_nome
) {
    global $con;

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