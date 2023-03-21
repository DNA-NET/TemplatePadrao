<?php



function Busca_secao_dna($secao_id)
{
    global $con;

    $RS2 = mysqli_query($con, "SELECT secao_dna FROM secao WHERE secao_id=$secao_id");

    if (mysqli_num_rows($RS2) > 0) {
        $row2 = mysqli_fetch_assoc($RS2);
        return $row2["secao_dna"];
    } else {
        return "";
    }
}