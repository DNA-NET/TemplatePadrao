<?php
if($_REQUEST["tabela"] !="" && $_REQUEST["chave"] !="" && $_REQUEST["id"] !="" && $_REQUEST["campo"] !="") {
	include('conexao.php');
    $query = "select " . $_REQUEST["campo"] . " from " . $_REQUEST["tabela"] . " where " . $_REQUEST["chave"] . " = " . $_REQUEST["id"];
    $result = db_query($con, $query);

	if(db_num_rows($result)){
		$row = mysqli_fetch_assoc($result);
		echo '<img src="data:image/jpg;base64,' . base64_encode($row[$_REQUEST["campo"]]) . '">';
	} 
}
?>