<?php include_once 'conexao.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <?php 		
		include_once 'Cabecalho.php'; ?>
    </head>
	<body class="smoothscroll">
		<div id="wrapper">
        <?php include_once 'MenuPrincipal.php'; ?>
        <?php
        $Dominio_id =       isset($_COOKIE["Dominio_id"])       ? $_COOKIE["Dominio_id"]        : "";
        $Dominio_id =       isset($_REQUEST["Dominio_id"])      ? $_REQUEST["Dominio_id"]       : "";
		$Dominioid =        isset($_REQUEST["dom"])				? $_REQUEST["dom"]				: "";
        $logon =            isset($_REQUEST["logon"])           ? $_REQUEST["logon"]            : "";
        $Password =         isset($_REQUEST["Password"])        ? $_REQUEST["Password"]         : "";
        $Name =             isset($_REQUEST["Name"])            ? $_REQUEST["Name"]             : "";
        $lembrar_senha =    isset($_REQUEST["lembrar_senha"])   ? $_REQUEST["lembrar_senha"]    : "";
        $returnJS = "";

		if(!isset($_SESSION["Mascara_busca"])); $_SESSION["Mascara_busca"] = "";


		// Atualização de versão de banco de dados
		$result = db_query($con,"show columns from funcionarios like 'Funcionarios_troca_senha'");
		$existes = (db_num_rows($result))?true:false;
		if(!$existes) { 
			db_query($con,"alter table funcionarios add Funcionarios_troca_senha varchar(3)");
		}

		$result = db_query($con,"show columns from mascara like 'secao_id_preferencial'");
		$existes = (db_num_rows($result))?true:false;
		if(!$existes) { 
			db_query($con,"alter table mascara add secao_id_preferencial int");
		}


        function ProcessaLogin($txtUser, $txtPassword) {
            global $con, $Dominio_Pasta;
            $return["sucess"] = FALSE;
            $return["msg"] = "";
            $return["redirectTo"] = "";
            $checkedUser = FALSE;
            $newLDAPUser = FALSE;
            $ramal = "";

			// Resgata os dados do usuário a partir da base do atualiza
			$strSQL = "select distinct
							FUNCIONARIOS_CONTA1, inibe_geral, inibe_tabela, inibe_corretor, inibe_impressao,
							inibe_imagem, inibe_format, inibe_command, email, Funcionarios_arquivo_tamanho,
							Funcionarios_arquivo_ext, Funcionarios_editor, Funcionarios_nome, Funcionarios_telefone, Funcionarios_funcao,
							funcionarios.senha, funcionarios.Funcionarios_id, Funcionarios_cpf, Funcionarios_troca_senha, Funcionarios_endereco, Funcionarios_cidade, Funcionarios_escolaridade, Funcionarios_status
						from
							funcionarios
						where
							( Funcionarios_cpf IS NOT NULL AND Funcionarios_cpf = '" . trim(strtolower(tira_aspas($txtUser))) . "') OR
							( email IS NOT NULL AND email = '" . trim(strtolower(tira_aspas($txtUser))) . "') OR
							( username IS NOT NULL AND LOWER(username) = '" . trim(strtolower(tira_aspas($txtUser))) . "')";


			// Encontrando a conta do usuário...
			$RSUsuario = db_query($con, $strSQL);
			if(db_num_rows($RSUsuario) > 0) {
				$row = db_fetch_assoc($RSUsuario);

				// Se a senha do usuário é válida...
				if ((trim(strtoupper($row["senha"])) == trim(strtoupper($txtPassword))) || (trim(strtoupper($row["senha"])) == trim(strtoupper(sha1($txtPassword))))) {
					if ($row["Funcionarios_status"] == 'Ativo') {
						$_SESSION["UserName"] = tira_aspas($txtUser);
						$_SESSION["Password"] = $txtPassword;
						$_SESSION["FUNCIONARIOS_CONTA1"] = $row["FUNCIONARIOS_CONTA1"];

						$_SESSION["Funcionarios_nome"] = $row["Funcionarios_nome"];
						$_SESSION["Funcionarios_cpf"] = $row["Funcionarios_cpf"];
						$_SESSION["Funcionarios_editor"] = $row["Funcionarios_editor"];
						$_SESSION["Funcionarios_id"] = $row["Funcionarios_id"];
						$_SESSION["Funcionarios_id2"] = $row["Funcionarios_id"];
						$_SESSION["Funcionarios_arquivo_ext"] = $row["Funcionarios_arquivo_ext"];
						$_SESSION["Funcionarios_arquivo_tamanho"] = $row["Funcionarios_arquivo_tamanho"];
						$_SESSION["email"] = $row["email"];
						$_SESSION["Funcionarios_telefone"] = $row["Funcionarios_telefone"];
						$_SESSION["Funcionarios_endereco"] = $row["Funcionarios_endereco"];
						$_SESSION["Funcionarios_cidade"] = $row["Funcionarios_cidade"];
						$_SESSION["Funcionarios_funcao"] = $row["Funcionarios_funcao"];
						$_SESSION["Funcionarios_troca_senha"] = $row["Funcionarios_troca_senha"];
						$_SESSION["Funcionarios_escolaridade"] = $row["Funcionarios_escolaridade"];

						$_SESSION["ramal"] = $ramal;

						$_SESSION["Perfil_id"] = "";
														
						$RSPerfil = db_query($con,"SELECT p.Perfil_id,  p.Superior_id, p.Perfil_nome, p.Perfil_funcao, p.Perfil_descricao
													FROM
														perfil p, funcionarios_perfil fp
													WHERE
														fp.Perfil_id = p.Perfil_id AND
														fp.Funcionarios_id=" . $_SESSION["Funcionarios_id"]) or die("Erro!: " . db_error());

						if (db_num_rows($RSPerfil) > 0) {
							$perfil_acumulado = "";
							while($RSResultado=db_fetch_array($RSPerfil)) {
								$_SESSION["Perfil_id"] = $RSResultado["Perfil_id"];
								$_SESSION["Superior_id"] = $RSResultado["Superior_id"];
								$_SESSION["perfil"] = $RSResultado["Perfil_nome"];
								$_SESSION["perfil_descricao"] = $RSResultado["Perfil_descricao"];
								$_SESSION["funcao"] = $RSResultado["Perfil_funcao"];

								$perfil_acumulado .= "-" . $RSResultado["Perfil_id"];
							}
							$_SESSION["perfil_acumulado"] = $perfil_acumulado;

							if ($perfil_acumulado != "") {
								$query = "(Perfil_id = 0 " . str_replace("-", " OR Perfil_id = ", $perfil_acumulado) . ")";
								$_SESSION["Secoes_permitidas"] = "";
								$RSSecao = db_query($con,"SELECT Secao_id FROM perfil_secao WHERE " . $query) or die("Erro!: " . db_error());

								if (db_num_rows($RSSecao) > 0) {
									while($RSResultado=db_fetch_array($RSSecao)) {
										//echo '<Br>';
										$_SESSION["Secoes_permitidas"] .= $RSResultado["Secao_id"] . ",";
									}
									$_SESSION["Secoes_permitidas"] .= "0";
								}
							}

							$perfil_selecionado = "";
							if(isset($_COOKIE["Perfil_id"])) $perfil_selecionado = "?Perfil_id=" . $_COOKIE["Perfil_id"];
							if(isset($_SESSION["Perfil_id"])) $perfil_selecionado = "?Perfil_id=" . $_SESSION["Perfil_id"];


							$return["sucess"] = TRUE;
							$return["redirectTo"] = "Default_user.php" . $perfil_selecionado;

						} else {
							$return["msg"] = "window.location.href='" . $Dominio_Pasta . "/Manager/Login.php?logout=s&mensagem=Falta um registro em funcionarios_perfil para o usuario ".$_SESSION["Funcionarios_id"]."';";
						}
					} else {
						$return["msg"] = "window.location.href='" . $Dominio_Pasta . "/Manager/Login.php?logout=s&mensagem=Seu cadasdtro ainda não foi ativado. Solicite ativação do cadastro ao Administrador ou Coordenador Esportivo';";
						//$return["msg"] = "Mostra_modal('<p class=info>Senha inválida!</p>');";
					}
				} else {
					$return["msg"] = "window.location.href='" . $Dominio_Pasta . "/Manager/Login.php?logout=s&mensagem=Senha Inválida';";
					//$return["msg"] = "Mostra_modal('<p class=info>Senha inválida!</p>');";
				}
			}
			else {
				$return["msg"] = "Mostra_modal('<p class=info>Usuário " . trim(strtolower(tira_aspas($txtUser))) . " não encontrado!</p>');";
			}
            return $return;
        }

        // Identifica a ação que deve ser executada
        $Action = ($logon == "sim" && $Dominio_id != "" && $Name != "") ? "logon" : "";
        $Action = ($Action == "" && $lembrar_senha == "sim") ? "lembrarsenha" : $Action;

        switch($Action) {

            // Executa o Login
            case "logon":
							
                // Identifica informações do domínio selecionado
                $RSDominio = db_query($con,"select * FROM dominio WHERE Dominio_id = " . $Dominio_id);
                if(db_num_rows($RSDominio) > 0) {									

                    $row = db_fetch_assoc($RSDominio);
                    $_SESSION["Dominio_nome"]					= $row["Dominio_nome"];
                    //$_SESSION["bd_conn"]					    = $row["Dominio_conexao"];
                    //$_SESSION["Dominio_banco_dados"]		    = $row["Dominio_banco_dados"];
                    $_SESSION["Dominio_id"]					    = $row["Dominio_id"];
                    $_SESSION["Dominio_css"]					= $row["dominio_css"];
                    $_SESSION["titulo"]						    = $row["Dominio_titulo"];
                    $_SESSION["Dominio_caminho_fisico"]		    = $row["Dominio_caminho_fisico"];
                    $_SESSION["Dominio_url_producao"]		    = $row["Dominio_url_producao"];
                    $_SESSION["Dominio_local_arquivos"]		    = $row["Dominio_local_arquivos"];
                    $_SESSION["Dominio_url_desenvolvimento"]	= $row["Dominio_url_desenvolvimento"];
                    $_SESSION["dominio_url_producao"]			= $row["Dominio_url_producao"];
                    $_SESSION["url"]							= $row["Dominio_url_producao"];

                    $_SESSION["Dominio_caminho_arquivos"]	    = $row["Dominio_caminho_arquivos"];					

//					if(isset($_SESSION["bd_conn"]) && isset($_SESSION["Dominio_banco_dados"])) {				

						// Devem haver exatos 4 valores definidos.
//						$conData = str_replace(" ", "", $_SESSION["bd_conn"]);
//						$conData = explode(",", $conData);

//						if(count($conData) === 4) {							
//							$useCon["db_host"] = $conData[0];
//							$useCon["db_username"] = $conData[1];
//							$useCon["db_password"] = $conData[2];
//							$useCon["db_basename"] = $conData[3];
//
//							$useCon["DataBase"] = strtolower($_SESSION["Dominio_banco_dados"]);
//						}

//						$con = db_get_connection();
//					}

                    //setcookie("Dominio_id", $Dominio_id);
                }

                // Tenta efetuar o login
                $resultLogin = ProcessaLogin($Name, $Password);
				//echo "resultLogin[sucess]:".$resultLogin["sucess"]."<Br>";
                if($resultLogin["sucess"]) {
				?>
					<script type="text/javascript">
					window.location="<?php echo $resultLogin["redirectTo"];?>";
					</script>
				<?php
                }
                else {
                    $returnJS = $resultLogin["msg"];
                }

                break;


            // Executa o Lembrar senha
            case "lembrarsenha" :
				
				// Identifica informações do domínio selecionado
                $RSDominio = db_query($con,"SELECT * FROM dominio WHERE Dominio_id = " . $Dominioid);
                if(db_num_rows($RSDominio) > 0) {									

                    $row = db_fetch_assoc($RSDominio);
                    $_SESSION["Dominio_nome"]					= $row["Dominio_nome"];
                    $_SESSION["bd_conn"]					    = $row["Dominio_conexao"];
                    $_SESSION["Dominio_banco_dados"]		    = $row["Dominio_banco_dados"];
                    $_SESSION["Dominio_id"]					    = $row["Dominio_id"];			

					if(isset($_SESSION["bd_conn"]) && isset($_SESSION["Dominio_banco_dados"])) {				

						// Devem haver exatos 4 valores definidos.
						$conData = str_replace(" ", "", $_SESSION["bd_conn"]);
						$conData = explode(",", $conData);

						if(count($conData) === 4) {							
							$useCon["db_host"] = $conData[0];
							$useCon["db_username"] = $conData[1];
							$useCon["db_password"] = $conData[2];
							$useCon["db_basename"] = $conData[3];

							$useCon["DataBase"] = strtolower($_SESSION["Dominio_banco_dados"]);
						}

						$con = db_get_connection();
					}

                    //setcookie("Dominio_id", $Dominio_id);
                }

                $RSUsuario = db_query($con, "SELECT DISTINCT
                                                email, username, Funcionarios_nome, Funcionarios_telefone,
                                                funcionarios.senha, funcionarios.Funcionarios_id, Funcionarios_cpf
                                                FROM
                                                funcionarios
                                                WHERE
                                                (Funcionarios_cpf IS NOT NULL AND Funcionarios_cpf = '" . tira_aspas($Name) . "') OR
                                                ( email IS NOT NULL AND email = '" . tira_aspas($Name) . "') OR
                                                ( username IS NOT NULL AND username = '" . tira_aspas($Name) . "')");


	            if(db_num_rows($RSUsuario) > 0){
		            $row = db_fetch_assoc($RSUsuario);
		            $Body = "<h1>Envio de senha para acesso ao sistema - " .  $_SESSION["Dominio_nome"] . "</h1>
                             <p>Username: " . $row["username"] . "</p>
                             <p>Senha: " . $row["senha"];


		            Envia_email($row["email"], $row["email"], "", "", "Aviso de senha para acesso ao sistema - " .  $_SESSION["Dominio_nome"], 1, 1, $Body, "");
					unset($_SESSION["bd_conn"]);					
                    $returnJS = "window.location='" . $Dominio_Pasta . "/Manager/Login.php?mensagem=Senha enviada para seu e-mail'";
	            } else {
					unset($_SESSION["bd_conn"]);					
                    $returnJS = "window.location='" . $Dominio_Pasta . "/Manager/Login.php?mensagem=Usuário não encontrado!'";
				}
                break;
        }




        // Retorna os Domínios onde o usuário pode tentar efetuar Login
		$Sql1 = "SELECT Dominio_id, Dominio_nome FROM dominio ORDER BY Dominio_nome";
		$servidor = $_SERVER["SERVER_NAME"];
		if(strpos($servidor, "www") !== false) {
			$Sql1 = "SELECT Dominio_id, Dominio_nome FROM dominio where Dominio_url_producao like '%" . $servidor . "%'";
		}

        $RSDominio = db_query($con,$Sql1);
        $Dominio_id_options = "";
        while($RSDominioResultado=db_fetch_array($RSDominio)) {
            $selected = ($Dominio_id == $RSDominioResultado["Dominio_id"])  ? " selected " : "";
            $D_Id = $RSDominioResultado["Dominio_id"];
            $D_Nome = $RSDominioResultado["Dominio_nome"];

            $Dominio_id_options .= "<option value=\"" . $D_Id . "\" " . $selected . ">" . $D_Nome . "</option>";
        }

        ?>



        <script type="text/javascript">
            function LembrarSenha() {
                try {
                    var _user = document.login.Name.value;
					var _dominio = document.login.Dominio_id.value;
                    if (_user == "") {
                        Mostra_modal('<p class="info">Informe seu Username, e-mail ou CPF para que seja enviado o lembrete de senha!</p>');
                        document.login.Name.focus();
                        return false;
                    }
                    else {
                        window.location.href = '<?php echo $Dominio_Pasta ?>/Manager/Login.php?Name=' + _user + '&lembrar_senha=sim' + '&dom=' + _dominio;
                    }
                }
                catch (e) {
                }
            };


            function funConsistenciaComum(sErro) {

                if (document.login.Name.value == "") {
                    sErro += "Você precisa informar o usuário.<br>";
                }
                if (document.login.Password.value == "") {
                    sErro += "Você precisa informar a Senha.<br>";
                }
                if (document.login.Dominio_id.value == "0") {
                    sErro += "Você precisa selecionar o Dominio.";
                }


                return (sErro);
            };


            function funVerificaDados() {

                sErro = "";
                sErro = funConsistenciaComum(sErro);

                if (sErro != "") {
                    Mostra_modal('<p class="info">' + sErro + '</p>');
                    return false;
                }
                else {
                    return true;
                }
            };
        </script>


		<div class="parallax parallax-1" style="background-image: url('<?php echo $Dominio_Pasta ?>/assets/images/hearder-atualiza.jpg'); padding: 40px 0;">
			<span class="parallax-overlay"></span>

			<div class="container parallax-content">

				<div class="row">
					<div class="col-md-7 col-sm-7">
					
						<h1 style="margin: 0;"><strong>Atualiza DXP - <?php echo date("Y");?></strong><br>Plataforma de Experiência<br>Digital</h1>

					</div>


					<div class="col-md-5 col-sm-5 text-right">

						<div class="hidden-xs">
							<h4 class="nomargin">Suporte DNA<span style="font-style: italic;">net</span></h4>
							<h2><strong>(51) 3231-7002</strong></h2>
							<h4 class="nomargin"><a href="http://www.dna.com.br" target="_blank">www.dna.com.br</a></h4>
						</div>

					</div>
				</div>

			</div>

		</div>

		<section>
			<div class="container">

				<div class="row">
					<div class="col-md-6 col-sm-6">

						<div id="article">

							<form method="post" action="Login.php" name="login" class="sky-form boxed" onsubmit="return funVerificaDados();">
								<input name="acao" type="hidden" value="logon">
								<input type="hidden" name="logon" value="sim">
								<input name="Dominio_id" type="hidden" value="1">

								<header>Login de usuário</header>

								<fieldset>					
									<section>
										<div class="row">
											<div class="col-md-12">
												<label class="label"> Usuário: </label>
												<label class="input">
													<i class="icon-append fa fa-user"></i>
													<input type="text" name="Name" value="<?php echo $Name ?>" placeholder="username, e-mail ou CPF">
												</label>
											</div>
										</div>
									</section>

									<section style="margin-bottom: 0px;">
										<div class="row">
											<div class="col-md-12">
												<label class="label">Senha:</label>
												<label class="input">
													<i class="icon-append fa fa-lock"></i>
													<input type="password" name="Password" placeholder="insira a sua senha">
												</label>
											</div>
										</div>
									</section>

									<section style="margin-bottom: 0px;">
										<div class="row">
											<div class="col-md-12">									
												<div class="note"><a href="#" onclick="LembrarSenha();">Lembrar senha</a></div>
											</div>
										</div>
									</section>	
								</fieldset>
								<footer>
									<button type="submit" class="button"style="border-radius: 3px;">Entrar</button>
								</footer>
							</form>

						</div>
					</div>
					<div class="col-md-6 col-sm-6">

						<H3 style="margin-top:20px;">Precisa de Ajuda?</H3>

						<p>
							No campo <b>Usuário</b> pode ser utilizado seu username, e-mail ou CPF.
						</p>
						<p>
							<b>Esqueceu a senha?</b> Entre com o Username e clique em <b>Lembrar senha</b>. Você receberá por e-mail as instruções para recuperação da senha.
						</p>
					</div>
				</div>

			</div>
		</section>



        <?php include_once 'rodape.php'; ?>




        <?php
            if($returnJS != "") {
                echo "<script>" . $returnJS . "</script>";
            }
        ?>
        </div>
    </body>
</html>
