<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
inicio();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<link href="../Manager/contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />
	<?php require_once('../portal/incs/Cabecalho.php'); ?>
</head>

<body id="pagina_interna" class="smoothscroll">

	<div id="wrapper">
		<?php require_once('../portal/incs/Menu.php'); ?>

		<div id="corpo_pagina">

			<!-- PAGE TOP -->
			<section class="page-title">
				<div class="container">

					<header>

						<ul class="breadcrumb">
							<!-- breadcrumb -->
							<?php if (isset($_SESSION["navegacao"])) echo $_SESSION["navegacao"]; ?>
						</ul><!-- /breadcrumb -->

						<h2>
							<!-- Page Title -->
							<?php if (isset($_SESSION["secao_nome"])) echo "" . $_SESSION["secao_nome"]; ?>
						</h2><!-- /Page Title -->

					</header>

				</div>
			</section>
			<!-- /PAGE TOP -->

			<?php
			echo Conteudo("banner_secao");
			?>


			<section>
				<div class="container">

					<!--<form  method="post" class="sky-form">-->

					<?php
					if (isset($_SESSION["secao_descricao"])) {
						if ($_SESSION["secao_descricao"] != '') echo '<p class="margin-bottom40">' . $_SESSION["secao_descricao"] . "</p>";
						$_SESSION["secao_descricao"] = '';
					}

					//echo $_SESSION["secao_link"];
					//if(isset($_SESSION["secao_descricao"])) echo "<h4 style='margin-bottom:30px;'>" . $_SESSION["secao_descricao"] . "</h4>";


					$secao_menu_id = "";
					if (isset($_SESSION["secao_dna"])) {

						//========================================================================================================
						// Exibe detalhe de um conteúdo específico
						//========================================================================================================

						// Seções que tem o menu da esquerda
						$secao_menu_id = Busca_secao_menu();
						if ($secao_menu_id !== "") echo '<div class="row"><div class="col-md-3">' . Menu_interna_2($secao_menu_id) . '</div><div id="blog" class="col-md-9">';
					}

					//Prepara apontamento para conteúdo no caso de PREVIEW
					if (isset($_REQUEST["preview"])) {
						$_SESSION["campo"] = $_REQUEST["id"];
						Busca_secao($_REQUEST["secao_id"], '');
					}

					if ($_SESSION["campo"] != "" && $_SESSION["campo"] != "0") {
						$template_interna = "interna_detalhe";
						if ($_SESSION["secao_link"] != "") {
							if ($_SESSION["secao_link"] == 'lista_noticias') $template_interna = "interna_detalhe_noticias";
							if ($_SESSION["secao_link"] == 'lista_menu_eventos') $template_interna = "interna_detalhe_evento";
							if ($_SESSION["secao_link"] == 'interna_normal') $template_interna = "interna_detalhe_normal";
						}

						if ($_SESSION["secao_link"] == 'lista_noticias' || $_SESSION["secao_link"] == 'lista_unidades') {
							$conteudo_pagina = str_replace('column full', '', Conteudo($template_interna));
						} else {
							$conteudo_pagina = Conteudo($template_interna);
						}

						echo $conteudo_pagina;




						//========================================================================================================
						// Exibe listas de conteúdos ou templates específicos
						//========================================================================================================

					} else {
						$conteudo_pagina = "";
						if (isset($_SESSION["secao_link"])) {
							if ($_SESSION["secao_link"] != "") {
								if ($_SESSION["secao_link"] == 'lista_noticias') require_once($_SERVER['DOCUMENT_ROOT'] . '/hc/portal/incs/busca_noticias.php');
								if ($_SESSION["secao_link"] == 'lista_nome_link') require_once($_SERVER['DOCUMENT_ROOT'] . '/hc/portal/incs/busca_documentos.php');
								if ($_SESSION["secao_link"] == 'lista_membros') require_once($_SERVER['DOCUMENT_ROOT'] . '/hc/portal/incs/busca_membros.php');
								if ($_SESSION["secao_link"] == 'lista_licitacoes') require_once($_SERVER['DOCUMENT_ROOT'] . '/hc/portal/incs/busca_licitacoes.php');
								if ($_SESSION["secao_link"] == 'lista_documentos' || $_SESSION["secao_link"] == 'lista_avisos') require_once($_SERVER['DOCUMENT_ROOT'] . '/hc/portal/incs/busca_documentos.php');
								if ($_SESSION["secao_link"] == 'interna_menu_extranet') require_once($_SERVER['DOCUMENT_ROOT'] . '/hc/portal/incs/agenda_artista.php');

								$conteudo_pagina = Conteudo($_SESSION["secao_link"]);
								if (isset($_SESSION["numero_registros_" . $_SESSION["secao_link"]])) $conteudo_pagina .= '<div class="margin-top30">' . paginacao($_SESSION["secao_link"]) . '</div>';

								if ($_SESSION["secao_link"] != 'lista_noticias') {
									// Carrega o conteudo específico para ESPAÇOS
									if (isset($_SESSION["secao_dna"]) && (strpos($_SESSION["secao_dna"], '-14-') !== false || strpos($_SESSION["secao_dna"], '-13-') !== false)) {
										$conteudo_pagina = Conteudo('template_espacos') . $conteudo_pagina;
									}

									// Carrega o conteudo específico para NOTICIAS
									if (isset($_SESSION["secao_dna"]) && $_SESSION["secao_id"] == "75") {
										$conteudo_pagina = Conteudo('template_noticias') . $conteudo_pagina;
									}
								}

								echo $conteudo_pagina;
							} else {
								echo Conteudo("interna");
							}
						} else {

							echo "Página não encontrada!";
						}

						if (isset($_SESSION["secao_dna"])) {
							if ($secao_menu_id != "") echo  '</div></div>';
						}
					}


					// Carrega NOTICIAS relacionadas
					$noticias_relacionadas = Conteudo("lista_noticias_relacionadas");
					if ($noticias_relacionadas != '<!-- -->') {
					?>
						<div class="margem_texto margin-top40">
							<h4>Notícias <strong>Relacionadas</strong> <a href="/hc/institucional/noticias" style="margin-left:10px;"><i class="fa fa-plus-circle" style="color: #D3D92B;"></i></a></h4>
							<div class="owl-carousel" data-plugin-options='{"singleItem": false, "singleItem": false, "items":3, "itemsDesktop":[1199,3], "itemsDesktopSmall":[980,2], "autoPlay": true}'><!-- transitionStyle: fade, backSlide, goDown, fadeUp,  -->
								<?php echo $noticias_relacionadas; ?>
							</div>
						</div>
					<?php
					}
					?>

				</div>




			</section>


			<?php


			?>


		</div>
	</div>

	<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . getenv("ATUALIZA_MANAGER_MAIN_DIR") . "/Manager/salva_conteudo.php");
	require_once('../portal/incs/rodape.php');
	?>
</body>

</html>