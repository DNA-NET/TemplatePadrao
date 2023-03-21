<?php



function tira_pontos_tracos_cpf($cpf)
{
    $novoCpf = str_replace('.', '', $cpf);
    $novoCpf = str_replace('-', '', $novoCpf);
    $novoCpf = str_replace('/', '', $novoCpf);
    return $novoCpf;
}