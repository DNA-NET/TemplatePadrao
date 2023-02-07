<?php
// Salva o conteúdo da página
$Secoes_permitidas = "";
$funcao = "";
if(isset($_SESSION["Secoes_permitidas"])) $Secoes_permitidas = $_SESSION["Secoes_permitidas"];
if(isset($_SESSION["funcao"])) $funcao = $_SESSION["funcao"];

if(isset($_SESSION["Funcionarios_id"]) && isset($_SESSION["Institucional_id"]) && $funcao == 'Publicador' && strpos("," . $Secoes_permitidas . ",","," . $_SESSION["secao_id"] . ",")) {


	//=============================================================================
	// Verifica o tipo de template
	//=============================================================================

	$conteudo_tipo = "";
	if(isset($_SESSION["secao_link"])) {
		$RSUser = db_query($con,"select CONTEUDO_TIPO from conteudo where CONTEUDO_CAMPO = '" . $_SESSION["secao_link"] . "'");
		if(db_num_rows($RSUser) > 0){
			$row = db_fetch_assoc($RSUser);
			$conteudo_tipo = $row["CONTEUDO_TIPO"];
		}
	}

	//=============================================================================
	// Verifica máscara de conteúdo
	//=============================================================================

	$MASCARA_TEXTO_SCRIPT = "";
	$mascara_id = "";
	$RSUser = db_query($con,"SELECT mascara.mascara_id, MASCARA_TEXTO_SCRIPT FROM mascara, institucional WHERE mascara.mascara_id = institucional.mascara_id and institucional.Institucional_id  = " . $_SESSION["Institucional_id"]);
	if(db_num_rows($RSUser) > 0){
		$row = db_fetch_assoc($RSUser);
		$MASCARA_TEXTO_SCRIPT = $row["MASCARA_TEXTO_SCRIPT"];
		$mascara_id = $row["mascara_id"];
	}

	if($MASCARA_TEXTO_SCRIPT == 'template' && $mascara_id == 23 && $conteudo_tipo != 'Menu' && $conteudo_tipo != 'Lista') {

		$Conteudo = "";
		if(isset($_POST["inpHtml"])) {
			$SQL_query = "UPDATE institucional set institucional_texto = '" . str_replace("'","''",stripslashes($_POST["inpHtml"])) . "' where  institucional_id = " . $_POST["idcampo"];
			db_query($con,$SQL_query);

			//=============================================================================
			// Cria uma versão do conteúdo - INICIO
			//=============================================================================

			$Sql = "institucional_texto = '" . str_replace("'","''",stripslashes($_POST["inpHtml"])) . "'";

			$RS_coneudo_atual = "select * from institucional where Institucional_id  = " . $_SESSION["Institucional_id"];

			$sql_result = mysqli_query($con, $RS_coneudo_atual);

			for($i = 0; $i < mysqli_num_fields($sql_result); $i++) {
				$field_info = mysqli_fetch_field($sql_result);
				$col = $field_info->name;

				$RS = db_query($con, "select " . $col . " from institucional where Institucional_id = " . $_SESSION["Institucional_id"]);
				if(db_num_rows($RS) > 0) {
					$row = db_fetch_assoc($RS);
					if($col != 'Institucional_texto' && $col != 'pais_id' && $col != 'idioma_id' && $col != 'Institucional_autor_id' && $col != 'Institucional_excluir' && $col != 'Institucional_comentarios' && $col != 'INSTITUCIONAL_VAL_CONTEUDO' && $col != 'Institucional_excluir' && $col != 'INSTITUCIONAL_ID_ALTERACAO' && $col != 'Institucional_comentarios' && $col != 'institucional_servico_unidade_movel' && $col != 'Institucional_visitas' && $col != 'Institucional_palestrante' && $col != 'Institucional_certificado' && $col != 'Institucional_local' && $col != 'Institucional_eventos') $Sql .= ", " . $col . " = '" . $row[$col] . "'";
				}
			}

			$Institucional_versao_id = Seq("Institucional_versao");

			db_query($con, "insert into institucional_versao (Institucional_versao_id, Institucional_id, Institucional_val_conteudo, Institucional_excluir, Institucional_DataVersao, Funcionarios_id) VALUES (" . $Institucional_versao_id . ", " . $_SESSION["Institucional_id"] . ", 0, 0, '" . formatarData(date("d/m/Y")) . " " . date('H:i') . "', " . $_SESSION["Funcionarios_id"] . ")");

			$Sql_versao = "UPDATE institucional_versao SET " . $Sql . " where Institucional_versao_id=" . $Institucional_versao_id;
			//echo $Sql_versao;
			//exit();
			db_query($con, $Sql_versao);

			//=============================================================================
			// Cria uma versão do conteúdo - FIM
			//=============================================================================

			echo "<script>window.location.href = '?mensagem=Conteúdo atualizado com sucesso!';</script>";
			//$_SESSION["mensagem"] = "Conteúdo atualizado com sucesso!'";
		}
		?>

		<style type="text/css">

		.meiodatela {width:100%; border-top: #eee 1px solid;background:rgba(255,255,255,0.95);position:fixed;bottom:0;padding:10px;box-sizing:border-box;text-align:center;white-space:nowrap;z-index:10001;}

		.box-content {
			display: '' !important;
		}

		</style>

		<!-- Hidden Form Fields to post content -->
		<form id="form1" method="post" style="display:none">
			<input type="hidden" id="idcampo" name="idcampo" value="<?php if(isset($_SESSION["Institucional_id"])) echo $_SESSION["Institucional_id"]; ?>" />
			<input type="hidden" id="inpHtml" name="inpHtml" />
			<input type="submit" id="btnPost" value="submit" />
		</form>


		<div id="meiodatela" style="z-index: 999; width:100%" class="meiodatela">
		<button id="btnSave" class="btn btn-success" style="height:40px; border-radius:3px;">Salvar Conteúdo</button>
		</div>

		<script>
			var pasta_inicial = '<?php echo $Dominio_Pasta ?>/Manager/';
		</script>

		<script src="<?php echo $Dominio_Pasta ?>/Manager/assetmanager/xprofile_lang.js" type="text/javascript"></script>
		<script src="<?php echo $Dominio_Pasta ?>/Manager/assetmanager/xprofiledialog.js" type="text/javascript"></script>
		<link rel="stylesheet" href="<?php echo $Dominio_Pasta ?>/Manager/assetmanager/css/xprofiledialog.css" />   

		<script src="<?php echo $Dominio_Pasta ?>/Manager/contentbuilder/contentbuilder.min.js" type="text/javascript"></script>
		<script src="<?php echo $Dominio_Pasta ?>/Manager/assets/minimalist-blocks/content.js" type="text/javascript"></script>

		<script type="text/javascript">

		var builder = new ContentBuilder({
			container: '.conteudo',

			onFileSelectClick: function(selEv) {
				dlg = new XPROFileDialog({
					url: pasta_inicial +"assetmanager/assetmain.php",
					onSelect: function(data) {							
						var inp = jQuery(selEv.targetInput).val(data.url);
					}
				});
				dlg.open();
			},

			onImageSelectClick: function(selEv) {
				dlg = new XPROFileDialog({
					url: pasta_inicial +"assetmanager/assetmain.php",
					onSelect: function(data) {					
						var inp = jQuery(selEv.targetInput).val(data.url);
					}
				});
				dlg.open();
			},

			toolbar: 'left'
		});

		var btnSave = document.querySelector('#btnSave');
		btnSave.addEventListener('click', (e) => {
			
			builder.saveImages(pasta_inicial + '/saveimage.php', function(){
				
				//Get html
				var html = builder.html(); //Get content

				//Submit the html to the server for saving. For example, if you're using html form:
				document.querySelector('#inpHtml').value = html;
				document.querySelector('#btnPost').click();

			});

		});

		</script>
	<?php
	}
}
?>