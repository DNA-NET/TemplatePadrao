<?php

require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once(dirname(__FILE__) . '/common.php');

$f = $_FILES["thefile"];
$folder = $_POST["folder"];

$target = XF_ASSET_BASE . "$folder";

try {
    
    //validate
    $pathinfo = pathinfo($f["name"]);
    $ext = strtolower(isset($pathinfo["extension"])?$pathinfo["extension"]:"N/A");
    if(!in_array("*", $__UPLOAD_FILE_TYPES) && !in_array($ext, $__UPLOAD_FILE_TYPES)) {
        //exit and return error
        echo json_encode(array(
            "status"=>false,
            "message"=>$__XFLANG["ERR_UPLOAD_001"]
        ));
        exit();
    }
    
    if($f["size"] > XF_MAX_SIZE) {
        echo json_encode(array(
            "status"=>false,
            "message"=>$__XFLANG["ERR_UPLOAD_004"] . "(" . XF_MAX_SIZE . ")"
        ));
        exit();
    }
    
    $filename = $f["name"];
    if(file_exists("$target/$filename")) {
        $ts = date("YmdHis");
        $filename = "{$pathinfo["filename"]}{$ts}.$ext"; 
    }
    
    if(move_uploaded_file($f["tmp_name"], $target . "/" . $filename)) {

        $pathinfo = pathinfo($filename);
        $ext = strtolower(isset($pathinfo["extension"])?$pathinfo["extension"]:"N/A");
        $dim = "";

        if(is_image($ext)) {
            
            $thumbnail = get_thumbnail($folder, $filename);
            $type = 'image';
            $size = getimagesize($target . "/{$filename}");
            $dim = "{$size[0]}x{$size[1]}";
            
        } else {
            $thumbnail = "images/document.png";
            $type = 'document';
        }
        
        $upfile = array("thumbnail"=>$thumbnail,
            "virtual_base"=>XF_ASSET_BASE_VIRTUAL,
            "folder"=>$folder, 
            "file"=>$filename, 
            "type"=>$type,
            "ext"=>$ext,
            "dim"=>$dim,
            "files_count"=>0,
            "status"=>true);

        echo json_encode($upfile);
        
    } else {
        
        echo json_encode(array(
            "status"=>false,
            "message"=>$__XFLANG["ERR_UPLOAD_002"]
            ));
        
    }    
} catch (Exception $ex) {
    
    echo json_encode(array(
        "status"=>false,
        "message"=>$__XFLANG["ERR_UPLOAD_003"] . $ex
        ));
    
}

?>