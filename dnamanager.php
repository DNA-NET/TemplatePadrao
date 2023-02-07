<?php 
    // CONFIGURAÇÕES BÁSICAS DO SCRIPT    
    // ----------
    include_once 'Manager/conexao.php';


    // Seta amostragem de erros na tela.
    ini_set("display_errors", 1);

    // Seta report para todos os erros.
    error_reporting(E_ALL);

    // Seta DateTime para o Brasil
    date_default_timezone_set("America/Sao_Paulo");

    // Seta UTF-8
    mb_internal_encoding("UTF-8");
    mb_http_output("UTF-8");
    mb_http_input("UTF-8");
    mb_language("uni");
    mb_regex_encoding("UTF-8");
    header('Content-Type: text/html; charset=utf-8');

    // Definições para Upload / Precisam ser ajustadas tambem
    // no arquivo de configuração do servidor web usado... web.config / .httacess
    ini_set("upload_max_filesize","100M");
    ini_set("max_execution_time","2000");
    ini_set("post_max_size","100M");
?>
<!DOCTYPE html>
<html lang="pt-br" xml:lang="pt-br" xmlns="http://www.w3.org/1999/xhtml">
<?php



    // Caminho base para o uso deste script é o diretório onde o mesmo está instalado.
    $BasePath = explode(DIRECTORY_SEPARATOR, __FILE__);
    $BasePath = array_slice($BasePath, 0, -1);
    $BasePath = implode(DIRECTORY_SEPARATOR, $BasePath);


    $BaseURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    if ($_SERVER["SERVER_PORT"] != "80") { $BaseURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; } 
    else { $BaseURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; }


    $BaseURL = explode("/", $BaseURL);
    $BaseURL = array_slice($BaseURL, 0, -1);
    $BaseURL = implode("/", $BaseURL);




    $JsBasepath = "<script>
                        var BasePath = '" . str_replace(DIRECTORY_SEPARATOR, "/", $BasePath) . "';
                        var BaseURL = '" . $BaseURL . "';
                   </script>\n";
    $ThisScriptFile = "dnamanager.php";
    $TransferFile = "dnamanager_selecteditens.txt";
    $CurrentPath = (isset($_REQUEST["CurrentPath"])) ? $_REQUEST["CurrentPath"] : $BasePath;
    $CurrentPath = str_replace("\\", DIRECTORY_SEPARATOR, $CurrentPath);

    $ServerMessage = "";
    $ServerMessageClass = "";


    $editableFilesExt = array("txt", "aspx", "vb", "cs", "php", "xml", "css", "js", "html", "htm");
    $imageExtensions = array("bmp", "png", "jpg", "jpge", "gif", "svg", "psd", "raw");



    // Verifica se o diretório base é um diretório válido.
    if(!is_dir($BasePath)) {
        echo "O caminho principal (BasePath) não está corretamente configurado.<br />";
        echo "O caminho atual é " . __FILE__;
        exit();
    }


    // Define o diretório de trabalho
    if(isset($_REQUEST["GoTo"])) {
        switch($_REQUEST["GoTo"]) {
            case "root" :
                $CurrentPath = $BasePath;
                break;

            case "parent" :
                $CurrentPath = explode(DIRECTORY_SEPARATOR, $CurrentPath);
                $CurrentPath = array_slice($CurrentPath, 0, -1);
                $CurrentPath = implode(DIRECTORY_SEPARATOR, $CurrentPath);
                break;
        }
    }



    // Caso o diretório atualmente selecionado não esteja
    // dentro do caminho base, envia o usuário para o caminho base.
    if(strrpos($CurrentPath, $BasePath) !== 0) {
        header('Location: dnamanager.php');
        exit();
    }


    $IsBase = ($BasePath === $CurrentPath) ? TRUE : FALSE;
    $action = (isset($_REQUEST["Action"])) ? $_REQUEST["Action"] : "";
?>
    <head>
        <meta charset="utf-8" />
        <title>DNA File Manager</title>

        <style type="text/css">
            body {
                font-family:Verdana;
                font-size:12px;
                padding: 0;
                margin: 0;
            }
            body > form {
                padding: 30px 10px 10px 10px;
                position: relative;
                width: 96%;
            }


            a {text-decoration: none; cursor: pointer; color:#00F;}

            .lpath, .ico {
                cursor: pointer;
                height:20px; 
                padding-left: 30px; 
                background-position: left center; 
                background-repeat: no-repeat;
                color:#00F;
            }
            .ico {
                padding-left: 0; 
                width: 30px;
                margin-left: -30px;
            }
                .dir {background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAFo9M/3AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAuBJREFUeNpiXNcpzgACTEAs7hdr9R8ggBhhIgynlhn+//l6xX+AAAKJcPj7yX9/9eYbw79//xkYgZJcP474fQWpevnrCwNAACH0QAHjr/Pm/1l5/zG8eP6d4ca1n4ws354zM/AIijIIa1gxyP+//4/l1vtfDHpPgNaynGB4eP06E0AAMe6fqcrw9duXHUDjuf7++X+ej5c3H8VMkDPcLJS+M3P+Z7h86zvD//+MYKf8/w/E/xi+sADZ/L8+MTGwMQoz6KkA/cT9n4GJFyjLxspwas+9IyDFHECs6GvHz8PGyviXhZnxrxAf88/pa968Bop/BgggDH+gA5AVYmzsTGtAHG5OHjsMBYyMjM88nGWZmdj+M7x89f0/TOLUyTdfgT7iAZnA8OszM8ONx98Yfv9iAfsABMRExDkZGJlWsjAxMv769ZmRU8dABugDJob/rAIM/5i8wZF0Zkv7S7AJt9/+ZtDlZmb49/U/AwvPOwZmrqUMHz4wMfz69S2P5f///781pCU4weZ+Y2T4840ZGGH/GW5dvb8THJKgMPCy4RPnYGP6zcjI8J+Tnem3mBDL974lr94Ao+EDQIARDAdCgEVQgI/h85cvbwz0hIWZWRkZmJj+g+09c/rtN0YmhrMYOv4z/Pz16180UN8rsAHvP3ziAIalgCgvDwMT0MtMrMB0x/KfwdWVg4uR6b8tPNyg4OuXPwzHjr6+BGTqAfErUCjxAIPyNzAomUEGvPryG5gYuBhk1UQY/jPzMPxn0gRaqgQ3gFuUgcFVkUH8x5e3L68cnHkNZAAzyGF/fzAxvPz+i4GZV4hBko+D4d/bPwxM7O8YGNmOAd15nAHkPGQDbx1f8ubXz+9BLDCT33z/AYxIEQZpXnZgdDAw/GMAYWZIagImEyZWhIGXz637/OXzR0egzE2QD1XbsoTabc3kA1hY2CA+Bqf5/4zYQv3Pr99/ctpvuF689f0sMBo/g7MUJQAAydYAOAh+PewAAAAASUVORK5CYII=);}
                .file {background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAFo9M/3AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAzFJREFUeNpiXL9+PQMQSLEAwXM3NzcJgABiXLJkiRA3N/c7hj9//vw/2yjxHyCAGJYvX/5/586d/0ECfS3Vb5l4eHheODg4MDy6vpJBVd/iF0AAMcJMYYCAZwz/geDv379gLU+72v+z/Pv3DyTIsHv9BAYGaXZGht+/f///8ePH/415qv+B5jEABBDYjO/fv/8XFhZmcHV1BasGge3bt99+vG+DJgszM/NLoAsZeHl5GUDGgcDvX78YTFV/qG7tP72KiZGR8Z+AgAADExMTA9ByhrOnjjJwPOlnOHbm3XvviIBgJqAgK9BFYMmfP74zfDozheEVbzwDE4/47+TaLnaAAAK74evXr2xA/4swoIJnYBIYOB4PHz78//Lly/9AN4D9+ObNm/9lEZaMb+9dZ2BydHTcLicnB3Y9MrYXVn79aPeuKYyPHj36z8nJySAkJASX3L99AQPnx7cMH/5wszDx8/OjSD5+cJ/B1ZyZ4cbJzXX//r3/ywJyB0gCFAYg/OzhAYbrqysZpOyqm9+//8LABFIAkzx/bCeD8d9DDDplxxiMXOMZTtx4xQAKB7AkMMAYPr68ynD8qSzDf2Z2BmMzLV6gXkaAAIPFJygsnIChuReYOBiAjv4O1PCfAQsAih/79euXK4zPAtToB9S40d7enkFGRoZh165dDAYGBpyioqLwiIX548OHDwzHjh1VOLWy211dXl/MOynzFOOqVavAyQIGPn36xBAYGMiwbds2BqBNKAZoaaoxHNu07J+DsC7Tw8M7fv3WlstlAjr3hY2NDcPPnz8Zvnz5Ak4UGzZsgGuGBe+DW5cYWL4fZkgPZWU6cz7/xT1NXrM/kiKz4MGMiwYF4psnNxiSYgwY2K4sYli0+NlvAcsyXf5/v94w/v8GiQZkm2AYFPLfv35m+P7xHkNuvBbD67V1DGdfGzAIuTe8+/jj35uU6mkMuy69gRiArhHsgv//GHiYXjOkeIkxHOupZLjIGcmg4pLKAJL5wykD9OJvRmDaZGQBOvHV4cOHBdjY2BhAGAZ+fPvMtHf7MbZbd1X+6lh1/eFh5fx/4cIFkJdeLV26WJ2Hl/M5KMwB6rrRCjrRKZgAAAAASUVORK5CYII=);}
                .image {background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAVxJREFUOI3Fkj1Ow0AQhd94l0AcIwUaIAZRoBT0nIQDIHENDsBNqOAMSCloiAQUNChSKIAg2YSExD+x196hMHbkJIqUilftzOz7Zma1wH+LWq32XoSoLQxhr2JMWb8ba8aJHEY/51a1Zjc/Llfq3LEv9scj70zGKt1FFdjy7oAKg7c5G21AQETLp0ixI4tIAPpQAyILeZNhdAWQ/tUZAAggLkGM/MAmF+YciCoX5knfRODUAE2LARRnBU6B4EVlyXhqVkEFaSThO1YJMl0hAvBJ+Lr3EL4mqL9toG6bhTmXjgV81wKOZwCsAffWR+gmAIDh8wTJdwXr9am5gETTXYsVnMcxQleVLnq9EUJ3MAeYfwOdzplzBe5gKUQagjURQR5kH5GUAqkyLNGAEhLCqpXyTJrlyAufzIqJ3unN0lFnFScKP57/IOO+c9WdhE0mPloFQEDHaTSuV+q6SL/0zZjUDEo5PgAAAABJRU5ErkJggg==);}


            .btn {
	            background-color: #884dc4;
	            border-color: #660066 rgb(100, 50, 100) rgb(100, 50, 100) rgb(150, 50, 150);
	            border-style: solid;
	            border-width: 1px;
	            color: #fff;
	            height: 25px;
	            font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
	            font-size: 12px;
	            margin: 0 2px;
	            padding: 2px 18px;
	            cursor:pointer;
            }



            .table-content {
                width:98%;
    
                font-family:Verdana;
                font-size:12px;
                color:#333333;

                border-collapse:collapse;
                border: 1px solid #000;
            }
                .table-content thead tr {
                    color: #FFF;
                    background-color:#884DC4;
                    font-weight:bold;
                    white-space: nowrap;
                }
                .table-content tbody tr:nth-child(odd) {
                    color:#333;
                    background-color:#F7F6F3;
                }
                .table-content tbody tr:nth-child(even) {
                    color:#284775;
                    background-color:#FFF;
                }

                .table-content th {
                    border-left: 1px solid #FFF;
                }
                .table-content td {
                    border: 1px solid #000;
                    white-space: nowrap;
                    border-top: none;
                    border-bottom: none;
                }


                .table-content th:first-child {
                    border-left: 1px solid #000;
                }
                .table-content td, .table-content th {
                    padding: 4px;
                }






            .w-full {width: 100%;}
            .hide {display: none;}
            .show {display: block;}




            #ServerMessage {
                position: fixed;
                z-index: 1;
                display: none;
                width: 100%;
                padding: 10px 0;
                color: #222;
    
                font-weight: bold;
                text-align: center;
                border:1px solid #333;
                border-right: none;
                border-left: none;
            }
            #ServerMessage.sucess {background-color:#b7f7a1;}
            #ServerMessage.fail {background-color:#f79e9e;}

            #Shadow {
                position: fixed;
                display: none;
                width: 100%;
                height: 100%;
                background-color: #000;
                opacity: .8;
                z-index: 1;
            }


            .dialog-box {
                display: none;
                width:300px;
                padding: 5px;

                position: absolute;
                z-index: 2;
                border: darkgray 2px solid;
                background-color: #E0E0E0;
    
                text-align: center;
                box-shadow: 7px 7px 5px 0px rgba(50, 50, 50, 0.75);
            }
            .dialog-box .msg {
                padding: 5px 5px 10px 5px;    
                font-weight: bold;
            }

            #UploadFiles.dialog-box {width: 400px;}
            #UploadFilesBox {padding-bottom: 35px; max-height: 220px; overflow: auto;}
            #UploadFilesBox input {margin-bottom: 5px;}
            #btnAddUploadFile {
                position: absolute;
                bottom: 40px;
                right: 40px;
            }


            #DialogBoxInput {padding-bottom: 10px; display: none;}
            #DialogBoxInput input {width: 260px;}


            #UploadProgress {color: red;}
            
            
            
            #FormView textarea {width: 1000px; height: 600px;}
        </style>

        <?php echo $JsBasepath?>
    </head>
    <body>
<?php
    // FUNÇÕES AUXILIARES
    // ----------



    /**
    * Copia todo o conteúdo de um diretório para um novo destino.
    *
    * @param {String}           $origin         Diretório de origem.
    * @param {String}           $destin         Local de destino.
    *
    * @return {Boolean}
    */
    function CopyDir($origin, $destin) { 
        $dir = opendir($origin);
        mkdir($destin, 0777);
        $isOK = TRUE;

        while(false !== ($file = readdir($dir))) {
            if($isOK) {
                if (($file != ".") && ($file != "..")) {
                    if (is_dir($origin . DIRECTORY_SEPARATOR . $file)) {
                        $isOK = recurse_copy($origin . DIRECTORY_SEPARATOR . $file, $destin . DIRECTORY_SEPARATOR . $file);
                    }
                    else {
                        $isOK = copy($origin . DIRECTORY_SEPARATOR . $file, $destin . DIRECTORY_SEPARATOR . $file);
                    }
                }
            }
        }
        closedir($dir);
        
        return $isOK;
    } 
    /**
    * Remove um diretório e todo seu conteúdo.
    *
    * @param {String}               $pathDirectory          Caminho completo do diretório que será excluido.
    *
    * @return {Boolean}
    */
    function RemoveDir($pathDirectory) {
        if (is_dir($pathDirectory)) {
            $allObjects = array_diff(scandir($pathDirectory), array(".", ".."));
        
            foreach ($allObjects as $object) {
                (is_dir($pathDirectory . DIRECTORY_SEPARATOR . $object)) ? RemoveDir($pathDirectory . DIRECTORY_SEPARATOR . $object) : unlink($pathDirectory . DIRECTORY_SEPARATOR . $object);
            }

            return rmdir($pathDirectory);
        }

        return FALSE;
    }
    /**
    * Substitui qualquer caracter reservado XML para seu equivalente em entitie X/HTML.
    *
    * @param {String} $str String original.
    *
    * @return {String}
    */
    function ConvertStringToXMLValue($str) {
        $str = mb_str_replace("&amp;", "§", $str);
        $str = mb_str_replace("&", "§", $str);
        $str = mb_str_replace("§", "&amp;", $str);
        $str = mb_str_replace("<", "&lt;", $str);
        $str = mb_str_replace(">", "&gt;", $str);
        $str = mb_str_replace("'", "&apos;", $str);
        $str = mb_str_replace("\"", "&quot;", $str);
        return $str;
    }
    /**
    * Substitui entities X/HTML para seus caracteres equivalentes.
    *
    * @param {String} $str String original.
    *
    * @return {String}
    */
    function ConvertXMLValueToString($str) {
        $str = mb_str_replace("&amp;", "&", $str);
        $str = mb_str_replace("&lt;", "<", $str);
        $str = mb_str_replace("&gt;", ">", $str);
        $str = mb_str_replace("&apos;", "'", $str);
        $str = mb_str_replace("&quot;", "\"", $str);
        return $str;
    }
    /**
    * Gera um nome novo para um arquivo que deseja clonar dentro de um mesmo diretório.
    *
    * @param {String} $file Caminho completo até o arquivo clone.
    *
    * @return {String}
    */
    function CreateNameForCloneFile($file) {
        // Se não existe um arquivo no local alvo com mesmo nome...
        if(!file_exists($file)) {
            return $file;
        }
        else {
            $fileExt = "";
            $fileName = "";
            $filePath = "";
            $fileNameParts = explode(DIRECTORY_SEPARATOR, $file);

            // Resgata a extenção do mesmo.
            $fileExt = explode(".", $file);
            $fileExt = "." . $fileExt[count($fileExt) - 1];


            // Resgata o nome do arquivo.
            $fileName = $fileNameParts[count($fileNameParts) - 1];
            $fileName = str_replace($fileExt, "", $fileName);
            

            // Caminho completo até o diretório onde o arquivo será copiado
            array_pop($fileNameParts);
            $filePath = implode(DIRECTORY_SEPARATOR, $fileNameParts);

            $newFileName = "";
            $count = 0;
            do {
                $count++;
                $newFileName = $filePath . DIRECTORY_SEPARATOR . $fileName . "_Copy(" . $count . ")" . $fileExt;
            } while(file_exists($newFileName));


            return $newFileName;
        }
    }
    /**
    * Replace all occurrences of the search string with the replacement string.
    *
    * @param mixed $search
    * @param mixed $replace
    * @param mixed $subject
    * @param int $count
    *
    * @return mixed
    */
    if (!function_exists("mb_str_replace")) {
        function mb_str_replace($search, $replace, $subject, &$count = 0) {
            if (!is_array($subject)) {
                // Normalize $search and $replace so they are both arrays of the same length
                $searches = is_array($search) ? array_values($search) : array($search);
                $replacements = is_array($replace) ? array_values($replace) : array($replace);
                $replacements = array_pad($replacements, count($searches), "");
                
                foreach ($searches as $key => $search) {
                    $parts = mb_split(preg_quote($search), $subject);
                    $count += count($parts) - 1;
                    $subject = implode($replacements[$key], $parts);
                }
                } else {
                    // Call mb_str_replace for each subject in array, recursively
                    foreach ($subject as $key => $value) {
                        $subject[$key] = mb_str_replace($search, $replace, $value, $count);
                    }
                }
            return $subject;
        }
    }













    // EXECUTA A AÇÃO INDICADA
    // ----------
    switch($action) {



        // CRIA UM NOVO DIRETÓRIO
        // ----------
        case "createdir":
            if(mkdir($CurrentPath . DIRECTORY_SEPARATOR . $_REQUEST["DialogInput"], 0777)) {
                $ServerMessage = "Diretório criado com sucesso.";
                $ServerMessageClass = "sucess";
            }
            else {
                $ServerMessage = "Não foi possível criar novo diretório no local especificado.";
                $ServerMessageClass = "fail";
            }
            break;



        // CRIA UM NOVO ARQUIVO
        // ----------
        case "createfile":
            $nFile = fopen($CurrentPath . DIRECTORY_SEPARATOR . $_REQUEST["DialogInput"], "w");

            if($nFile === FALSE) {
                $ServerMessage = "Não foi possível criar novo arquivo no local especificado.";
                $ServerMessageClass = "fail";
            }
            else {
                $ServerMessage = "Arquivo criado com sucesso.";
                $ServerMessageClass = "sucess";
                fclose($nFile);
            }
            break;



        // PREPARA ARQUIVOS PARA SEREM RECORTADOS OU COLADOS.
        // ----------
        case "cutfiles":
        case "copyfiles":

            $SFiles = explode(",", $_REQUEST["SelectedItens"]);
            $nFile = fopen($BasePath . DIRECTORY_SEPARATOR . $TransferFile, "w");

            if($nFile === FALSE) {
                $ServerMessage = "Houve um erro ao gravar os dados na area de transferência.";
                $ServerMessageClass = "fail";
            }
            else {
                fwrite($nFile, $action);
                foreach($SFiles as $i => $file) {                    
                    fwrite($nFile, "\n" . $file);
                }
                fclose($nFile);

                $A = ($action == "cutfiles") ? "recortados" : "copiados";
                $ServerMessage = "Os itens selecionados foram " . $A . " para a área de transferência.";
                $ServerMessageClass = "sucess";
            }
            break;



        // EXCLUI OS ARQUIVOS SELECIONADOS.
        // ----------
        case "deletefiles":
            $isOK = TRUE;
            $SFiles = explode(",", $_REQUEST["SelectedItens"]);

            foreach($SFiles as $i => $file) {
                if($isOK) {
                    if(is_dir($file)) {
                        $isOK = RemoveDir($file);
                    }
                    else {
                        $isOK = unlink($file);
                    }
                }
            }


            if($isOK) {
                $ServerMessage = "Os itens selecionados foram excluidos.";
                $ServerMessageClass = "sucess";
            }
            else {
                $ServerMessage = "Houve um erro ao excluir um dos arquivos selecionados.";
                $ServerMessageClass = "fail";
            }
            break;



        // RENOMEIA UM ARQUIVO SELECIONADO.
        // ----------
        case "renamefile":
            $SFiles = explode(DIRECTORY_SEPARATOR, $_REQUEST["SelectedItens"]);
            $SFiles[count($SFiles) - 1] = $_REQUEST["DialogInput"];

            $oldFileName = $_REQUEST["SelectedItens"];
            $newFileName = implode(DIRECTORY_SEPARATOR, $SFiles);
                
            if(rename($oldFileName, $newFileName)) {
                $ServerMessage = "O arquivo foi renomeado.";
                $ServerMessageClass = "sucess";
            }
            else {
                $ServerMessage = "Houve um erro ao renomear o arquivo indicado.";
                $ServerMessageClass = "fail";
            }
            break;



        // COLA ARQUIVOS PREVIAMENTE SELECIONADOS.
        // ----------
        case "pastefiles" :
            $nFile = fopen($BasePath . DIRECTORY_SEPARATOR . $TransferFile, "rb");
            if($nFile === FALSE) {
                $ServerMessage = "A área de transferência está vazia.";
                $ServerMessageClass = "fail";
            }
            else {
                $toDo = NULL;
                $allFiles = array();
                $isOK = TRUE;

                while (($line = fgets($nFile)) !== false) {
                    if($toDo === NULL) {
                        $toDo = trim($line);
                    }
                    else {
                        array_push($allFiles, trim($line));
                    }
                }
                fclose($nFile);


                foreach($allFiles as $i=>$file) {
                    if($isOK) {
                        $fName = explode(DIRECTORY_SEPARATOR, $file);
                        $fName = $fName[count($fName) -1];
                        $fName = $CurrentPath . DIRECTORY_SEPARATOR . $fName;


                        if(is_dir($file)) {
                            $isOK = CopyDir($file, $fName);
                            if($isOK && $toDo == "cutfiles") {
                                $isOK = RemoveDir($file);
                            }
                        }
                        else {
                            if($file == $fName && $toDo == "cutfiles") {
                                $ServerMessage = "O local selecionado para colar é o mesmo da origem dos arquivos selecinados.";
                                $ServerMessageClass = "fail";
                                $isOK = FALSE;
                            }
                            else {
                                $isOK = copy($file, CreateNameForCloneFile($fName));
                                if($isOK && $toDo == "cutfiles") {
                                    $isOK = unlink($file);
                                }
                            }
                        }
                    }
                }


                if($isOK) {
                    $A = ($action == "cutfiles") ? "recortados" : "copiados";
                    $ServerMessage = "Os itens selecionados foram " . $A . " corretamente para o local selecionado.";
                    $ServerMessageClass = "sucess";
                    unlink($BasePath . DIRECTORY_SEPARATOR . $TransferFile);
                }
                else {
                    $ServerMessage = ($ServerMessage == "") ? "Houve um erro ao tentar colar os arquivos selecionados." : $ServerMessage;
                    $ServerMessageClass = ($ServerMessageClass == "") ? "fail" : $ServerMessageClass;
                }
            }
            break;



        // RECEBE ARQUIVOS ENVIADOS E ALOCA-OS NA PASTA DE TRABALHO ATUAL.
        // ----------
        case "uploadfiles":
            $getFiles = NULL;
            $hasInputs = TRUE;
            $count = 0;

            while($hasInputs) {
                $iName = "FileUpload_" . $count;
                if(isset($_FILES[$iName])) {
                    for($i = 0; $i < count($_FILES[$iName]["name"]); $i++) {
                        if($_FILES[$iName]["size"][$i] > 0) {
                            $tmp_name = $_FILES[$iName]["tmp_name"][$i];
                            $getFiles[$tmp_name] = $CurrentPath . DIRECTORY_SEPARATOR . $_FILES[$iName]["name"][$i];
                            
                        }
                    }
                    $count++;
                }
                else {
                    $hasInputs = FALSE;
                }
            }

            
            if(count($getFiles) > 0) {
                $isOK = TRUE;

                foreach($getFiles as $tmp_name => $destination) {
                    if($isOK) {
                        $isOK = move_uploaded_file($tmp_name, $destination);
                    }
                }

                if($isOK) {
                    $ServerMessage = "O upload dos arquivos foi bem sucedido.";
                    $ServerMessageClass = "sucess";
                }
                else {
                    $ServerMessage = "Houve um erro ao receber um ou mais arquivos enviados.";
                    $ServerMessageClass = "fail";
                }
            }
            break;



        // CARREGA DADOS DO ARQUIVO QUE SERÁ EDITADO.
        // ----------
        case "editfile":
            $editFileContent = "";

            if(!is_file($_REQUEST["SelectedItens"])) {
                $ServerMessage = "O arquivo indicado não é válido.";
                $ServerMessageClass = "fail";
            }
            else {
                $ext = explode(".", strtolower($_REQUEST["SelectedItens"]));
                if(count($ext) == 0) {
                    $ServerMessage = "Não foi possível reconhecer a extenção do arquivo selecionado.";
                    $ServerMessageClass = "fail";
                }
                else {
                    $ext = $ext[count($ext) - 1];
                    if(!in_array($ext, $editableFilesExt)) {
                        $ServerMessage = "O arquivo indicado não é editável.";
                        $ServerMessageClass = "fail";
                    }
                    else {
                        
                        $nFile = fopen($_REQUEST["SelectedItens"], "rb");
                        if($nFile === FALSE) {
                            $ServerMessage = "O arquivo indicado não foi encontrado.";
                            $ServerMessageClass = "fail";
                        }
                        else {
                            while (($line = fgets($nFile)) !== false) {
                                $editFileContent .= ConvertStringToXMLValue($line);
                            }
                            fclose($nFile);

                            // Converte o arquivo em uma versão UTF-8
                            $editFileContent = iconv(mb_detect_encoding($editFileContent, mb_detect_order(), true), "UTF-8", $editFileContent);
                        }
                    }
                }
            }
            break;



        // SALVA A EDIÇÃO DO ARQUIVO INDICADO.
        // ----------
        case "savefile":
            if(!is_file($_REQUEST["SaveFilePath"])) {
                $ServerMessage = "O arquivo indicado não é válido.!!!";
                $ServerMessageClass = "fail";
            }
            else {
                $ext = explode(".", strtolower($_REQUEST["SaveFilePath"]));
                if(count($ext) == 0) {
                    $ServerMessage = "Não foi possível reconhecer a extenção do arquivo selecionado.";
                    $ServerMessageClass = "fail";
                }
                else {
                    $ext = $ext[count($ext) - 1];
                    if(!in_array($ext, $editableFilesExt)) {
                        $ServerMessage = "O arquivo indicado não é editável.";
                        $ServerMessageClass = "fail";
                    }
                    else {
                        $nFile = fopen($_REQUEST["SaveFilePath"], "w");
                        if($nFile === FALSE) {
                            $ServerMessage = "O arquivo indicado não foi encontrado.";
                            $ServerMessageClass = "fail";
                        }
                        else {

                            // Converte o arquivo em uma versão UTF-8
                            $editFileContent = ConvertXMLValueToString($_REQUEST["Textarea_FileRaw"]);
                            $editFileContent = iconv(mb_detect_encoding($editFileContent, mb_detect_order(), true), "UTF-8", $editFileContent);

                            fwrite($nFile, $editFileContent);
                            fclose($nFile);

                            $ServerMessage = "A edição foi concluída com sucesso.";
                            $ServerMessageClass = "sucess";
                        }
                    }
                }
            }
            break;
    }














    // RESGATA OS ARQUIVOS E DIRETÓRIOS DENTRO DO LOCAL ATUALMENTE SELECIONADO
    // ----------
    $AllFilesInFolder = scandir($CurrentPath);
    $Dirs = array();
    $Files = array();

    foreach ($AllFilesInFolder as $key => $value) {
        if (!in_array($value, array(".",".."))) {
            if (is_dir($CurrentPath . DIRECTORY_SEPARATOR . $value)) {
                array_push($Dirs, $value);
            }
            else {
                if($value != $TransferFile && $value != $ThisScriptFile) {
                    array_push($Files, $value);
                }
            }
        }
    }
?>
        <div id="Shadow"></div>
        <div id="ServerMessage" class="<?php echo $ServerMessageClass?>"><?php echo $ServerMessage?></div>



        <form method="post" action="dnamanager.php" id="FormView">
            <input type="hidden" name="GoTo" value="" />
            <input type="hidden" name="Action" value="" />
            <input type="hidden" name="CurrentPath" value="<?php echo $CurrentPath?>" />


            <div>
                <?php if($action != "editfile") { ?>
                <table class="w-full">
		        <tr>
			        <td>
                        <input type="button" value="Criar Pasta" class="btn" onclick="CriarPasta();" title="Nova pasta" />
                        <input type="button" value="Criar Arquivo" class="btn" onclick="CriarArquivo();" title="Novo arquivo" />
                        <input type="button" value="Recortar" class="btn" onclick="RecortarArquivos()" title="Recortar items selecionados" />
                        <input type="button" value="Copiar" class="btn" onclick="CopiarArquivos()" title="Copiar itens selecionados" />
                        <input type="button" value="Colar" class="btn" onclick="ColarArquivos()" title="Colar itens selecionados dentro da pasta atual" />
                        <input type="button" value="Renomear" class="btn" onclick="RenomearArquivos()" title="Renomeia o arquivo selecionado" />
                        <input type="button" value="Apagar" class="btn" onclick="ApagarArquivos()" title="Apagar itens selecionados" />
                    </td>
                </tr>
                <tr>
	                <td> 
                        <br />
                        <strong>Pasta Atual : <?php echo $CurrentPath?></strong>
                        <br /><br />
                        <a onclick="ShowUploadDeArquivos()"><strong>Upload: Enviar arquivo para este diretorio</strong></a>
                        <br /><br /><br />
                    </td>
                </tr>
                <tr>
			        <td>

		        		<table class="table-content">
                            <thead>
                                <tr>
                                    <th style="width:25px;">
                                        <input type="checkbox" name="ToggleSelection" onclick="ToggleSelectForAllItens();" />
                                    </th>
                                    <th>Nome Arquivo | Folder</th>
                                    <th style="width:8%;">Ultima Alteração</th>
                                    <th style="width:8%;">Data Criação</th>
                                    <th style="width:8%;">Tamanho</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!$IsBase) {
                                ?>
                                <tr>
						            <td></td>
                                    <td><a class="lpath" onclick="GoTo('root');">[Root]</a></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
						            <td></td>
                                    <td><a class="lpath" onclick="GoTo('parent');">[Parent]</a></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php
                                }
                                $itemID = 0;

                                foreach($Dirs as $i => $name) {
                                    $tgt = $CurrentPath . DIRECTORY_SEPARATOR . $name;

                                    $createTime = date("d/m/Y H:i:s", filectime($tgt));
                                    $modTime = date("d/m/Y H:i:s", filemtime($tgt));
                                ?>

                                <tr>
						            <td>
                                        <input type="checkbox" name="itemID_<?php echo $itemID?>" value="<?php echo $tgt?>" />
                                    </td>
                                    <td>
                                        <a title="Clique para acessar" class="lpath dir" onclick="GoTo('<?php echo $name?>');"><?php echo $name?></a>
                                    </td>
                                    <td><?php echo $modTime?></td>
                                    <td><?php echo $createTime?></td>
                                    <td>0 Bytes</td>
                                </tr>
                                <?php
                                    $itemID++;
                                }
                                foreach($Files as $i => $name) {
                                    $tgt = $CurrentPath . DIRECTORY_SEPARATOR . $name;

                                    $createTime = date("d/m/Y H:i:s", filectime($tgt));
                                    $modTime = date("d/m/Y H:i:s", filemtime($tgt));
                                    $fileSize = round(filesize($tgt) / 1024, 2);

                                    $class = "file";
                                    $event = " onclick=\"EditFileItem('" . $itemID . "')\"";
                                    $label = "Clique para editar";
                                    $ext = explode(".", strtolower($name));
                                    

                                    if(count($ext) > 0) {
                                        $ext = $ext[count($ext) - 1];

                                        
                                        // Verifica se é um arquivo editável
                                        if(!in_array($ext, $editableFilesExt)) {
                                            $event = "";
                                        }


                                        // Identifica se é uma imagem
                                        if(in_array($ext, $imageExtensions)) {
                                            $label = "";
                                            $class = "image";
                                            $event = "";
                                            //$event = " onhover=\"ShowImageItem('" . $itemID . "')\"";
                                        }
                                    }
                                ?>

                                <tr>
						            <td>
                                        <input type="checkbox" name="itemID_<?php echo $itemID?>" value="<?php echo $tgt?>" />
                                    </td>
                                    <td>
                                        <a>
                                            <?php if($event != "") { ?>
                                            <span title="<?php echo $label?>" class="lpath <?php echo $class?>" <?php echo $event?>></span>
                                            <?php } ?>
                                            <span title="Abrir em nova janela" onclick="OpenFileItem('<?php echo $itemID?>');"><?php echo $name?></span>
                                        </a>
                                    </td>
                                    <td><?php echo $modTime?></td>
                                    <td><?php echo $createTime?></td>
                                    <td><?php echo $fileSize?> KB</td>
                                </tr>
                                <?php
                                    $itemID++;
                                }
                                ?>

                            </tbody>
                        </table>

                    </td>
                </tr>
		        <tr>
			        <td>
                        <br />
                        <a onclick="ShowUploadDeArquivos()"><strong>Upload: Enviar arquivo para este diretorio</strong></a>
                        <br /><br /><br />

                        <input type="button" value="Criar Pasta" class="btn" onclick="CriarPasta();" title="Nova pasta" />
                        <input type="button" value="Criar Arquivo" class="btn" onclick="CriarArquivo();" title="Novo arquivo" />
                        <input type="button" value="Recortar" class="btn" onclick="RecortarArquivos()" title="Recortar items selecionados" />
                        <input type="button" value="Copiar" class="btn" onclick="CopiarArquivos()" title="Copiar itens selecionados" />
                        <input type="button" value="Colar" class="btn" onclick="ColarArquivos()" title="Colar itens selecionados dentro da pasta atual" />
                        <input type="button" value="Renomear" class="btn" onclick="RenomearArquivos()" title="Renomeia o arquivo selecionado" />
                        <input type="button" value="Apagar" class="btn" onclick="ApagarArquivos()" title="Apagar itens selecionados" />
                    </td>
		        </tr>
                </table>
                <?php } else { ?>
                <input type="hidden" name="SaveFilePath" value="<?php echo $_REQUEST["SelectedItens"]?>" />

                <table class="w-full">
		        <tr>
			        <td>
                        <input type="button" value="Salvar" class="btn" onclick="SalvarAlteracoes();" title="Salvar" />
                        <input type="button" value="Voltar" class="btn" onclick="GoBack();" title="Voltar" />
                    </td>
                </tr>
                <tr>
	                <td>
                        <br />
                        <strong>Arquivo em alteração : <?php echo $_REQUEST["SelectedItens"]?></strong>
                        <br /><br />
                        <textarea rows="20" cols="30" name="Textarea_FileRaw" spellcheck="false"><?php echo $editFileContent?></textarea>
                        <br /><br /><br />
                    </td>
                </tr>
		        <tr>
			        <td>
                        <input type="button" value="Salvar" class="btn" onclick="SalvarAlteracoes();" title="Salvar" />
                        <input type="button" value="Voltar" class="btn" onclick="GoBack();" title="Voltar" />
                    </td>
                </tr>
                </table>
                <?php } ?>
            </div>
        </form>










        <form method="post" action="dnamanager.php" id="DialogBox" class="dialog-box">
            <input type="hidden" name="CurrentPath" value="<?php echo $CurrentPath?>" />
            <input type="hidden" id="Action" name="Action" value="" />
            <input type="hidden" id="SelectedItens" name="SelectedItens" value="" />

            <div id="DialogMsg" class="msg"></div>
            <div id="DialogBoxInput">
                <input type="text" id="DialogInput" name="DialogInput" />
            </div>
            <div class="controls">
                <input type="submit" name="Confirm" id="DialogConfirmYes" class="btn" value="Sim" onclick="return CheckAndSubmit();" />
                <input type="submit" name="Confirm" id="DialogConfirmNo" class="btn" value="Não" onclick="return HideDialog();" />
            </div>
        </form>




        <form method="post" action="dnamanager.php" id="UploadFiles" class="dialog-box" enctype="multipart/form-data">
            <input type="hidden" name="CurrentPath" value="<?php echo $CurrentPath?>" />
            <input type="hidden" id="Action" name="Action" value="uploadfiles" />

            <div id="UploadFilesMsg" class="msg"></div>

            <div id="UploadFilesBox">
                <div id="fset">
                </div>
                
                <input type="button" id="btnAddUploadFile" class="btn" value="Add" onclick="AddUploadFile()" />
            </div>
            <div class="controls">
                <input type="submit" name="Confirm" class="btn" value="Enviar" onclick="return FileUpload()" />
                <input type="submit" name="Confirm" class="btn" value="Cancelar" onclick="return HideDialog();" />
            </div>
        </form>









        <script>
            // ------------------------------------
            // CONTROLES PRINCIPAIS DA VIEW
            // ------------------------------------
            var actionType = '';



            /**
            * Verifica os dados setados e, caso passe na validação, envia-os.
            *
            * @function CheckAndSubmit
            *
            * @global
            *
            * @param {String}           id          Id do item.
            */
            var CheckAndSubmit = function () {
                var dI = Get('DialogInput').value;
                var validChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 _-.'


                switch (actionType) {
                    case 'createdir':
                        if (dI == '') {
                            alert('Digite o nome da nova pasta.');
                            return false;
                        }
                        else if (OnlyCharCollection(dI, validChars) != dI) {
                            alert('Use apenas espaço, letras, números e os caracteres "_", "-" e "." para o nome do diretório.');
                            return false;
                        }
                        break;

                    case 'createfile':
                        if (dI == '') {
                            alert('Digite o nome do arquivo.');
                            return false;
                        }
                        else if (OnlyCharCollection(dI, validChars) != dI) {
                            alert('Use apenas espaço, letras, números e os caracteres "_", "-" e "." para o nome do arquivo.');
                            return false;
                        }
                        break;
                        break;

                    case 'renamefile':
                        if (dI == '') {
                            alert('Digite o novo nome do arquivo.');
                            return false;
                        }
                        else if (OnlyCharCollection(dI, validChars) != dI) {
                            alert(OnlyCharCollection(dI, validChars));
                            alert('Use apenas espaço, letras, números e os caracteres "_", "-" e "." para o nome do arquivo.');
                            return false;
                        }
                        break;
                }

                return true;
            };










            var CriarPasta = function () {
                actionType = 'createdir';
                ShowDialog(actionType, 'Entre com o nome da pasta :', 'Ok', 'Cancelar', true);
            };
            var CriarArquivo = function () {
                actionType = 'createfile';
                ShowDialog(actionType, 'Entre com o nome do arquivo :', 'Ok', 'Cancelar', true);
            };
            var ColarArquivos = function () {
                actionType = 'pastefiles';

                var f = Get('DialogBox');
                f.Action.value = actionType;
                f.submit();
            };



            var RecortarArquivos = function () {
                _SelectFiles('cut');
            };
            var CopiarArquivos = function () {
                _SelectFiles('copy');
            };
            var ApagarArquivos = function () {
                _SelectFiles('delete');
                ShowDialog(actionType, 'Você tem certeza que deseja excluir os arquivos selecionados?', 'Ok', 'Cancelar', false);
            };
            var _SelectFiles = function (act) {
                if (act == 'cut') { actionType = 'cutfiles'; }
                else if (act == 'copy') { actionType = 'copyfiles'; }
                else if (act == 'delete') { actionType = 'deletefiles'; }

                GetSelectedItens();

                var f = Get('DialogBox');
                f.Action.value = actionType;

                if (f.SelectedItens.value == '') {
                    var sm = Get('ServerMessage');
                    sm.className = 'fail';
                    sm.innerHTML = 'Você precisa selecionar ao menos 1 arquivo ou pasta para executar esta ação.';
                    HasServerMessage();
                }
                else {
                    if (act != 'delete') {
                        f.submit();
                    }
                }
            };
            var RenomearArquivos = function () {
                actionType = 'renamefile';
                GetSelectedItens();

                var f = Get('DialogBox');
                var item = (f.SelectedItens.value == '') ? [] : f.SelectedItens.value.split(',');


                if (item.length != 1) {
                    var sm = Get('ServerMessage');
                    sm.className = 'fail';
                    if (item.length == 0) {
                        sm.innerHTML = 'Você precisa selecionar 1 arquivo ou pasta para executar esta ação.';
                    }
                    else {
                        sm.innerHTML = 'Apenas 1 arquivo ou pasta pode ser selecionado por vez para renomear.';
                    }
                    HasServerMessage();
                }
                else {
                    ShowDialog(actionType, 'Informe o novo nome para o item selecionado :', 'Ok', 'Cancelar', true);
                    var iName = item[0].split('\\');
                    if (iName[iName.length - 1].indexOf('/') != -1) { var iName = item[0].split('/'); }

                    f.DialogInput.value = iName[iName.length - 1];
                    f.DialogInput.focus();
                }
            };



            var ShowUploadDeArquivos = function () {
                var initi = 5;
                Get('fset').innerHTML = '';

                for (var i = 0; i < initi; i++) {
                    var iFile = document.createElement('input');
                    iFile.setAttribute('type', 'file');
                    iFile.setAttribute('multiple', 'multiple');
                    iFile.setAttribute('name', 'FileUpload_' + i + '[]');
                    Get('fset').appendChild(iFile);
                }


                Get('Shadow').style.display = 'block';
                Get('UploadFiles').style.display = 'block';
                Get('UploadFilesMsg').innerHTML = 'Selecione os arquivos que serão enviados.';

                AlignToCenter(Get('UploadFiles'), 'xy');
            };
            var FileUpload = function () {
                var aI = document.querySelectorAll('#fset input');
                var isSelected = false;

                for (var it in aI) {
                    if (aI[it].nodeType == 1 && aI[it].value != '') {
                        isSelected = true;
                    }
                }

                if (!isSelected) {
                    var sm = Get('ServerMessage');
                    sm.className = 'fail';
                    sm.innerHTML = 'Você precisa selecionar um arquivo para efetuar o upload.';

                    HideDialog();
                    HasServerMessage();

                    return false;
                }
                else {
                    Get('UploadFilesBox').style.display = 'none';
                    Get('UploadFilesMsg').innerHTML = 'Upload em andamento, por favor aguarde...';
                }
            };



            var SalvarAlteracoes = function () {
                var v = Get('FormView');
                v.Action.value = 'savefile';
                v.submit();
            };


















            // ------------------------------------
            // CONTROLES PARA OS ELEMENTOS DA VIEW
            // ------------------------------------
            /**
            * Mostra a caixa de dialogo e as opções que o usuário pode tomar.
            *
            * @function ShowDialog
            *
            * @global
            *
            * @param {String}           action                  Nome da ação que será executada.
            * @param {String}           msg                     Mensagem que será exibida para o usuário.
            * @param {String}           [lblYes = Sim]          Legenda do botão "sim".
            * @param {String}           [lblNo = Não]           Legenda do botão "não".
            * @param {String}           [showInput = false]     Legenda do botão "não".
            */
            var ShowDialog = function (action, msg, lblYes, lblNo, showInput) {
                lblYes = (lblYes === undefined) ? 'Sim' : lblYes;
                lblNo = (lblNo === undefined) ? 'Não' : lblNo;
                showInput = (showInput === undefined || showInput === false) ? 'none' : 'block';


                Get('Action').value = action;
                Get('Shadow').style.display = 'block';
                Get('DialogBoxInput').style.display = showInput;
                Get('DialogBox').style.display = 'block';

                AlignToCenter(Get('DialogBox'), 'xy');
                Get('DialogMsg').innerHTML = msg;
                Get('DialogConfirmYes').value = lblYes;
                Get('DialogConfirmNo').value = lblNo;
            };
            /**
            * Esconde a caixa de dialogo.
            *
            * @function HideDialog
            *
            * @global
            */
            var HideDialog = function () {
                Get('Action').value = '';

                Get('Shadow').style.display = 'none';
                Get('DialogBoxInput').style.display = 'none';
                Get('DialogBox').style.display = 'none';
                Get('UploadFiles').style.display = 'none';
                Get('DialogMsg').innerHTML = '';

                return false;
            };
            /**
            * Muda o foco da página para abrir o caminho indicado.
            *
            * @function GoTo
            *
            * @global
            *
            * @param {String}           tgt         Diretório alvo.
            */
            var GoTo = function (tgt) {
                var f = Get('FormView');
                f.GoTo.value = tgt;

                if (tgt != 'root' && tgt != 'parent') {
                    f.CurrentPath.value = f.CurrentPath.value + '\\' + tgt;
                }

                f.submit();
            };
            /**
            * Retorna à listagem anterior.
            *
            * @function GoBack
            *
            * @global
            */
            var GoBack = function () {
                var f = Get('FormView');
                f.submit();
            };
            /**
            * Abre o item clicado em uma nova página.
            *
            * @function OpenFileItem
            *
            * @global
            *
            * @param {String}           id          Id do item.
            */
            var OpenFileItem = function (id) {
                var f = Get('FormView');
                var i = 'itemID_' + id;

                var bp = BasePath;
                while (bp.indexOf('\\') != -1) { bp = bp.replace('\\', '/'); }


                var tgt = f[i].value.replace(bp, '');
                while (tgt.indexOf('\\') != -1) { tgt = tgt.replace('\\', '/'); }

                console.log(bp);
                console.log(tgt);

                tgt = tgt.replace(bp, "");
                console.log(tgt);
                window.open(BaseURL + tgt, '_blank');
            };
            /**
            * Abre a edição do arquivo selecionado.
            *
            * @function EditFileItem
            *
            * @global
            *
            * @param {String}           id          Id do item.
            */
            var EditFileItem = function (id) {
                var f = Get('FormView');
                var i = 'itemID_' + id;

                var tgt = f[i].value;

                f = Get('DialogBox');
                f.Action.value = 'editfile';
                f.SelectedItens.value = tgt;
                f.submit();
            };
            /**
            * Verifica se há mensagem do servidor para ser mostrada.
            *
            * @function HasServerMessage
            *
            * @global
            */
            var HasServerMessage = function () {
                var sm = Get('ServerMessage');
                if (sm.innerHTML != '') {
                    sm.style.display = 'block';
                    setTimeout(function () {
                        var sm = Get('ServerMessage');
                        sm.style.display = 'none';
                        sm.className = '';
                    }, 8000);
                }
            };
            /**
            * Adiciona um novo input[file] ao form de upload.
            *
            * @function AddUploadFile
            *
            * @global
            */
            var AddUploadFile = function () {
                var aI = document.querySelectorAll('#fset input');

                var iFile = document.createElement('input');
				iFile.setAttribute('type', 'file');
                iFile.setAttribute('multiple', 'multiple');
                iFile.setAttribute('name', 'FileUpload_' + aI.length + '[]');

                Get('fset').appendChild(iFile);
            };
















            // ------------------------------------
            // CONTROLES PARA OS ELEMENTOS "CHECKBOX"
            // ------------------------------------
            /**
            * Marca ou desmarca todos os itens da view atual.
            *
            * @function ToggleSelectForAllItens
            *
            * @global
            *
            * @param {String}           id          Id do item.
            */
            var ToggleSelectForAllItens = function (id) {
                var f = Get('FormView');
                var selection = f.ToggleSelection.checked;

                var aI = document.querySelectorAll('#FormView input[type="checkbox"]');

                for (var it in aI) {
                    aI[it].checked = selection;
                }
            };
            /**
            * Resgata todos os itens selecionados e prepara-os para serem enviados
            * atravez do formulário "DialogBox" pelo campo "SelectedItens".
            *
            * @function GetSelectedItens
            *
            * @global
            */
            var GetSelectedItens = function () {
                var f = Get('FormView');
                var checkeds = document.querySelectorAll('#FormView .table-content tbody input[type="checkbox"]');

                var fValue = '';
                for (var it in checkeds) {
                    if (checkeds[it].checked) {
                        fValue += checkeds[it].value + ',';
                    }
                }
                fValue = fValue.slice(0, -1);

                var f = Get('DialogBox');
                f.SelectedItens.value = fValue;
            };















            // ------------------------------------
            // FUNÇÕES AUXILIARES
            // ------------------------------------
            var Get = function (id) { return document.getElementById(id); }


            /**
            * Posiciona os Nodes selecionados ao centro do node Pai.
            * @desc Posiciona os Nodes selecionados ao centro do node Pai usando as propriedades CSS Top e Left.
            * Se "pn" não for informado, a referência é o objeto "parentNode" mais próximo de "o".
            *
            * @function AlignToCenter
            *
            * @global
            *
            * @param {Object[]}                     o                                       Objetos que serão posicionados.
            * @param {String}                       ax                                      Eixos que serão usados [x|y|xy].
            * @param {Object}                       [pn]                                    Objeto pai(parentNode) que será a referencia para o posicionamento.
            */
            var AlignToCenter = function (o, ax, pn) {
                o = (o.constructor === Array) ? o : [o];

                // Para cada node selecionado
                for (var i = 0; i < o.length; i++) {
                    var el = o[i];
                    var nd = (pn === undefined) ? el.parentNode : pn;
                    var nT = nd.tagName.toLowerCase();

                    // Se for indicado que é para alinhar pelo eixo 'x'
                    if (ax.indexOf('x') != -1) {
                        var pW = (nT == 'body' || nT == 'html') ? window.innerWidth : nd.offsetWidth;
                        el.style.left = Math.round((parseInt(pW) - parseInt(el.clientWidth)) / 2) + 'px';
                    }
                    // Se for indicado que é para alinhar pelo eixo 'y'
                    if (ax.indexOf('y') != -1) {
                        var pH = (nT == 'body' || nT == 'html') ? window.innerHeight : nd.offsetHeight;
                        el.style.top = Math.round((parseInt(pH) - parseInt(el.clientHeight)) / 2) + 'px';
                    }
                }
            };
            /**
            * Mantem apenas os caracteres que pertencem a string de válidos.
            *
            * @function OnlyCharCollection
            *
            * @global
            *
            * @param {String}                           v                                       String original.
            * @param {String}                           valid                                   Caracteres que são permitidos (caseSensitive).
            *
            * @return {String}
            */
            var OnlyCharCollection = function (v, valid) {
                var s = v;
                var sR = '';

                for (var i = 0; i < s.length; i++) {
                    var c = s.charAt(i);

                    for (var j = 0; j < valid.length; j++) {
                        if (c == valid.charAt(j)) { sR += c; j = valid.length; }
                    }
                }

                return sR;
            };




        </script>
        <script>
            // Verifica se o servidor enviou alguma mensagem.
            HasServerMessage();
        </script>
    </body>
</html>