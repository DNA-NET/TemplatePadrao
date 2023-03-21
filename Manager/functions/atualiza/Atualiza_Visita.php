<?php



function Atualiza_Visita($sessionID, $jsonData)
{
    global $con;
    $stmt = null;
    $visitante_id = null;
    $visitante_session_hash = $sessionID;
    $visitante_pagina_url = $jsonData["url"];

    $yesterday = new DateTime();
    $yesterday->sub(new DateInterval("P1D"));
    $yesterday = $yesterday->format("Y-m-d") . " 00:00:00";


    $strSQL = " SELECT
                    visitante_id
                FROM
                    visitante
                WHERE
                    visitante_session_hash='$visitante_session_hash' AND
                    visitante_data_inicio>='$yesterday'
                ORDER BY
                    visitante_id DESC
                LIMIT 1;";

    $dt = mysqli_query($con, $strSQL);
    if (mysqli_num_rows($dt) > 0) {
        $row = mysqli_fetch_assoc($dt);
        $visitante_id = (int)$row["visitante_id"];

        if ($visitante_id !== null) {
            $visitante_tela_largura = (int)$jsonData["displayInfo"]["sizeScreenWidth"];
            $visitante_tela_altura = (int)$jsonData["displayInfo"]["sizeScreenHeight"];
            $visitante_janela_largura = (int)$jsonData["displayInfo"]["sizeWindowWidth"];
            $visitante_janela_altura = (int)$jsonData["displayInfo"]["sizeWindowHeight"];
            $visitante_profundidade_cor = (int)$jsonData["displayInfo"]["colorDepth"];
            $visitante_profundidade_pixel = (int)$jsonData["displayInfo"]["pixelDepth"];

            $strSQL = " UPDATE visitante
                                SET
                                    visitante_tela_largura=$visitante_tela_largura,
                                    visitante_tela_altura=$visitante_tela_altura,
                                    visitante_janela_largura=$visitante_janela_largura,
                                    visitante_janela_altura=$visitante_janela_altura,
                                    visitante_profundidade_cor=$visitante_profundidade_cor,
                                    visitante_profundidade_pixel=$visitante_profundidade_pixel
                                WHERE
                                    visitante_id=$visitante_id AND
                                    visitante_tela_largura IS NULL;";
            mysqli_query($con, $strSQL);


            // Encerra data de visita de uma pagina anteriormente aberta.
            $strSQL = " UPDATE visitante_pagina
                                SET
                                    visitante_pagina_data_fim=NOW()
                                WHERE
                                    visitante_id=$visitante_id AND
                                    visitante_pagina_data_fim IS NULL;";
            mysqli_query($con, $strSQL);



            // Cria o registro do acesso para a página atual
            $strSQL = " INSERT INTO visitante_pagina
                            (
                                visitante_pagina_url, visitante_id
                            )
                            VALUES
                            (
                                ?, ?
                            );";
            $stmt = $con->prepare($strSQL);
            $stmt->bind_param(
                "ss",
                $visitante_pagina_url,
                $visitante_id
            );

            $stmt->execute();
            $stmt->close();
        }
    }
}