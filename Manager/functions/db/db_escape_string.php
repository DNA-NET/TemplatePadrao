<?php



/**
 * Função que serve para substituir chamadas "inline" de uma função "_real_escape_string"
 * este método não faz o tratamento adequado mas permite manter a compatibilidade com a forma como o
 * código foi originalmente escrito.
 * Não deve ser usado para proteger de Sql Injection.
 * Para renovar o código previnindo contra Sql Injection todas as chamadas para uso do banco de dados
 * devem ser alteradas por versões que utilizam parametros e não o código direto.
 *
 * @param {String}               $escapestr          Valor que será tratado.
 *
 * @return {String}
 */
function db_escape_string($escapestr)
{
    if (is_numeric($escapestr)) {
        return $escapestr;
    }

    $unpacked = unpack("H*hex", $escapestr);
    return "0x" . $unpacked["hex"];
}