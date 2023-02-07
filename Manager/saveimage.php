<?php
// Inicia a sessão
if(!isset($_SESSION)) {
    session_cache_expire(10800000);
	session_start();
}
include_once 'config.php';

header('Cache-Control: no-cache, must-revalidate');

//Specify url path
$path = $_SERVER['DOCUMENT_ROOT'] . $Dominio_Pasta . '/conteudo/upload/'; // Physical path, relative to this file (saveimage.php)
$urlpath = $path; // Use this in case URL path is different than physical path. For example: $urlpath = '/admin/contentbox/uploads/';

//Read image
$count = $_REQUEST['count'];
$b64str = $_REQUEST['hidimg-' . $count]; 
$imgname = $_REQUEST['hidname-' . $count]; 
$imgtype = $_REQUEST['hidtype-' . $count]; 

$customvalue = $_REQUEST['hidcustomval-' . $count]; //Get customvalue  

//Generate random file name here
if($imgtype == 'png'){
	$image = $imgname . '-' . base_convert(rand(),10,36) . '.png'; 
} else {
	$image = $imgname . '-' . base_convert(rand(),10,36) . '.jpg'; 
}



//Check folder. Create if not exist
$pagefolder = $path;
if (!file_exists($pagefolder)) {
	mkdir($pagefolder, 0777);
} 


//Optional: If using customvalue to specify upload folder
if ($customvalue!='') {
  $pagefolder = $path . $customvalue. '/';
  if (!file_exists($pagefolder)) {
	  mkdir($pagefolder, 0777);
  } 
  $urlpath = $urlpath . $customvalue. '/';
}



//Save image

$success = file_put_contents($pagefolder . $image, base64_decode($b64str)); 
if ($success === FALSE) {

  if (!file_exists($path)) {
    echo "<html><body onload=\"alert('Saving image to folder failed. Folder ".$pagefolder." not exists.')\"></body></html>";
  } else {
    echo "<html><body onload=\"alert('Saving image to folder failed. Please check write permission on " .$pagefolder. "')\"></body></html>";
  }
    
} else {
  //Replace image src with the new saved file
  $urlpath = str_replace($_SERVER['DOCUMENT_ROOT'],'', $urlpath);

  echo "<html><body onload=\"parent.document.getElementById('img-" . $count . "').setAttribute('src','" . $urlpath . $image . "');  parent.document.getElementById('img-" . $count . "').removeAttribute('id') \"></body></html>";
}


?>
