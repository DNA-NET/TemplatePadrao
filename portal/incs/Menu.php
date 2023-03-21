<div vw class="enabled hidden-xs hidden-sm">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>

<script>
	function auto_contraste()
	{
		if(document.getElementById('pagina_interna').className == 'contrast-page') 
		{ 
			document.getElementById('pagina_interna').className = '';
		}
		else
		{ 
			document.getElementById('pagina_interna').className = 'contrast-page';
		}
	}	
</script>

<style type="text/css">
	.contrast-page, .contrast-page * {
		background-color: #000!important;
		color: #fff!important;
		border-color: #fff!important;
	}	
</style>

<?php
if(!isset($_SESSION["secao_id"])) $_SESSION["secao_id"] = "0";
?>

		<a href="#header" accesskey="1"></a>
		<a href="#corpo_pagina" accesskey="2"></a>
		<a href="#footer" accesskey="3"></a>

		<div id="header">
			<!-- class="sticky" for sticky menu -->
			<!-- Top Bar -->
			<header id="topBar">
				<div class="container">
					<div class="row">
						<div class="col-md-12 header">
							<div class="col-md-6">
								<a class="logo" href="<?php echo $Dominio_Pasta_menu; ?>" accesskey="0">
									<img src="<?php echo ($Dominio_Pasta_menu . $Imagem_logo); ?>" alt="" style="max-height: 100px;" />
								</a>
							</div>
							<div class="col-md-6">
								<div class="col-md-12" style="padding:0;">
									<div class="row">
										<div class="col-md-7">
											<ul class="menu-superior">
												<li  class="item-menu-superior">
													<a href="<?php echo $Dominio_Pasta_menu; ?>/contato" target="_blank"><b>Contato</b></a>
												</li>
												<li class="item-menu-superior">
													<a href="<?php echo $Dominio_Pasta_menu; ?>/mapa" aria-label="mapa" title="mapa" alt="mapa">Mapa do Site</a>
												</li>
												<li class="item-menu-superior">
													<a href="<?php echo $Dominio_Pasta_menu; ?>/acessibilidade" title="Acessibilidade" alt="Acessibilidade"><i class="fa fa-wheelchair" style="font-weight: bold;"></i></a>
												</li>
												<li class="item-menu-superior">
													<a href="#" id="auto-contraste" onclick="auto_contraste();" title="Auto-contraste" alt="Auto-contraste"><i class="icon ion-contrast" style="font-weight: bold;"></i></a>
												</li>
											</ul>
										</div>
										<div class="col-md-5" style="padding:0;">	
											<!-- Search -->
											<form class="search" method="get" action="<?php echo $Dominio_Pasta_menu; ?>/busca/">
												<input type="text" class="form-control" name="palavra" value="" placeholder="O que você procura?">
												<button class="fa fa-search btn-busca"></button>
											</form>
											<!-- /Search -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>

			<div class="container">
				<!-- Top Nav -->
				<header id="topNav" class="menu-principal-dpmt">
					<div class="container main-menu">
						<!-- Mobile Menu Button -->
						<button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
							<i class="fa fa-bars"></i>
						</button>
						<!-- Top Nav -->
						<div class="navbar-collapse nav-main-collapse collapse" style="width:100%;">
							<nav class="nav-main" style="border-right: #eee 1px solid;">
								<ul id="topMain" class="nav nav-pills nav-main borda-menu">
<!-- 
									<li class="dropdown mega-menu">
										<a class="dropdown-toggle item-main-menu" href="#"><b>Mega menu</b></a>
										<ul class="dropdown-menu">
											<li class="column">
												<div class="row">
													<div class="col-md-6">
														<h3>Mega menu</h3>
														<ul>
														<?php //echo menu(0, '<li><a class="item-menu" href="secao_link"><i class="fa fa-angle-right"></i> secao_nome</a></li>','','','',''); ?>
														</ul>
													</div>
													<div class="col-md-6">
														<h3>Mega menu</h3>
														<ul>
														<?php //echo menu(0, '<li><a class="item-menu" href="secao_link"><i class="fa fa-angle-right"></i> secao_nome</a></li>','','','',''); ?>
														</ul>
													</div>
												</div>
											</li>
										</ul>
									</li>
 -->
									<?php 
									$sql = "SELECT secao_id, secao_nome, secao_link  FROM secao where secao_dna = '-1--9-' order by secao_ordem";
									$query = mysqli_query($con, $sql);
									while($result = mysqli_fetch_array($query))
									{
										echo '<li class="dropdown">';
										echo '<a class="dropdown-toggle item-main-menu" href="' . $result["secao_link"] . '"><b>' . $result["secao_nome"] . '</b> <!--<span>recent news</span>--></a>';
										echo '<ul class="dropdown-menu">';
										echo menu($result["secao_id"], '<li><a class="item-menu" href="secao_link"><i class="fa fa-angle-right"></i> secao_nome</a></li>','','','','');
										echo '</ul>';
										echo '</li>';

									}	
									?>
								</ul>
							</nav>
						</div>
						<!-- /Top Nav -->
					</div><!-- /.container -->
				</header>
				<!-- /Top Nav -->
			</div>
		</div>

<?php


?>