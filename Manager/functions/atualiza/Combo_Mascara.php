<?php
//==================================================================================================================
//       Função para montar combo com valores fixos de campo da mascara
//==================================================================================================================
function Combo_Mascara(
    $Mascara_id,
    $Mascara_campo,
    $Mascara_campo_valor,
    $Condicao
) {

    global $con;
    $valores_combo = "";

    $dtA = mysqli_query($con, "select " . $Mascara_campo . " from mascara where Mascara_id=" . $Mascara_id . " order by " . $Mascara_campo);

    $n = mysqli_num_rows($dtA);

    if ($n > 0) {

        $rowA = mysqli_fetch_assoc($dtA);
        $parte = explode("\n", $rowA[$Mascara_campo]);

        for ($i = 0; $i < count($parte); $i++) {
            $selecionado = "";
            if (trim($Mascara_campo_valor) == trim($parte[$i])) $selecionado = " selected ";
            if (trim($Condicao . " - " . $Mascara_campo_valor) == trim($parte[$i])) $selecionado = " selected ";
            if ($Condicao != "") {
                if (strpos($parte[$i], $Condicao) !== false) $valores_combo .= "<option value='" . trim($parte[$i]) . "' " . $selecionado . ">" . trim($parte[$i]) . "</option>";
            } else {
                $valores_combo .= "<option value='" . trim($parte[$i]) . "' " . $selecionado . ">" . trim($parte[$i]) . "</option>";
            }
        }
    }

    return $valores_combo;
}