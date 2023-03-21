<?php



function Busca_secao_menu()
{
    global $con;

    $secao_menu = '';

    if (isset($_SESSION["secao_id"]) && isset($_SESSION["secao_dna"])) {

        $niveis = $_SESSION["secao_dna"] . "-" . $_SESSION["secao_id"];
        $niveis = substr($niveis, - (strlen($niveis) - 1));
        $niveis_array = str_replace("--", ",", $niveis);

        $RS2 = mysqli_query($con, "SELECT secao_id FROM secao WHERE secao_menu='Sim' AND secao_id IN ($niveis_array)");

        if (mysqli_num_rows($RS2) > 0) {
            $row2 = mysqli_fetch_assoc($RS2);
            $secao_menu =  $row2["secao_id"];
        }
    }

    return $secao_menu;
}