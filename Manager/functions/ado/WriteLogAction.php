<?php



function WriteLogAction($Funcionarios_id, $InstrucaoSQL)
{
    global $con;
    $DataDaAcao = new DateTime();


    $Funcionarios_id = (int)$Funcionarios_id;
    $DataDaAcao = $DataDaAcao->format("Y-m-d H:i:s");
    $Acao = NULL;
    $Tabela = NULL;
    $InstrucaoSQL = str_replace("'", "''", stripslashes($InstrucaoSQL));
    $ip = get_client_ip();

    // Identifica ação que está sendo executada
    $SQLSplit = explode(" ", trim($InstrucaoSQL));
    $count = 0;
    do {
        if (trim($SQLSplit[$count]) != "") {
            $Acao = strtolower(trim($SQLSplit[$count]));
        }
        $count++;
    } while ($Acao == NULL);

    $getPosition = 3;
    $validWordCounter = 0;
    $count = 0;

    // Identifica a posição da palavra que define a tabela onde a ação está sendo executada
    switch ($Acao) {
        case "insert":          // INSERT INTO "tablename"
            $getPosition = 3;
            break;

        case "update":          // UPDATE "tablename"
            $getPosition = 2;
            break;

        case "delete":          // DELETE FROM "tablename"
            $getPosition = 3;
            break;
    }



    // Identifica a tabela
    while ($Tabela == NULL) {
        if (trim($SQLSplit[$count]) != "") {
            $validWordCounter++;

            if ($validWordCounter == $getPosition) {
                $Tabela = strtolower(trim($SQLSplit[$count]));
            }
        }

        $count++;
    }



    $strSQL = "INSERT INTO
                    log
                    (Funcionarios_id, DataDaAcao, Acao, Tabela, InstrucaoSQL, ip)
               VALUES
                    (" . $Funcionarios_id . ", '" . $DataDaAcao . "', '" . $Acao . "', '" . $Tabela . "', '" . $InstrucaoSQL . "', '" . $ip . "')";


    db_query($con, $strSQL);
}