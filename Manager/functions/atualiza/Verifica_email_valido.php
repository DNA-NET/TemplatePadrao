<?php
//=============================================================================================================
// Envio de emails
//=============================================================================================================
function Verifica_email_valido($emailAddress)
{
    if (strpos($emailAddress, '@') !== false) {
        return  True;
    } else {
        return False;
    }
}