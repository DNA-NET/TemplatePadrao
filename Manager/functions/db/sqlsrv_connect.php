<?php



//
// Se o módulo para SQL Server não tiver sido encontrado,
// gera uma versão vazia para suas funções não serem marcadas como erros
// pela IDE.
//
define("SQLSRV_CURSOR_KEYSET", "");
define("SQLSRV_FETCH_ASSOC", "");

function sqlsrv_connect()
{
}
function sqlsrv_query()
{
}
function sqlsrv_num_rows()
{
}
function sqlsrv_fetch_array()
{
}
function sqlsrv_errors()
{
}
function sqlsrv_real_escape_string()
{
}