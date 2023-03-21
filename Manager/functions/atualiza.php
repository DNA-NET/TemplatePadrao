<?php



foreach (\scandir(__DIR__ . "/atualiza") as $function_file) {
    if (
        $function_file !== "." &&
        $function_file !== ".." &&
        \function_exists(\str_replace(".php", "", $function_file)) === false
    ) {
        require_once __DIR__ . "/atualiza" . DIRECTORY_SEPARATOR . $function_file;
    }
}
unset($function_file);