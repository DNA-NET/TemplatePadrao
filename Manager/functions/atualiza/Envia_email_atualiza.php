<?php



function Envia_email_atualiza(
    $from,
    $para,
    $cc,
    $bcc,
    $subject,
    $BodyFormat,
    $MailFormat,
    $Body,
    $AttachFile,
    $responder_para
) {

    global $Dominio_SMTP;

    try {
        ini_set("SMTP", $Dominio_SMTP);

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