<?php 
require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

$area_ajuda = "";
if(isset($_REQUEST["area_ajuda"])) $area_ajuda = $_REQUEST['area_ajuda'];

if($area_ajuda == "lgpd" && !isset($_SESSION["funcao"])) {
	require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
	if(!isset($_SESSION["funcao"])) $_SESSION["funcao"] = "";
} else {
}
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include_once '../Cabecalho.php'; ?>
    </head>
	<body class="smoothscroll">
		<div id="wrapper">
			<?php 
			if($area_ajuda == "lgpd" && $_SESSION["funcao"] == '') {
				echo '<div class="container" style="padding:15px; padding-left:22px;"><a class="logo" href="/atualizadxp/Manager/index.php"><img alt="Atualiza - Sistema de Gerenciamento" src="/atualizadxp/Manager/imagens/logo.png" border="0" style="max-height: 80px; margin-bottom:2px;"></a></div>';
			} else {
				include_once '../MenuPrincipal.php'; 
				echo Permissao_Aplicativo();
			}

			$Conteudo = "";
			if(isset($_POST["inpHtml"])) {
				$Conteudo = str_replace("'","''",stripslashes($_POST["inpHtml"]));
				$_SESSION["mensagem"] = "Wiki salvo com sucesso!";
				file_put_contents($area_ajuda . ".html", $Conteudo);
			}

			if(file_exists($area_ajuda . ".html")) $Conteudo = file_get_contents($area_ajuda . ".html");
			?>



			<link href="../assets/minimalist-blocks/content.css" rel="stylesheet" type="text/css" />
			<link href="../contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />

			<style type="text/css">
				.nao_mostra_site {
					display: block !important;
				}

				.meiodatela {width:100%; border-top: #eee 1px solid;background:rgba(255,255,255,0.95);position:fixed;bottom:0;padding:10px;box-sizing:border-box;text-align:center;white-space:nowrap;z-index:10001;}

				.box-content {
					display: '' !important;
			}

			</style>


			<div class="container">
				<div id="contentarea" class="contentarea margin-bottom80 margin-top80" style="text-align: justify;">
				<?php echo stripslashes($Conteudo); ?>
				</div>

				<?php if(isset($_SESSION["funcao"]) && $_SESSION["funcao"] == 'Wiki') {?>
					<form id="form1" method="post" style="display:none">
						<input type="hidden" id="area_ajuda" name="idcampo" value="<?php echo $area_ajuda; ?>" />
						<input type="hidden" id="inpHtml" name="inpHtml" />
						<input type="submit" id="btnPost" value="submit" />
					</form>


					<div id="meiodatela" style="z-index: 999; width:100%" class="meiodatela">
					<button id="btnSave" class="btn btn-success" style="height:40px; border-radius:3px;">Salvar Wiki</button>
					</div>

					<script>
						var pasta_inicial = '../';	
					</script>

					<script src="../contentbuilder/contentbuilder.min.js" type="text/javascript"></script>
					<script src="../assets/minimalist-blocks/content.js" type="text/javascript"></script>

					<script type="text/javascript">

						var builder = new ContentBuilder({
							container: '.contentarea',

							onFileSelectClick: function(selEv) {
								dlg = new XPROFileDialog({
									url: "../assetmanager/assetmain.php?atualiza=sim",
									onSelect: function(data) {							
										var inp = jQuery(selEv.targetInput).val(data.url);
									}
								});
								dlg.open();
							},

							onImageSelectClick: function(selEv) {
								dlg = new XPROFileDialog({
									url: "../assetmanager/assetmain.php?atualiza=sim",
									onSelect: function(data) {					
										var inp = jQuery(selEv.targetInput).val(data.url);
									}
								});
								dlg.open();
							},

							toolbar: 'left'
						});

						jQuery(document).ready(function ($) {

							var btnSave = document.querySelector('#btnSave');
							btnSave.addEventListener('click', (e) => {
								
								builder.saveImages('../saveimage.php?atualiza=sim', function(){
									
									//Get html
									//var html = builder.html(); //Get content

								});

							});


						});

						function save() {
							
							//Mostra_modal('<p class=aguarde>AGUARDE, Salvando imagens ..........</p>');
							alert('As imagens binárias serão armazenadas em disco na pasta conteudo/Upload e as URLs alteradas automaticamente!');

							//Save Images
							builder.saveImages('saveimage.php', function(){
								Mostra_modal('<p class=\"info\">Imagens do conteúdo foram salvas com sucesso!.</p>');
							});
						}

						var btnSave = document.querySelector('#btnSave');
						btnSave.addEventListener('click', (e) => {
							
							builder.saveImages('../Manager/saveimage.php?atualiza=sim', function(){
								
								//Get html
								var html = builder.html(); //Get content

								//Submit the html to the server for saving. For example, if you're using html form:
								document.querySelector('#inpHtml').value = html;
								document.querySelector('#btnPost').click();

							});

						});

					</script>
				<?php }?>

			</div>
			<?php include_once '../rodape.php'; ?>
		</div>
    </body>
</html>