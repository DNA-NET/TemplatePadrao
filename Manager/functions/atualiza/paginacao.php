<?php



function paginacao($Conteudo_campo)
{
    if (isset($_SESSION["numero_registros_" . $Conteudo_campo])) {
        $pagina = 1;
        if (isset($_SESSION["pagina_atual_" . $Conteudo_campo])) $pagina = $_SESSION["pagina_atual_" . $Conteudo_campo];

        $total_registros = $_SESSION["numero_registros_" . $Conteudo_campo];
        $limite = $_SESSION["linhas_por_pagina_" . $Conteudo_campo];
        $controles = "<div style=\"clear: both; font-size: 16px;\">";

        if (!isset($_SESSION["secao_link"])) {
            $_SESSION["secao_link"] = "";
        }

        if ($total_registros > 0 &&  $limite > 0) {
            $useSessaoId = "secao_id=" . $_SESSION["secao_id"] . "&";
            if ($_SESSION["secao_id"] == 0) {
                $useSessaoId = "";
            }
            $total_paginas = Ceil($total_registros / $limite);

            $inicio = $pagina - 5;
            if ($inicio <= 0) $inicio = 1;

            $fim = $pagina + 5;
            if ($fim >= $total_paginas) $fim = $total_paginas;


            if ($pagina != 1) $controles .= '<a href="?' . $useSessaoId . 'pagina_atual_' . $Conteudo_campo . '=1" OnClick="" class="btn btn-primary">Primeira p&aacute;gina</a>';
            for ($i = $inicio; $i <= $fim; $i++) {
                if ($pagina == $i) {
                    $controles .=  " | <b>" . $i . "</b> ";
                } else {
                    $controles .=  '| <a href="?' . $useSessaoId . 'pagina_atual_' . $Conteudo_campo . '=' . $i . '" OnClick="Paginacao(' . $i . ');"> ' . $i . '</a> ';
                }
            }

            $controles .=  ' | <a href="?' . $useSessaoId . 'pagina_atual_' . $Conteudo_campo . '=' . $total_paginas . '" class="btn btn-primary"> &Uacute;ltima p&aacute;gina</a>';
            $controles .=  '<br><br>Total de <b>' . $total_registros . '</b> registros';
        }

        if (isset($_REQUEST["palavra"])) $controles = str_replace("?", "?palavra=" . $_REQUEST["palavra"] . "&", $controles);
        if (isset($_REQUEST["periodo"])) $controles = str_replace("?", "?periodo=" . $_REQUEST["periodo"] . "&", $controles);
        if (isset($_REQUEST["ano"])) $controles = str_replace("?", "?ano=" . $_REQUEST["ano"] . "&", $controles);

        $controles .=  '</div>';
        return $controles;
    } else {
        return '';
    }
}