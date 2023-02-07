<?php require_once('../Manager/conexao.php');?>
<!DOCTYPE html>

<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	
<html lang="pt-br"> <!--<![endif]-->

	<head>
		<?php require_once('../portal/incs/Cabecalho.php'); ?>
	</head>

	<body id="pagina_interna" class="smoothscroll">

		<div id="wrapper">
		<?php require_once('../portal/incs/Menu.php'); ?>

			<div class="container">
			<?php echo Conteudo('conteudo_home'); ?>
			</div>

		</div>

		<?php require_once('../portal/incs/rodape.php'); ?>	
	</body>

</html>