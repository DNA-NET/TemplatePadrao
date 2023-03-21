<?php

use Elastic\Elasticsearch\ClientBuilder;






//=============================================================================================================
// Retorna resultado da busca em conteúdo por palavra chave
//=============================================================================================================
function Busca(
    $String_busca,
    $pagina_atual,
    $nome_pagina,
    $secao_dna
) {

    global $Linhas_por_pagina_busca;
    global $con, $Dominio_url_producao;
    global $Dominio_url_menu;

    $resposta = "";
    $texto_final = "";
    $pode = "";
    $texto_meio = "";
    $query = "";
    $Sql = "";
    $inicio = "1";
    $fim = "";
    $paginas_final = "1";
    $linhas_por_pagina = "50";
    $passo_inicio = "";
    $inicio_texto = "";
    $passo_fim = "";
    $inicio_descricao = "";
    $i2 = "";

    $resposta = "Nenhum resultado encontrado para a busca!";

    try {
        $linhas_por_pagina = $Linhas_por_pagina_busca;
        if ($pagina_atual < 1) $pagina_atual = 1;

        $query = " secao.Secao_id = 0 ";
        $Sql = "";
        $sessaoDNA = str_replace("-", "", right($secao_dna, 4));
        $n = 0;
        $esClient = null;
        $esWhere = null;


        if (getenv("ELASTIC_SEARCH_ENABLED") === "1") {
            $elasticServerHostIP = "https://" . getenv("ELASTIC_SEARCH_IP") . ":" . getenv("ELASTIC_SERVER_PORT");

            $esClient = ClientBuilder::create()
                ->setHosts([$elasticServerHostIP])
                ->setBasicAuthentication("elastic", getenv("ELASTIC_SEARCH_PASSWORD"))
                ->setCABundle(getenv("ELASTIC_SEARCH_HTTP_CA_CRT_PATH"))
                ->build();

            $esWhere = [
                "index" => strtolower(getenv("ELASTIC_SEARCH_MAIN_INDEX")),
                "body"  => [
                    "query" => [
                        "combined_fields" => [
                            "query" => $String_busca,
                            "fields" => [
                                "institucional_nome",
                                "institucional_descricao",
                                "institucional_texto",
                                "institucional_arquivo_txt"
                            ]
                        ]
                    ]
                ]
            ];

            $response = $esClient->count($esWhere);
            $jsonResponse = json_decode($response->getBody(), true);
            $n = $jsonResponse["count"];
        } else {
            $strSQLWhere = "
                url_tipo = 'conteudo' AND
                (
                    secao_dna LIKE '%$secao_dna%' OR
                    secao.secao_id = $sessaoDNA
                ) AND
                secao.secao_id = institucional.secao_id_principal AND
                (
                    (
                        institucional_texto LIKE '%$String_busca%'
                    ) OR
                    (
                        institucional_descricao LIKE '%$String_busca%'
                    ) OR
                    (
                        institucional_nome LIKE '%$String_busca%'
                    )
                ) AND
                Institucional_status='Publicado' AND
                (
                    secao_controle = 'Livre' OR
                    (
                        secao_controle = 'Restrita' AND
                        ($query)
                    )
                )";


            $strSQLCount = "SELECT
                COUNT(Ids) as count FROM (
                    SELECT
                        institucional.institucional_id as Ids
                    FROM
                        institucional,
                        secao,
                        url_amigavel
                    WHERE
                        $strSQLWhere
                    GROUP BY
                        institucional.institucional_id
            ) as t1";

            $dt = mysqli_query($con, $strSQLCount);
            $n = (int)mysqli_fetch_assoc($dt)["count"];
        }


        if ($n > 0) {
            if ($n < $linhas_por_pagina) $linhas_por_pagina = $n;
            $resposta = '<p>Foram encontrados ' . $n . ' resultados para <b>' . $String_busca . '</b></p>';
            $paginas_final = $pagina_atual * $linhas_por_pagina;
            if ($paginas_final > $n) $paginas_final = $n;

            if ($n > 1) {
                $resposta = $resposta . "<b>P&aacute;gina " . $pagina_atual . " de " . ceil($n / $linhas_por_pagina) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;";
                if ($pagina_atual > 1) {
                    $resposta = $resposta . '<a href="' . $nome_pagina . '?palavra=' . $String_busca . '&pagina_atual=' . ($pagina_atual - 1) . '">Anterior</a> ';
                }
                if ($pagina_atual < ($n / $linhas_por_pagina)) {
                    $resposta = $resposta . '&nbsp;&nbsp;&nbsp;<a href="' . $nome_pagina . '?palavra=' . $String_busca . '&pagina_atual=' . ($pagina_atual + 1) . '">Pr&oacute;xima</a> ';
                }
            }

            $inicio = ($pagina_atual - 1) * $linhas_por_pagina;
            $fim = $pagina_atual * $linhas_por_pagina;
            if ($n <= $fim) $fim = $n;


            if (getenv("ELASTIC_SEARCH_ENABLED") === "1") {
                $response = $esClient->search($esWhere);

                $tmpDT = [];
                foreach ($response->asArray()["hits"]["hits"] as $RSPag) {
                    $tmpDT[] = $RSPag["_source"];
                }
                $dt = $tmpDT;
            } else {
                $Sql = "SELECT DISTINCT
                            institucional.institucional_id,
                            institucional_nome,
                            institucional_arquivo,
                            secao_nome,
                            secao_nome_ordem,
                            secao_link,
                            secao.secao_id,
                            url_amigavel
                        FROM
                            institucional,
                            secao,
                            url_amigavel
                        WHERE
                            $strSQLWhere
                        ORDER BY
                            secao_nome_ordem
                        LIMIT
                            $inicio,$linhas_por_pagina";


                $dt = mysqli_query($con, $Sql);
                $tmpDT = [];
                while ($RSPag = mysqli_fetch_array($dt)) {
                    $tmpDT[] = $RSPag;
                }
                $dt = $tmpDT;
            }


            $i = 0;
            $resposta = $resposta . "<p></p>";

            foreach ($dt as $RSPag) {
                $i += 1;
                if ((string)$RSPag["institucional_arquivo"] != "" && $RSPag["institucional_arquivo"] != "migradas.jpg") {
                    $downloadURL = $Dominio_url_producao . "/conteudo/" . $RSPag["institucional_arquivo"];
                    $resposta = $resposta . ("<h4>" . $i . '- <a href="' . $downloadURL . '"  target="_blank">' . str_replace(">", " > ", $RSPag["secao_nome_ordem"]) . "</a></h4><p>" . $RSPag["institucional_nome"] . "</p>");
                } else {
                    $resposta = $resposta . ("<h4 style=\"font-size:1.3em; margin-bottom:2px;\">" . $i . '- <a href="' . $Dominio_url_menu . $RSPag["url_amigavel"] . '"  >' . str_replace(">", " > ", str_replace("> HC > Home ", "", $RSPag["secao_nome_ordem"])) . "</a></h4><p>" . $RSPag["institucional_nome"] . "</p>");
                }
            }

            if ($n > 1) {
                $resposta = $resposta . "<br><br><b>P&aacute;gina " . $pagina_atual . " de " . ceil($n / $linhas_por_pagina) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;";
                if ($pagina_atual > 1) {
                    $resposta = $resposta . '<a href="' . $nome_pagina . "?palavra=" . $String_busca . "&pagina_atual=" . ($pagina_atual - 1) . '">Anterior</a> ';
                }
                if ($pagina_atual < ($n / $linhas_por_pagina)) {
                    $resposta = $resposta . '&nbsp;&nbsp;&nbsp;<a href="' . $nome_pagina . "?palavra=" . $String_busca . "&pagina_atual=" . ($pagina_atual + 1) . '">Pr&oacute;xima</a> ';
                }
            }
        } else {
            $resposta = '<span class="cinzamediohome"><b>Nenhum resultado encontrado para a busca!</b></span>';
        }
    } catch (Exception $e) {
        acumula_erro("Erro na busca > " . $e->getMessage());
    }

    return $resposta;
}