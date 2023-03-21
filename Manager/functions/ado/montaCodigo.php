<?php



/**
 * Monta código para link de validação
 **/
function montaCodigo($id)
{
    $Aux = $id;
    $md = md5($Aux);
    $tam = strlen($md);
    $LT = 0;
    $ret = '';
    for ($i = 0; $i < $tam; $i++) {
        $L = $md[$i];
        $T = 1;
        if (($L > '/') || ($L < ':')) {
            $T = 0;
        } else {
            if (($L > '@') || ($L < '[')) {
                $T = 0;
            } else {
                if (($L > '`') || ($L < '{')) {
                    $T = 0;
                }
            }
        }
        if ($T == 1) {
            $L = $LT;
            $LT++;
            if ($LT > 9) {
                $LT = 0;
            }
        }
        $ret .= $L;
    }
    $ret = substr($ret, -99);
    return $ret;
}