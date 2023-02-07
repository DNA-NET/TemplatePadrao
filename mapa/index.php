<?php require_once('../Manager/conexao.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="../Manager/contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />
    <?php require_once('../portal/incs/Cabecalho.php'); ?>
</head>

<body class="smoothscroll">
    <div id="wrapper">
        <?php require_once('../portal/incs/Menu.php'); ?>

        <div id="corpo_pagina">

            <!-- PAGE TOP -->
            <section class="page-title">
                <div class="container">

                    <header>

						<ul class="breadcrumb">
                            <!-- breadcrumb -->
                            <li><a href="../home/">Home</a></li><li>Mapa do Site</li>                       
						</ul>

                        <h2>
                            Mapa do site
                        </h2>

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
						if($_SESSION["secao_descricao"] != '') echo '<p class="margin-bottom40">' . $_SESSION["secao_descricao"] . "</p>";
						$_SESSION["secao_descricao"] = '';
					}

					echo '<div class="row">';
					echo '<div class="col-md-3">' . Menu_interna_2(15) . Menu_interna_2(16) . Menu_interna_2(17). '</div>';
					echo '<div class="col-md-3">'  . Menu_interna_2(18) . Menu_interna_2(19) . Menu_interna_2(11) . '</div>';
					echo '<div class="col-md-3">'  . Menu_interna_2(21) . Menu_interna_2(22) . '</div>';
					echo '<div class="col-md-3">' . Menu_interna_2(13) . Menu_interna_2(355) . Menu_interna_2(14) .  '</div>';
					echo '</div>';
	
					?>

				</div>




            </section>



            <?php 
			require_once('../portal/incs/rodape.php'); 
			?>
        </div>
    </div>
</body>

</html>