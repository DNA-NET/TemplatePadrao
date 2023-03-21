<?php



function Seq($tabela_nome)
{

    global $con;
    $RSTestaSeq = db_query($con, "SELECT Seq_Tabela_id FROM seq WHERE Seq_Tabela='" . $tabela_nome . "'");
    if (db_num_rows($RSTestaSeq) == 0) {
        db_query($con, "INSERT INTO seq (Seq_Tabela_id, Seq_Tabela) VALUES (1,'" . $tabela_nome . "')");
        $Seq_Tabela_id = 1;
    } else {

        while ($RSTesteResultado = db_fetch_array($RSTestaSeq)) {
            $Seq_Tabela_id = $RSTesteResultado["Seq_Tabela_id"] + 1;
        }
        db_query($con, "UPDATE seq SET Seq_Tabela_id=" . $Seq_Tabela_id . " WHERE Seq_Tabela ='" . $tabela_nome . "'");
    }
    $Seq = $Seq_Tabela_id;

    return $Seq;
}