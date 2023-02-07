<style type="text/css">
	#footer input.form-control {
		-webkit-border-top-left-radius: 0px !important;
		-webkit-border-bottom-left-radius: 0px !important;
		-moz-border-radius-topleft: 6px;
		-moz-border-radius-bottomleft: 6px;
		border-top-left-radius: 0px !important;
		border-bottom-left-radius: 0px !important;
	}

	#footer .input-group-btn .btn {
		-webkit-border-top-right-radius: 0px !important;
		-webkit-border-bottom-right-radius: 0px !important;
		-moz-border-radius-topright: 6px;
		-moz-border-radius-bottomright: 6px;
		border-top-right-radius: 0px !important;
		border-bottom-right-radius: 0px !important;
	}

	#footer li a {
		color: #999;
	}

	#footer {
		border-top: #252525 2px solid;
	}
</style>

<!-- modal dialog -->


<a href="#" id="modal_rodape" data-toggle="modal" data-target="#myModal_rodape"></a>

<div class="modal fade" id="myModal_rodape" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header"><!-- modal header -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Atenção!</h4>
			</div><!-- /modal header -->

			<!-- modal body -->
			<div class="modal-body" id="modal-body">
				<span id="rodape_mensagem"></span>
			</div>
			<!-- /modal body -->

			<div class="modal-footer"><!-- modal footer -->
				<button class="btn btn-default" data-dismiss="modal">Fechar</button> <!-- <button class="btn btn-primary">Save changes</button> -->
			</div><!-- /modal footer -->

		</div>
	</div>
</div>

<style type="text/css">
	.fonte_rodape {
		color: #ddd;
		font-size: 15px;
	}
</style>

<div id="footer" style="background-image: linear-gradient(to left,  #a0a0a0, #313131);"> <!-- #D4E8C5 -->

	<div class="container margin-top10">
		<div class="row margin-top20" style="padding-bottom:0px; font-size: 13px;">

		</div>
	</div>

	<hr />

	<div class="copyright">
		<div class="container text-center fsize12">
			© <?php echo $Dominio_Nome; ?> | Todos os direitos reservados | <a href="http://www.dna.com.br" target="_blank">DNA Tecnologia LTDA</a>
		</div>
	</div>

	<a href="#" id="toTop"></a>

	<?php
	require_once('scripts.php');

	if (isset($_REQUEST["mensagem"]) and (!isset($_SESSION["mensagem"]) or trim($_SESSION["mensagem"]) == '')) $_SESSION["mensagem"] = $_REQUEST["mensagem"];

	if (isset($_SESSION["mensagem"])) {
		if ($_SESSION["mensagem"] != '') {
	?>
			<script type="text/javascript">
				function Mensagem_modal(mensagem) {

					$(document).ready(function() {
						document.getElementById('modal-body').innerHTML = mensagem;
						document.getElementById('modal_rodape').click();
					});
				}

				Mensagem_modal('<?php echo $_SESSION["mensagem"]; ?>');
			</script>
	<?php

			$_SESSION["mensagem"] = '';
		}
	}

	require_once('politica_cookies.php');
	Registra_Visita(session_id());
	?>
	<script>
		function registraVisita() {
			const appname = window.location.pathname.substr(1).split("/")[0];
			const xhr = new XMLHttpRequest();

			xhr.onload = function(e) {}
			xhr.open("POST", "/" + appname + '/portal/registraVisita.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send(JSON.stringify({
				url: window.location.pathname,
				displayInfo: {
					sizeScreenWidth: window.screen.availWidth,
					sizeScreenHeight: window.screen.availHeight,
					sizeWindowWidth: window.innerWidth,
					sizeWindowHeight: window.innerHeight,
					colorDepth: screen.colorDepth,
					pixelDepth: screen.pixelDepth
				}
			}));
		};
		registraVisita();
	</script>