<?php

	// includes
	include_once($_SERVER['DOCUMENT_ROOT'].'/fccr/portal/incs/uteis/dbConnectionMssql.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/fccr/portal/incs/uteis/mssql.class.php');
		
	// instancia conexão
	$conn = new mssql();	
	
	// se selecionou vazio
	if(trim($_POST['uf_selecionada'])==""){
		
		$html_ret = '<option value="">Primeiro selecione a UF</option>';		
		
	}else{
	
		// query para trazer cidades pela uf informada
		$query = "select cidade from scf..scdt03 where uf = '".$_POST['uf_selecionada']."' order by cidade";
		$ret = $conn->query($query);
		$html_ret = '<option value="">Selecione</option>';

		while($row = $conn->fetch_assoc($ret)){
			$html_ret .= '<option value="'.$row['cidade'].'">'.$row['cidade'].'</option>';
		}
		
	}

	echo $html_ret; 
	
	return true;
?>