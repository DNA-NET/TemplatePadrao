<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php"; ?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php require_once('../portal/incs/Cabecalho.php'); ?>
    </head>
	<body class="smoothscroll">
		<div id="wrapper">
			<?php 
			require_once('../portal/incs/Menu.php');

			$palavra = substr(md5( time() ) ,0,9);
			
			?>	
			
			<script>
				function Mascara(o,f){
					v_obj=o
					v_fun=f
					setTimeout("execmascara()",1)
				}
				
				/*FunÃ§Ã£o que Executa os objetos*/
				function execmascara(){
					v_obj.value=v_fun(v_obj.value)
				}
				
				/*FunÃ§Ã£o que padroniza telefone (11) 4184-1241*/
				function Telefone(v){
					v=v.replace(/\D/g,"")                 
					v=v.replace(/^(\d\d)(\d)/g,"($1) $2") 
					v=v.replace(/(\d{4})(\d)/,"$1-$2")    
					return v
				}
					

				function verifica(){				   
					var erro = "";					
					//if(document.contactForm.captcha.value == "") erro += "Entre com o cÃ³digo mostrado na imagem.<br>"; 
					//if(document.contactForm.captcha.value != document.contactForm.session_palavra.value) erro += "CÃ³digo errado! Digite as letras e os nÃºmeros conforme mostrados na imagem.";
					if(erro != '') {
						jQuery.fancybox('<span style=\"color: #006848;\"><b>Atenção:</b></span><br>' + erro);						
						return false;
					} else {
						return true;
					}
				}					
			
			
			
			function myAlert(){
				swal({
			  title: "Enviada!",
			  text: "Agradecemos a sua mensagem!",
			  icon: "success",
			});
			}
</script>

			<!-- PAGE TOP -->
			<section class="page-title">
				<div class="container">

					<header>

						<ul class="breadcrumb"><!-- breadcrumb -->
							<a href="../portal">Home</a> > Fale conosco
						</ul><!-- /breadcrumb -->

						<h2><!-- Page Title -->
							<strong>Fale conosco</strong>
						</h2><!-- /Page Title -->

					</header>

				</div>			
			</section>
			<!-- /PAGE TOP -->



			<!-- CONTENT -->
			<section>
				<div class="container">
				<?php
				// Aqui vai o miolo da pÃ¡gina
				$nome = "";
				$email = "";
				$assunto = "";
				$mensagem = "";
				$setor = "";

				if(isset($_REQUEST["nome"])) $nome = $_REQUEST["nome"];
				if(isset($_REQUEST["email"])) $email = $_REQUEST["email"];
				if(isset($_REQUEST["assunto"])) $assunto = $_REQUEST["assunto"];
				if(isset($_REQUEST["mensagem_mail"])) $mensagem = $_REQUEST["mensagem_mail"];

				$setor = "";
				if(isset($_REQUEST["setor"])) $setor = $_REQUEST["setor"];
				?>


					<div class="row">

						<!-- FORM -->
						<div class="col-md-8">
									
							<form id="formFale" name="formFale" method="post" onsubmit="return verifica();">
								<!-- <h3>Deixe sua mensagem para o HC preenchendo o formulário abaixo</h3> -->
								<input type="hidden" name="session_palavra" id="session_palavra" value="<?php echo $palavra; ?>" />
								<div class="row">
									<div class="form-group">
										<div class="col-md-6">
											<label>Nome *</label>
											<input required type="text" value="<?php echo $nome ?>" data-msg-required="Entre com seu nonme." maxlength="100" class="form-control" name="nome" id="contact_name">
										</div>
										<div class="col-md-6">
											<label>E-mail *</label>
											<input required type="email" value="<?php echo $email ?>" data-msg-required="Por favor, entre com seu endereÃ§o de e-mail" data-msg-email="Entre com um e-mail vÃ¡lido." maxlength="100" class="form-control" name="email" id="contact_email">
										</div>
									</div>
								</div>
<!-- 
								<div class="row">
									<div class="form-group">
										<div class="col-md-6">
											<label>Assunto *</label>
											<input required type="text" value="<?php echo $assunto ?>" data-msg-required="Entre com o assunto." class="form-control" name="assunto" id="contact_subject" maxlength="14">
										</div>
									
										<div class="col-md-6">
											<label>Setor*</label><br>
											<select required class="select_form" name="setor" style="border-radius: 8px; margin-top: 5px; height: 40px; padding: 5px; width: 100%;">
												<option value=""> Enviar para o setor ...</option>
												<option value="sistema@hc.sc.gov.br">Geral</option>
												<?php
												$RS = mysqli_query($con,"select  Email_nome, Email_email from email where Email_Area = 'Fale Conosco'");
												while($RSEmail=mysqli_fetch_array($RS)) {
													echo '<option value="' . $RSEmail["Email_email"] . '"';
													if($setor == $RSEmail["Email_email"]) echo " selected ";
													echo '>' . $RSEmail["Email_nome"] . '</option>';
												}											
												?>
											</select>
										</div>
									</div>
								</div> 
 -->
								<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<label>Mensagem *</label>
											<textarea required maxlength="5000" style="border-radius: 8px;" data-msg-required="Escreva sua mensagem." rows="6" class="form-control" name="mensagem_mail" id="contact_comment"><?php echo $mensagem ?></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-md-6">
											<div class="g-recaptcha" data-sitekey="6Lf1Cf0UAAAAALjmsuqplO1bbJ7Z1UxoBBp7Hj_8"></div>
										</div>
										<div class="col-md-6 text-right">										
											<input type="submit" value="Enviar mensagem" class="btn btn-primary btn-lg" id="contact_submit" />
										</div>
									</div>
								</div>
							</form>
													
						</div>
						
						<div class="col-md-4">
						<br><h4><strong>Nome da Empresa</strong></h4>


						<p class="margin-top20">Endereço
						Rua XXXXXXXXXXXXXXXX<br>
						Cidade - UF<br>
						CEP 99999-999</p>


						</div>
						
						
						<!-- /FORM -->


					</div>



				</div>
			</section>
			

		<?php
			if(isset($_REQUEST["email"])) {
				if($_REQUEST["email"] != '') {
					
					$Captcha = $_POST["g-recaptcha-response"];

					if($Captcha != "") {

							$Corpo_email = "<font face='Arial'><b>Fale Conosco - hc</b><br><br>";
							$Corpo_email .= "Nome: " . $_REQUEST["nome"] .  "<br>";
							$Corpo_email .= "E-mail: " . $_REQUEST["email"] . "<br>";
							//$Corpo_email .= "Assunto: " . $_REQUEST["assunto"] . "<br>";
							$Corpo_email .= "Mensagem: " . $_REQUEST["mensagem_mail"] . "</font>";
						
							$setor = "projetos@hc.br";
							//if(isset($_REQUEST["setor"])) $setor = $_REQUEST["setor"];

							$RS = mysqli_query($con,"select Email_nome, Email_email from email where Email_Area = 'Contato'");
							$RSEmail = mysqli_fetch_array($RS);
							$setor = $RSEmail['Email_email'];
							//$Corpo_email = utf8_encode($Corpo_email);

							Envia_email($_REQUEST["email"], $setor, "", "", "Fale Conosco - hc", 1, 1, $Corpo_email, "", $_REQUEST["email"]);

							$_SESION["mensagem"] = "Mensagem enviada com sucesso";


					} else {
						$_SESSION["mensagem"] = "Captha não selecionado, marque que voê não é um robo!";
					}					
				}
			}
			?>


			<?php 
			require_once('../portal/incs/rodape.php'); 
			?>
		</div>
    </body>
</html>