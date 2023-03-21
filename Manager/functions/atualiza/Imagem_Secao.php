<?php



function Imagem_Secao($secao_id, $imagem_default)
{
    global $con;

    $Rs_imagem = mysqli_query($con, "SELECT secao_imagem FROM secao where secao_id=$secao_id");

    if (mysqli_num_rows($Rs_imagem) > 0) {
        $row2 = mysqli_fetch_assoc($Rs_imagem);
        if (isset($row2["secao_imagem"])) {
            $imagem_default =  ('data:image/jpg;base64,' . base64_encode($row2["secao_imagem"]));
        }
    }

    return ($imagem_default);
}