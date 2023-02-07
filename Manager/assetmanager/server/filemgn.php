<?php

require_once("../config.php");
require_once("common.php");

if(!defined("SCANDIR_SORT_ASCENDING")) {
    define("SCANDIR_SORT_ASCENDING", 0);
}

function sanitize_filename($f) {
    return str_replace("..", "", $f);
}

function get_files($folder, $filter) {
    
    $folder = sanitize_filename($folder);
    
    $target_folder = XF_ASSET_BASE . $folder;

    $fout = array();
    $fldr = array();

    if($folder != "" && $folder!=DIRECTORY_SEPARATOR) {
        //add up thumb
        $base = dirname($folder);
        if($base=="/" || $base=="\\") $base = "";
        $fldr[] = array("thumbnail"=>"images/up.png",
            "virtual_base"=>XF_ASSET_BASE_VIRTUAL,
            "folder"=>$base,
            "file"=>"", 
            "type"=>"up",
            "files_count"=>0);
    }    

    $files = scandir($target_folder, SCANDIR_SORT_ASCENDING);
    
    foreach($files as $f) {

        if($f=="." || $f=="..") continue;

        $path = "$target_folder/$f";
        
        if(is_file($path)) {
            
            $pathinfo = pathinfo($f);
            $ext = strtolower(isset($pathinfo["extension"])?$pathinfo["extension"]:"N/A");
            $dim = "";
            
            if(is_image($ext)) {
                
                if($filter != 'all' && $filter != 'images') continue;
                
                $thumbnail = get_thumbnail($folder, $f);                
                $type = 'image';
                $size = getimagesize($target_folder . "/$f");
                $dim = "{$size[0]}x{$size[1]}";
                
            } else {
                
                if($filter != 'all' && $filter != 'documents') continue;
                
                $thumbnail = "images/document.png";
                $type = 'document';
            }
            
            $fout[] = array("thumbnail"=>$thumbnail,
                "virtual_base"=>XF_ASSET_BASE_VIRTUAL,
                "folder"=>$folder, 
                "file"=>$f, 
                "type"=>$type,
                "ext"=>$ext,
                "dim"=>$dim,
                "files_count"=>0);
            
        } else if(is_dir($path) && $f!==XF_AUTO_THUMB_NAME) {
            
            $files_in_folder = scandir($path);
            $files_count = count($files_in_folder) - 2;
            //exclude thumbs folder.
            if(file_exists("$path/" . XF_AUTO_THUMB_NAME)) {
                $files_count--;
            }
            
            $fldr[] = array("thumbnail"=>"images/folder.png",
                "virtual_base"=>XF_ASSET_BASE_VIRTUAL,
                "folder"=>$folder, 
                "file"=>$f, 
                "type"=>"folder",
                "files_count"=>$files_count);
        }
    }
    
    echo json_encode(array(
        "folder"=>$folder,
        "files"=>array_merge($fldr, $fout)
    ));
    
}

function get_folder_tree($folder) {
    
    $folder = sanitize_filename($folder);
    
    $fout = array();
    
    $fout[] = array("name"=>"Root", "folder"=>"", "level"=>0);
    
    traverse_folder($folder, $fout);
    
    echo json_encode($fout);
    
}

function traverse_folder($folder, &$item, $level=1) {
    
    $folder = sanitize_filename($folder);
    
    $target_folder = XF_ASSET_BASE . $folder;
    
    $files = scandir($target_folder, SCANDIR_SORT_ASCENDING);

    foreach($files as $f) {
        
        if($f=="." || $f=="..") continue;
        
        $fname = "$target_folder/$f";
        
        if(is_dir($fname) && $f!==XF_AUTO_THUMB_NAME) {
            
            $item[] = array("name"=>$f, "folder"=>"$folder/$f", "level"=>$level);
            
            traverse_folder("$folder/$f", $item, $level+1);
            
        }
        
    }
    
}

function create_folder($base, $newfolder) {
    
    global $__XFLANG;
    
    $base = sanitize_filename($base);
    $newfolder = sanitize_filename($newfolder);
            
    $base_folder = XF_ASSET_BASE . $base;
    
    $err = array(
        "status"=>"fail",
        "message"=>""
    );
    
    if(file_exists($base_folder)) {
        try {
            if(mkdir($base_folder . "/" . $newfolder)){
                $err["status"] = "success";
                $err["folder"] = "$base/$newfolder";
            } else {
                $err["message"] = $__XFLANG["ERR_FILEOP_001"];
            }
        } catch (Exception $ex) {
            $err["message"] = $ex;
        }
    } else {
        $err["message"] = $__XFLANG["ERR_FILEOP_002"];
    }
    
    echo json_encode($err);
}

function delete_file($folder, $fname) {
    
    $folder = sanitize_filename($folder);
    $fname = sanitize_filename($fname);
    
    if($fname=="" || $fname=="/") return false;
    
    $target_file = XF_ASSET_BASE . "$folder/$fname";
    $target_thumb = XF_ASSET_BASE . "$folder/" . XF_AUTO_THUMB_NAME . "/$fname";
            
    if(file_exists($target_file)) {
        if(is_dir($target_file)) {
            //delete all files
            delete_folder("$folder/$fname");
        } else {
            unlink($target_file);
            if(file_exists($target_thumb)) {
                unlink($target_thumb);
            }
        }
    }
    
    echo json_encode(array("status"=>"success"));
    
}

function delete_folder($folder) {
    
    $folder = sanitize_filename($folder);
    
    if($folder=="" || $folder=="/") return false;

    $target_folder = XF_ASSET_BASE . "$folder";
    
    $files = scandir($target_folder);
    foreach($files as $f) {
        if($f=="." || $f=="..") continue;
        if(is_dir("$target_folder/$f")) {
            delete_folder("$folder/$f");
        } else {
            unlink("$target_folder/$f");
        }
    }
    rmdir($target_folder);
    
    return true;
    
}

function rename_file($folder, $new, $old) {
    
    global $__XFLANG;
    
    $error_msg = "";
    
    $folder = sanitize_filename($folder);
    $new = sanitize_filename($new);
    $old = sanitize_filename($old);
    
    $newname = "$folder/$new";
    $oldname = "$folder/$old";
    
    //check of old name exists
    if(file_exists(XF_ASSET_BASE . "$newname") 
            || trim($newname)=='' 
                || $newname==DIRECTORY_SEPARATOR) {
        //return fail
        $error_msg = $__XFLANG["ERR_FILEOP_003"];
    } else {
        
        try {
            //rename the file
            if(rename(XF_ASSET_BASE . "$oldname", XF_ASSET_BASE . "$newname")) {
                
                //rename its thumbnail if exits.
                $thumbfolder = XF_ASSET_BASE . "$folder/" . XF_AUTO_THUMB_NAME;
                if(is_file(XF_ASSET_BASE . "$newname") &&
                        file_exists($thumbfolder . "/$old" )) {
                    rename($thumbfolder . "/$old", $thumbfolder . "/$new");
                } 
                
                echo json_encode(array(
                    "status" => "success",
                    "message" => "",
                    "name" => $newname
                ));
                return;
                
            } else {
                
                $error_msg = $__XFLANG["ERR_FILEOP_004"];
                
            }        
        } catch (Exception $ex) {
            $error_msg = $__XFLANG["ERR_FILEOP_004"] . $ex;   
        }
        
    }
    
    
    echo json_encode(array(
        "status" => "fail",
        "message" => $error_msg
    ));
    
}

function save_base64_image($folder, $filename, $base64) {
    
    global $__UPLOAD_FILE_TYPES;
    global $__XFLANG;
    
    $folder = sanitize_filename($folder);
    $filename = sanitize_filename($filename);
    
    $pathinfo = pathinfo($filename);
    $ext = strtolower(isset($pathinfo["extension"])?$pathinfo["extension"]:"N/A");
    if(!in_array("*", $__UPLOAD_FILE_TYPES) && !in_array($ext, $__UPLOAD_FILE_TYPES)) {
        //exit and return error
        echo json_encode(array(
            "status"=>false,
            "message"=>$__XFLANG["ERR_UPLOAD_001"]
        ));
        exit();
    }
    
    list($type, $data) = explode('base64,', $base64);
    
    //check if file exists.
    if(file_exists(XF_ASSET_BASE . "$folder/$filename")) {
        $ts = date("YmdHis");
        $filename = "{$pathinfo["filename"]}{$ts}.$ext"; 
    }
    
    if(file_put_contents(XF_ASSET_BASE . "$folder/$filename", base64_decode($data))===false) {
        //error
        echo json_encode(array(
            "status"=>false,
            "message"=>$__XFLANG["ERR_UPLOAD_002"]
        ));
        
    } else {
        $thumbnail = get_thumbnail($folder, $filename);
            $type = 'image';
            $size = getimagesize(XF_ASSET_BASE . "{$folder}/{$filename}");
            $dim = "{$size[0]}x{$size[1]}";
            
        $upfile = array("thumbnail"=>$thumbnail,
            "virtual_base"=>XF_ASSET_BASE_VIRTUAL,
            "folder"=>$folder, 
            "file"=>$filename, 
            "type"=>"image",
            "ext"=>$ext,
            "dim"=>$dim,
            "files_count"=>0,
            "status"=>true);

        echo json_encode($upfile);
    }

    
}

$user_functions = get_defined_functions()["user"];

$function = $_POST["fn"];
$param_arr = $_POST["param"];

if(in_array($function, $user_functions)) {
    call_user_func_array($function, $param_arr);
}

?>