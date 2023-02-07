<?php
if(!isset($_SESSION["where"])) $_SESSION["where"] = "";
$palavra = "";
if(isset($_REQUEST["palavra"])) $palavra = $_REQUEST["palavra"];
$modalidade = "";
if(isset($_REQUEST["modalidade"])) $modalidade = $_REQUEST["modalidade"];
$periodo = "";
if(isset($_REQUEST["periodo"])) $periodo = $_REQUEST["periodo"];

//var_dump($_SESSION["where"]);
if($palavra != "") $_SESSION["where"] .= " and (Institucional_nome like '%" . removeAcentos($palavra) . "%' or Institucional_descricao like '%" . removeAcentos($palavra) . "%') ";
if($modalidade != "") $_SESSION["where"] .= " and Institucional_fonte = '" . removeAcentos($modalidade) . "' ";
if($periodo != "") $_SESSION["where"] .= " and year(Institucional_data) = '" . $periodo . "' ";
?>

<div class="panel panel-light margin-bottom20" style="margin-top: 0px;">
<form action="" class="sky-form">
	<fieldset>
		<div class="col-md-5 col-sm-5">
			<section>
				<label class="label">Buscar por palavra, objeto, nº do edital</label>
				<label class="input">
					<input type="text" name="palavra" value="<?php echo $palavra ;?>">
				</label>
			</section>
		</div>

		<div class="col-md-3 col-sm-3">
			<section>
				<label class="label">Modalidade</label>
				<label class="select">
					<select name="modalidade">
						<option Value="">Selecione</option>
						<?php
							$Rs1 = mysqli_query($con,"select Mascara_fonte_valores from mascara where Mascara_id = 51");

							If(mysqli_num_rows($Rs1) > 0){
								$row1 = mysqli_fetch_assoc($Rs1);
								$Mascara_fonte = explode(Chr(13), $row1["Mascara_fonte_valores"]);
								for($i=0; $i < count($Mascara_fonte); $i++) {
									$selecionado = "";
									if($modalidade == formataEnter($Mascara_fonte[$i])) $selecionado = " selected ";
									echo "<option value='" . utf8_encode(removeAcentos(formataEnter($Mascara_fonte[$i]))) . "' " . $selecionado . ">" . formataEnter($Mascara_fonte[$i]) . "</option>";
								}
							}
						?>

					</select>
					<i></i>
				</label>
			</section>
		</div>

		<div class="col-md-2 col-sm-2">
		<section>
			<label class="label">Ano</label>
			<label class="select">
				<select name="periodo">
					<option Value="">Selecione</option>
					<?php
					$ano_atual =  date('Y');
					$data_combo = "";

					for($i=$ano_atual - 2; $i <= $ano_atual; $i++) {
						$selecionado = "";
						if($periodo == $i) $selecionado = " selected ";
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