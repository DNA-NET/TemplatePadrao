<?php
if(!isset($_SESSION["where"])) $_SESSION["where"] = "";
$palavra = "";
if(isset($_REQUEST["palavra"])) $palavra = $_REQUEST["palavra"];
$periodo = "";
if(isset($_REQUEST["periodo"])) $periodo = $_REQUEST["periodo"];

//var_dump($_SESSION["where"]);
if($palavra != "") $_SESSION["where"] .= " and (Institucional_nome like '%" . removeAcentos($palavra) . "%' or Institucional_descricao like '%" . removeAcentos($palavra) . "%') ";
if($periodo != "") $_SESSION["where"] .= " and concat('||', Institucional_fonte) like '%||" . $periodo . "%' ";
?>

<div class="panel panel-light margin-bottom20" style="margin-top: 0px;">
<form action="" class="sky-form">
	<fieldset>
		<div class="col-md-7 col-sm-7">
		<section>
			<label class="label">Buscar por nome:</label>
			<label class="input">
				<input type="text" name="palavra" value="<?php echo $palavra ;?>">
			</label>
		</section>
		</div>

		<div class="col-md-3 col-sm-3">
		<section>
			<label class="label">Período</label>
			<label class="select">
				<select name="periodo">
					<option Value="">Selecione</option>
					<?php
					$ano_atual =  date('Y');
					$data_combo = "";

					for($i=1993; $i <= $ano_atual; $i++) {
						$selecionado = "";
						//if($periodo == formataEnter($Mascara_fonte[$i])) $selecionado = " selected ";
						$data_combo = "<option value='" . $i . "' " . $selecionado . ">" . $i . "</option>" . $data_combo;
					}
					echo $data_combo;
					?>
				</select>
				<i></i>
			</label>
		</section>
		</div>

		<div class="col-md-2 col-sm-2">
			<button type="submit" class="button" style="margin-top:25px;">Buscar</button>
		</div>

	</fieldset>

</form>
</div>