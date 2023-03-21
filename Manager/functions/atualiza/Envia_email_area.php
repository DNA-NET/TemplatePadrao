<?php



function Envia_email_area(
    $from,
    $para_area,
    $cc,
    $bcc,
    $subject,
    $BodyFormat,
    $MailFormat,
    $Body,
    $AttachFile
) {

    global $con;

    $dtA = mysqli_query($con, "select email_email from email where email_area='" . $para_area . "'");

    $n = mysqli_num_rows($dtA);

    if ($n > 0) {
        while ($RSE = mysqli_fetch_array($dtA)) {
            if (Verifica_email_valido($RSE["email_email"])) Envia_email($from, $RSE["email_email"], $cc, $bcc, $subject, $BodyFormat, $MailFormat, $Body, $AttachFile);
        }
    }
}