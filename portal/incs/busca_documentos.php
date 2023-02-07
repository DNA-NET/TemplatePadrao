<?php
if(!isset($_SESSION["where"])) $_SESSION["where"] = "";
$palavra = "";
if(isset($_REQUEST["palavra"])) $palavra = $_REQUEST["palavra"];
$periodo = "";
if(isset($_REQUEST["periodo"])) $periodo = $_REQUEST["periodo"];

//var_dump($_SESSION["where"]);
if($palavra != "") $_SESSION["where"] .= " and (Institucional_nome like '%" . removeAcentos($palavra) . "%' or Institucional_descricao like '%" . removeAcentos($palavra) . "%') ";
if($periodo != "") $_SESSION["where"] .= " and year(Institucional_data) = '" . $periodo . "' ";

?>

<div class="panel panel-light margin-bottom20" style="margin-top: 0px;">
<form action="" class="sky-form" style="margin-bottom: 20px;">
	<fieldset>
		<div class="col-md-7 col-sm-7">
		<section>
			<label class="label">Buscar por palavra chave</label>
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
					$Rs1 = mysqli_query($con,"select distinct year(Institucional_data) as ano from institucional, institucional_secao where institucional.institucional_id = institucional_secao.institucional_id and institucional_secao.secao_id = " . $_SESSION["secao_id"]);

					$selecionado = "";
					while($row1=mysqli_fetch_array($Rs1)) {
						if($periodo == $row1["ano"]) $selecionado = " selected ";
						echo "<option value='" . $row1["ano"] . "' " . $selecionado . ">" . $row1["ano"] . "</option>";
					}
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