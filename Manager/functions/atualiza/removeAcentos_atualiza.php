<?php



function removeAcentos_atualiza($texto)
{
    $array1 = array("&aacute;", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "&oacute;", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "&aacute;", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "&oacute;", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
    $array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C");
    return str_replace($array1, $array2, $texto);
}