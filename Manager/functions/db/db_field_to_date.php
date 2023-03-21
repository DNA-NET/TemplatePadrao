<?php



/**
 * Converte o campo do banco de dados passado em uma string em formato de data.
 *
 * @param {String}               $format             Formato que a data deve ter.
 * @param {String}               $field              Consulta SQL que será feita.
 *
 * @return {String}
 */
function db_field_to_date($format, $field)
{
    global $useCon;


    switch ($useCon["DataBase"]) {
        case "mysql":
            return date($format, strtotime((string)$field));
            break;

        case "sql_server":
            return $field->format("d/m/Y");
            break;
    }


    return "";
}