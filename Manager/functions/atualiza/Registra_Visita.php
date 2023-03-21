<?php



function Registra_Visita($sessionID)
{
    global $con;
    $stmt = null;
    $visitante_id = null;

    $visitante_session_hash = $sessionID;
    $visitante_useragent = $_SERVER["HTTP_USER_AGENT"];

    $uaParser = \UAParser\Parser::create();
    $browserData = $uaParser->parse($visitante_useragent);

    $visitante_navegador = $browserData->ua->family;
    $visitante_navegador_versao = $browserData->ua->major;
    if ((string)$browserData->ua->minor !== "") {
        $visitante_navegador_versao .= "." . $browserData->ua->minor;
    }

    $visitante_os = $browserData->os->family;
    $visitante_os_versao = $browserData->os->major;
    if ((string)$browserData->os->minor !== "") {
        $visitante_os_versao .= "." . $browserData->os->minor;
    }

    $visitante_dispositivo = "Desktop";
    $regExp = '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i';
    if (preg_match($regExp, $visitante_navegador)) {
        $visitante_dispositivo = "Mobile";
    }


    $visitante_ip = get_client_ip();
    if ($visitante_ip !== "UNKNOW") {
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
        }


        if ($visitante_id === null) {
            $visitante_ip_cidade = null;
            $visitante_ip_regiao = null;
            $visitante_ip_pais = null;
            $visitante_ip_postal = null;
            $visitante_ip_loc = null;

            $strSQL = " INSERT INTO visitante
                            (
                                visitante_session_hash, visitante_useragent,
                                visitante_navegador, visitante_navegador_versao, visitante_os, visitante_os_versao, visitante_dispositivo,
                                visitante_ip, visitante_ip_cidade, visitante_ip_regiao, visitante_ip_pais, visitante_ip_postal, visitante_ip_loc
                            )
                            VALUES
                            (
                                ?, ?,
                                ?, ?, ?, ?, ?,
                                ?, ?, ?, ?, ?, ?
                            );";
            $stmt = $con->prepare($strSQL);
            $stmt->bind_param(
                "sssssssssssss",
                $visitante_session_hash,
                $visitante_useragent,

                $visitante_navegador,
                $visitante_navegador_versao,
                $visitante_os,
                $visitante_os_versao,
                $visitante_dispositivo,

                $visitante_ip,
                $visitante_ip_cidade,
                $visitante_ip_regiao,
                $visitante_ip_pais,
                $visitante_ip_postal,
                $visitante_ip_loc
            );


            $curlRequest = curl_init();
            curl_setopt($curlRequest, CURLOPT_URL, str_replace("[[IP]]", $visitante_ip, getenv("ATUALIZA_IP_DATA_PROVIDER")));
            curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlRequest, CURLOPT_CONNECTTIMEOUT, 5);
            $curlResponse = curl_exec($curlRequest);
            $curlErrno = curl_errno($curlRequest);
            curl_close($curlRequest);

            if ($curlErrno === 0) {
                $curlResponse = json_decode($curlResponse, true);
                if ($curlResponse !== null && key_exists("city", $curlResponse) === true) {
                    $visitante_ip_cidade = $curlResponse["city"];
                    $visitante_ip_regiao = $curlResponse["region"];
                    $visitante_ip_pais = $curlResponse["country"];
                    $visitante_ip_postal = $curlResponse["postal"];
                    $visitante_ip_loc = $curlResponse["loc"];

                    $stmt->execute();
                }
            }

            $stmt->close();
        } else {
            $funcionarios_id = (isset($_SESSION["Funcionarios_id"]) === true) ? (int)$_SESSION["Funcionarios_id"] : "NULL";
            $strSQL = " UPDATE visitante
                            SET
                                visitante_data_fim=NOW(), funcionarios_id=$funcionarios_id
                            WHERE
                                visitante_id=$visitante_id;";
            mysqli_query($con, $strSQL);
        }
    }
}