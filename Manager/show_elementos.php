<?php 
include_once 'conexao.php';
include_once 'ado.php'; 
?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>

	<?php
	$RSGrupos = db_query($con,"SELECT Elemento_id, Elemento, Elemento_thumb from elemento order by Elemento_nome");

	while($RSResultado=db_fetch_array($RSGrupos)) {
		//echo "<div id=\"Elemento" . $RSResultado["Elemento_id"] . "\" data-thumb=\"" . $_SESSION["Dominio_url_producao"] . "/manager/show.php?show_arquivo=Elemento&show_campo=Elemento_thumb&show_chave=Elemento_id=" . $RSResultado["Elemento_id"] . "\">" . $RSResultado["Elemento"] . "</div>";
		echo "<div id=\"Elemento" . $RSResultado["Elemento_id"] . "\" data-thumb=\"data:image/jpg;base64," . base64_encode($RSResultado["Elemento_thumb"]) . "\"><div>" . $RSResultado["Elemento"] . "</div></div>";
	}
	?>
    </body>
</html>