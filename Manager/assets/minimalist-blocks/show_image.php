<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
if($_REQUEST["show_arquivo"] !="" && $_REQUEST["show_campo"] !="" && $_REQUEST["show_chave"] !="") {
    $query = "select " . $_REQUEST["show_campo"] . " from " . $_REQUEST["show_arquivo"] . " where " . $_REQUEST["show_chave"];
    $result = mysqli_query($con, $query);

	If(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_assoc($result);
		$file_content = $row[$_REQUEST["show_campo"]];
		//file_put_contents('../cache/imagens/' .$_REQUEST["show_campo"] . "_". str_replace('=', '_', $_REQUEST["show_chave"]) . '.jpg', $file_content);
		header('Content-Type: image/jpeg');
		echo $file_content;
	} 
}
?>