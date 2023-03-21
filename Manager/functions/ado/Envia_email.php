<?php



function Envia_email($from, $para, $cc, $bcc, $subject, $BodyFormat, $MailFormat, $Body, $AttachFile)
{

    global $Dominio_SMTP, $Dominio_SMTP_porta;

    try {
        ini_set("SMTP", $Dominio_SMTP);
        ini_set("smtp_port", $Dominio_SMTP_porta);

        // USE "\n" para Linux e "\r\n" para Windows
        $nl = "\n";

        $to = $para;

        $message = "
		<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
		<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
		<title>" . $subject . "</title>
		</head>
		<body>" . $Body . "</body>
		</html>
		";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . $nl;
        $headers .= "Content-type:text/html;charset=utf-8" . $nl;

        // More headers
        $headers .= 'from: <' . $from . '>' . $nl;
        if ($cc != "") $headers .= 'Cc: ' . $cc . $nl;
        if ($bcc != "") $headers .= 'Bcc: ' . $bcc . $nl;

        return mail($to, $subject, $message, $headers);
    } catch (Exception $e) {
        acumula_erro("Erro no envio de e-mail > " . $e->getMessage());
    }
}