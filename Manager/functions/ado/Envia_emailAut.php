<?php



function Envia_emailAut(
    $from,
    $para,
    $cc,
    $bcc,
    $subject,
    $BodyFormat,
    $MailFormat,
    $Body,
    $AttachFile,
    $responder_para,
    $nome_user
) {

    global $Dominio_SMTP;


    // Inicia a classe PHPMailer
    $mail = new PHPMailer(true);

    // Define os dados do servidor e tipo de conexão
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->IsSMTP(); // Define que a mensagem será SMTP

    try {
        $to = $para;

        $mail->Host = $Dominio_SMTP; // Endereço do servidor SMTP
        //$mail->SMTPDebug = 2; // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;  // Usar autenticação SMTP
        $mail->Port = 587; //  Usar 587 porta SMTP
        $mail->Username = 'webmaster@hc.org.br'; // Usuário do servidor SMTP (endereço de email)
        $mail->Password = 'AhLUm+&e3?Rz@j'; // Senha do servidor SMTP (senha do email usado)

        //Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->SetFrom($from, 'Conselho Regional de Contabilidade do Paraná');
        $mail->AddReplyTo($responder_para, $nome_user);
        $mail->Subject = $subject;

        //Define os destinatário(s)
        //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->AddAddress($to, $nome_user);

        //Define o corpo do email
        $mail->MsgHTML($Body);

        $mail->Send();
        //echo "Mensagem enviada com sucesso</p>\n";

        //caso apresente algum erro é apresentado abaixo com essa exceção.
    } catch (phpmailerException $e) {
        acumula_erro("Erro no envio de e-mail > " . $e->errorMessage()); //Mensagem de erro costumizada do PHPMailer
    }
}