<?php 
	require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php"; 
	inicio(); 
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php require_once('../portal/incs/Cabecalho.php'); ?>
    </head>
	<body class="smoothscroll">
		<div id="wrapper">
			<?php 
			require_once('../portal/incs/Menu.php');

			$conexao_hotsite = 0;
			$dominio_site = str_replace('/','', $Dominio_Pasta);
			$palavra = "";
			if(isset($_REQUEST["palavra"])) $palavra = $_REQUEST["palavra"];
			?>

			<!-- PAGE TOP -->
			<section class="page-title">
				<div class="container">

			<header>
               <ul class="breadcrumb"><!-- breadcrumb -->
                      <span><a href="../home/">Início</a></span>/ <span class="current">Busca</span>        
				</ul>                
                <h2>
						Resultados de busca por: <strong><?php echo $palavra; ?></strong>
				</h2>          
			
			</header>
				</div>			
			</section>
			<!-- /PAGE TOP -->



			<!-- CONTENT -->
			<section>
				<div class="container">
					<div class="page">
				
					<?php
						$paginacao = "";
						if(isset($_REQUEST["campo"])){
							echo("<script>window.location.href='../portal/interna.php?secao_id=" . $_SESSION["secao_id"] . "&campo=" . $_REQUEST["campo"] . "';</script>");
						} else {
							$palavra = "";
							$pagina_atual = "1";
							if(isset($_REQUEST["palavra"])) $palavra = $_REQUEST["palavra"];
							if(isset($_REQUEST["pagina_atual"])) $pagina_atual = $_REQUEST["pagina_atual"];

							if($palavra != "") {
								//$_SESSION["where"] = " and (Institucional_nome like '%" . $palavra . "%') and institucional.Institucional_status = 'Publicado' and ((INSTITUCIONAL_VAL_CONTEUDO = 0) or ((INSTITUCIONAL_VAL_CONTEUDO = 1) and ( Getdate() between Institucional_data_inicial and Institucional_data_expira))) ";

								echo str_replace('» ' . $dominio_site . ' » Home » ', '', Busca($palavra, $pagina_atual, "../busca/index.php","-1-"));
							}
						}
						?>	

						<?php
						echo $paginacao; 
					?>
					</div>
				</div>
			</section>
			


			<?php 
			require_once('../portal/incs/rodape.php'); 
			?>
		</div>
    </body>
</html>