	<?php
	if($_REQUEST["id"] !="") {
		include('conexao.php');
		$query = "select Elemento_thumb from elemento where Elemento_id = " . $_REQUEST["id"];
		$result = db_query($con, $query);

		if(db_num_rows($result)){
			$row = mysqli_fetch_assoc($result);
			echo "<img src=\"data:image/jpg;base64," . base64_encode($row["Elemento_thumb"]) . "\">";
		} 
	}
	?>
