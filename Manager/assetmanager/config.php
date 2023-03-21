<?php
if (isset($_REQUEST["atualiza"])) {
    //asset base local path, ***NO TRAILING SLASH***
    define("XF_ASSET_BASE", $_SERVER["DOCUMENT_ROOT"]  . "/atualizadxp/conteudo");
    //asset base virtual path - for displaying on page or link
    define("XF_ASSET_BASE_VIRTUAL", "/atualizadxp/conteudo");
} else {
    //asset base local path, ***NO TRAILING SLASH***
    define("XF_ASSET_BASE", $_SERVER["DOCUMENT_ROOT"] . $Dominio_Pasta_site . "/conteudo");
    //asset base virtual path - for displaying on page or link
    define("XF_ASSET_BASE_VIRTUAL", $Dominio_Pasta_site . "/conteudo");
}

//set readonly mode
define("XF_READ_ONLY", false);

//set allow delete
define("XF_ALLOW_DELETE", true);

//set allow rename
define("XF_ALLOW_RENAME", true);

//allow edit existing image (new in 2.0)
define("XF_ALLOW_EDIT", true);

//maximum upload size.
define("XF_MAX_SIZE", 2000000);

//automatic creating thumbnails.
define("XF_AUTO_THUMB_CREATION", true);

//max automatic thumbnail width (in px).
define("XF_AUTO_THUMB_MAX_WIDTH", 150);

//max automatic thumbnail height (in px).
define("XF_AUTO_THUMB_MAX_HEIGHT", 120);

//automatic thumbnail target folder.
define("XF_AUTO_THUMB_NAME", "xfthumbs");

define("XF_AUTO_THUMB_QUALITY", 90);

//-----------------------------------------
//boot up
//-----------------------------------------

//define image type group
$__IMAGE_FILES = array("jpg", "jpeg", "png", "gif");

//set allowed type for upload.
//$__UPLOAD_FILE_TYPES = array("*"); //allow everything - NOT RECOMMENDED!!!
$__UPLOAD_FILE_TYPES = array(
    "doc", "docx", "pdf", "txt",
    "csv", "xml", "xls", "xlsx",
    "pps", "ppsx", "ppt", "pptx",
    "odt", "odp",
    "gif", "jpg", "jpeg", "png",
    "zip", "rar",
    "htm", "html"
);

//define language
define("XF_LANGUAGE", "en-US");

//language file
$__XFLANG = parse_ini_file("server/i18n-" . XF_LANGUAGE . ".txt");

//For security, you can add user access/user authentication check below.
//for example:
//   if (not authenticated) die("");