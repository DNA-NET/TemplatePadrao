<?php
//==================================================================================================================
//       Indexação autom&aacute;tica de tabelas
//==================================================================================================================
function Seq_MDB($tabela_nome)
{
    global $con;

    $RSTestaSeq = mysqli_query($con, "SELECT Seq_Tabela_id FROM seq WHERE Seq_Tabela='$tabela_nome'");

    if (!@mysqli_num_rows($RSTestaSeq)) {
        mysqli_query($con, "INSERT INTO seq (Seq_Tabela_id, Seq_Tabela) VALUES (1,'$tabela_nome')");
        $Seq_Tabela_id = 1;
    } else {

        while ($RSTesteResultado = mysqli_fetch_array($RSTestaSeq)) {
            $Seq_Tabela_id = $RSTesteResultado["Seq_Tabela_id"] + 1;
        }
        mysqli_query($con, "UPDATE seq SET Seq_Tabela_id='$Seq_Tabela_id' WHERE Seq_Tabela ='$tabela_nome'");
    }
    $Seq = $Seq_Tabela_id;

    return $Seq;
}