<?php
//$servidor_email = "192.168.1.206";

function Formata_nome($tabela,$campo,$clausula) {
	global $con;
	$resultado = "";
	$RS_Campo = db_query($con,"SELECT " . $campo . " FROM " . $tabela . " WHERE " . $clausula);
	
    if(db_num_rows($RS_Campo)) {
		$row = db_fetch_assoc($RS_Campo);
		$resultado = $row[$campo];
	}

	return $resultado;
}



//===================================================================================
// Verifica se já existe usuário cadastrado com mesmo CPF, username ou e-mail
//===================================================================================

function User_existente($cpf, $username, $email) {

	global $con;
	$resultado = "";

	$strSQL = "select Funcionarios_cpf, email, username
				from
					funcionarios
				where
					( Funcionarios_cpf IS NOT NULL AND Funcionarios_cpf = '" . tira_pontos_tracos_cpf(trim(strtolower(tira_aspas($cpf)))) . "') OR
					( email IS NOT NULL AND email = '" . trim(strtolower(tira_aspas($email))) . "') OR
					( username IS NOT NULL AND LOWER(username) = '" . trim(strtolower(tira_aspas($username))) . "' AND username <> 'palestrante')";


	// Encontrando a conta do usuário...
	$RSUsuario = db_query($con, $strSQL);
	if(db_num_rows($RSUsuario) > 0) {
		$row = db_fetch_assoc($RSUsuario);
		$resultado = "Usuário já cadastrado com mesmo <b>";
		if($row["Funcionarios_cpf"] == tira_pontos_tracos_cpf(trim(strtolower(tira_aspas($cpf))))) $resultado .="cpf, ";
		if($row["email"] == trim(strtolower(tira_aspas($email)))) $resultado .="email, ";
		if($row["username"] == trim(strtolower(tira_aspas($username)))) $resultado .="username, ";
		$resultado .= "</b> verifique ou localize o usuário cadastrado para alteração.";
	} 

	return $resultado;

}

//===================================================================================
// Verifica permissão de acesso ao aplicativo
//===================================================================================

function Permissao_Aplicativo() {
	global $con, $Dominio_Pasta;
	$resultado = "";
	$_SESSION["Permissao_Acesso_inclui"] = "";
	$_SESSION["Permissao_Acesso_altera"] = "";
	$_SESSION["Permissao_Acesso_exclui"] = "";
	$_SESSION["Permissao_Acesso_auditoria"] = "";

	$RSApp = db_query($con,"SELECT 
								permissao_tabela.permissao_tabela_id, permissao_tabela_area, permissao_tabela_link, permissao_tabela_Nome, permissao_tabela_descricao, permissao_acesso_inclui, permissao_acesso_altera, permissao_acesso_exclui, permissao_acesso_auditoria 
							FROM 
								permissao_tabela, permissao_acesso 
							WHERE 
								permissao_tabela.permissao_tabela_id = permissao_acesso.permissao_tabela_id and
								permissao_tabela_link = '" . str_replace($Dominio_Pasta, '', $_SERVER['PHP_SELF']) . "' and Perfil_id=" . $_SESSION["Perfil_id"]);

	If(db_num_rows($RSApp) > 0) {
		while($RS3=db_fetch_array($RSApp)) {

			$resultado = "<section class=\"page-title\">";
			$resultado .= "<div class=\"container\">";
			$resultado .= "<header>";
			$resultado .= "<ul class=\"breadcrumb\" style=\"float: right;\">";
			$resultado .= $RS3["permissao_tabela_area"] . ' > <a href="' . $RS3["permissao_tabela_link"] . '">' . $RS3["permissao_tabela_Nome"] . '</a>';
			$resultado .= "</ul>";
			$resultado .= "<h2>";
			$resultado .= $RS3["permissao_tabela_descricao"];
			$resultado .= "</h2>";
			$resultado .= "</header>";
			$resultado .= "</div>";
			$resultado .= "</section>";

			if($RS3["permissao_acesso_inclui"] == 'Sim') $_SESSION["Permissao_Acesso_inclui"] = $RS3["permissao_acesso_inclui"];
			if($RS3["permissao_acesso_altera"] == 'Sim') $_SESSION["Permissao_Acesso_altera"] = $RS3["permissao_acesso_altera"];
			if($RS3["permissao_acesso_exclui"] == 'Sim') $_SESSION["Permissao_Acesso_exclui"] = $RS3["permissao_acesso_exclui"];
			if($RS3["permissao_acesso_auditoria"] == 'Sim') $_SESSION["Permissao_Acesso_auditoria"] = $RS3["permissao_acesso_auditoria"];
		
		}

		if($_SESSION["Permissao_Acesso_auditoria"] != 'Sim' && $_SESSION["Perfil_id"] != "1") {
			$_SESSION["mensagem"] = "Você não tem acesso a este aplicativo!";
		}

	} else {
			$_SESSION["mensagem"] = "Aplicativo não cadastrado!";
	}

	return $resultado;
}


function Seq($tabela_nome) {

	global $con;
	$RSTestaSeq=db_query($con,"SELECT Seq_Tabela_id FROM seq WHERE Seq_Tabela='" . $tabela_nome . "'");
	  if(db_num_rows($RSTestaSeq) == 0) {
			db_query($con,"INSERT INTO seq (Seq_Tabela_id, Seq_Tabela) VALUES (1,'" . $tabela_nome . "')");
			$Seq_Tabela_id = 1;
	  }else{
	
  	   while($RSTesteResultado=db_fetch_array($RSTestaSeq)) {
	   $Seq_Tabela_id = $RSTesteResultado["Seq_Tabela_id"] + 1;
	   }
       db_query($con,"UPDATE seq SET Seq_Tabela_id=" . $Seq_Tabela_id . " WHERE Seq_Tabela ='" . $tabela_nome . "'");
	}	
	$Seq = $Seq_Tabela_id;
	
	return $Seq;
}



function left($str, $length) {
     return substr($str, 0, $length);
}


function right($str, $length) {
     return substr($str, -$length);
}


function tira_aspas($s) {
	return str_replace(Chr(39), "&#039;", str_replace(Chr(34), "&quot;", $s));
}


function tira_espaco($s) {
	return str_replace(" ", "%20",$s);
}


function encodeHTML($sHTML) {
    $sHTML=preg_replace('/&/i','&amp;',$sHTML);
    $sHTML=preg_replace('/</i','&lt;',$sHTML);
    $sHTML=preg_replace('/>/i','&gt;',$sHTML);
    return $sHTML;
}


function formataEnter($variavel) {
	$variavel = trim($variavel);
	$variavel = str_replace("\r", "", $variavel);
	$variavel = str_replace("\n", "", $variavel);
	$variavel = str_replace("\r\n", "", $variavel);
	$variavel = str_replace("\t", "", $variavel);
	$variavel = preg_replace("/(<br.*?>)/i","", $variavel);
	return $variavel;
}


function formatarData($data){
  $rData = implode("-", array_reverse(explode("/", trim($data))));
  return $rData;
}


function Data_atual(){
	return  formatarData(date("d/m/Y"));
}



function formataImagem($imagem, $largura, $altura){

	// Get the details of "Institucional_imagem_pq"
	$filename = $_FILES[$imagem]['name'];
	$temporary_name = $_FILES[$imagem]['tmp_name'];
	$mimetype = $_FILES[$imagem]['type'];
	$filesize = $_FILES[$imagem]['size'];

	//Open the image using the imagecreatefrom..() command based on the MIME type.
	switch($mimetype) {
	    case "image/jpg":
	    case "image/jpeg":
	        $i = imagecreatefromjpeg($temporary_name);
	    break;

	    case "image/gif":
	        $i = imagecreatefromgif($temporary_name);
	    break;

	    case "image/png":
	        $i = imagecreatefrompng($temporary_name);
	    break;
	}

	//Delete the uploaded file
	unlink($temporary_name);

	//Save a copy of the original
	//imagejpeg($i,"images/uploadedfile.jpg",80);

	//Specify the size of the thumbnail
	$dest_x = $largura;
	$dest_y = $altura;

	//Is the original bigger than the thumbnail dimensions?
	if (imagesx($i) > $dest_x or imagesy($i) > $dest_y) {
	//Is the width of the original bigger than the height?
	if (imagesx($i) >= imagesy($i)) {
	    $thumb_x = $dest_x;
	    $thumb_y = imagesy($i)*($dest_x/imagesx($i));
	} else {
	    $thumb_x = imagesx($i)*($dest_y/imagesy($i));
	    $thumb_y = $dest_y;
	}
	} else {
	    //Using the original dimensions
	    $thumb_x = imagesx($i);
	    $thumb_y = imagesy($i);
	}

	//Generate a new image at the size of the thumbnail
	$thumb = imagecreatetruecolor($thumb_x,$thumb_y);

	//Copy the original image data to it using resampling
	imagecopyresampled($thumb, $i ,0, 0, 0, 0, $thumb_x, $thumb_y, imagesx($i), imagesy($i));

	//Save the thumbnail
	//imagejpeg($thumb, "images/thumbnail.jpg", 80);

	ob_start();
	imagejpeg($thumb, NULL, 80);//IF SECOND PARAMETER SET TO NULL, OUTPUTS TO THE BROWSER
	$contents = ob_get_contents();//SAVE $content IN DATABASE
	// end capture
	ob_end_clean();

	return $contents;
}


$meses = array(
    'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro'
);

$diasdasemana = array (1 => "Seg",2 => "Ter",3 => "Qua",4 => "Qui",5 => "Sex",6 => "Sáb",7 => "Dom");


function Envia_email($from, $para, $cc, $bcc, $subject, $BodyFormat, $MailFormat, $Body, $AttachFile) {

	global $servidor_email;

	//$servidor_email = "192.168.1.206";
	//$servidor_email = 'smtp.hc.org.br';

	try {
		ini_set("SMTP",$servidor_email); 
        ini_set("smtp_port", "587");
        
        // USE "\n" para Linux e "\r\n" para Windows
        $nl = "\n"; 

		$to = $para;

		$message = "
		<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
		<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
		<title>" . $subject . "</title>
		</head>
		<body>" . $Body . "</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . $nl;
		$headers .= "Content-type:text/html;charset=utf-8" . $nl;

		// More headers
		$headers .= 'from: <' . $from . '>' . $nl;
		if ($cc != "") $headers .= 'Cc: ' . $cc . $nl;
		if ($bcc != "") $headers .= 'Bcc: ' . $bcc . $nl;

		return mail($to,$subject,$message,$headers);
	}
	catch (Exception $e) {
		acumula_erro("Erro no envio de e-mail > " . $e->getMessage());
	}

}


function Envia_emailAut($from, $para, $cc, $bcc, $subject, $BodyFormat, $MailFormat, $Body, $AttachFile, $responder_para, $nome_user) {

	global $servidor_email;
	
	//$servidor_email = 'smtp.hc.org.br';

	// Inclui o arquivo class.phpmailer.php localizado na pasta class
	require_once($_SERVER['DOCUMENT_ROOT']. $Dominio_Pasta . "/Manager/class/class.phpmailer.php");
	 
	// Inicia a classe PHPMailer
	$mail = new PHPMailer(true);
	 
	// Define os dados do servidor e tipo de conexão
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP(); // Define que a mensagem será SMTP
	 
	try {
		$to = $para;

		$mail->Host = $servidor_email; // Endereço do servidor SMTP
		//$mail->SMTPDebug = 2; // enables SMTP debug information (for testing)
							  // 1 = errors and messages
							  // 2 = messages only
		$mail->CharSet = 'UTF-8';
		$mail->SMTPAuth = true;  // Usar autenticação SMTP
		$mail->Port = 587; //  Usar 587 porta SMTP
		$mail->Username = 'webmaster@hc.org.br'; // Usuário do servidor SMTP (endereço de email)
		$mail->Password = 'AhLUm+&e3?Rz@j'; // Senha do servidor SMTP (senha do email usado)
	 
		//Define o remetente
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
		$mail->SetFrom($from, 'Conselho Regional de Contabilidade do Paraná');
		$mail->AddReplyTo($responder_para, $nome_user);
		$mail->Subject = $subject;
	 
		//Define os destinatário(s)
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->AddAddress($to, $nome_user);
	 
		//Define o corpo do email
		$mail->MsgHTML($Body); 
	 
		$mail->Send();
		//echo "Mensagem enviada com sucesso</p>\n";
	 
	//caso apresente algum erro é apresentado abaixo com essa exceção.
	} 
	catch (phpmailerException $e) {
		acumula_erro("Erro no envio de e-mail > " . $e->errorMessage()); //Mensagem de erro costumizada do PHPMailer
	}

}


function acumula_erro($erro) {
	try {
		$_SESSION["erro"] = $_SESSION["erro"] . "<br>" . $erro;
	}

	catch (Exception $e) {
		$_SESSION["erro"] .= "Problemas com a rotina de erros - " . $e->getMessage();
	}

	echo $_SESSION["erro"];
}


/**
* Monta código para link de validação
**/
function montaCodigo($id) {
	$Aux = $id;
	$md = md5($Aux);
	$tam = strlen($md);
	$LT = 0;
	$ret='';
	for( $i = 0; $i < $tam; $i++ ) {
		$L = $md[$i];
		$T=1;
		if (($L>'/') || ($L<':')) {
			$T=0;
		} else {
			if (($L>'@') || ($L<'[')) {
				$T=0;
			} else {
				if (($L>'`') || ($L<'{')) {
					$T=0;
				}
			}
		}
		if ($T==1) {
			$L = $LT;
			$LT++;
			if ($LT>9) {
				$LT=0;
			}
		}
		$ret.= $L;
	}
	$ret = substr($ret, -99);
	return $ret;
}



/**
* Transforma todos caracteres com glifos para seu equivalente sem glifo.
* Caracteres que não forem letras ou não ocidentais serão convertidos em _ 
*
* @param {String}           $str                    String original.
*
* @return {String}
*/
if (!function_exists("ReplaceGlyphs")) {
    function ReplaceGlyphs($str) {
        $rem = mb_str_split("ÄÅÁÂÀÃäáâàãÉÊËÈéêëèÍÎÏÌíîïìÖÓÔÒÕöóôòõÜÚÛüúûùÇç");
        $sub = mb_str_split("AAAAAAaaaaaEEEEeeeeIIIIiiiiOOOOOoooooUUUuuuuCc");

        for($i=0; $i<count($rem); $i++) {
            $str = mb_str_replace($rem[$i], $sub[$i], $str);
        }

        $num = mb_str_split("0123456789");
        $upp = mb_str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $low = mb_str_split("abcdefghijklmnopqrstuvwxyz");

		$str = mb_str_replace(" ", "_", $str);

        // Verifica as strings que sobraram
        //for($i=0; $i<mb_strlen($str); $i++) {
        //    $c = $str[$i];
        //    if(!in_array($c, $num) && !in_array($c, $upp) && !in_array($c, $low)) {
        //        $str = mb_str_replace($c, "_", $str);
        //    }
        //}

        return $str;
    }
}





/**
* Replace all occurrences of the search string with the replacement string.
*
* @param mixed $search
* @param mixed $replace
* @param mixed $subject
* @param int $count
*
* @return mixed
*/
if (!function_exists("mb_str_replace")) {
    function mb_str_replace($search, $replace, $subject, &$count = 0) {
        if (!is_array($subject)) {
            // Normalize $search and $replace so they are both arrays of the same length
            $searches = is_array($search) ? array_values($search) : array($search);
            $replacements = is_array($replace) ? array_values($replace) : array($replace);
            $replacements = array_pad($replacements, count($searches), "");
 
            foreach ($searches as $key => $search) {
                $parts = mb_split(preg_quote($search), $subject);
                $count += count($parts) - 1;
                $subject = implode($replacements[$key], $parts);
            }
        } else {
            // Call mb_str_replace for each subject in array, recursively
            foreach ($subject as $key => $value) {
                $subject[$key] = mb_str_replace($search, $replace, $value, $count);
            }
        }
 
        return $subject;
    }
}





/**
* Convert a string to an array.
*
* @param mixed $string
* @param mixed $split_length
*
* @return mixed
*/
if (!function_exists("mb_str_split")) {
    function mb_str_split($string, $string_length=1) {
        $charset = "utf-8";
        $string_length = ($string_length <= 0) ? 1 : $string_length;

        if(mb_strlen($string, $charset) > $string_length) {
            do {
                $c = mb_strlen($string, $charset);
                $parts[] = mb_substr($string, 0, $string_length, $charset);
                $string = mb_substr($string, $string_length, $c - $string_length, $charset);
            }
            while(!empty($string));
        } 
        else { 
            $parts = array($string);
        }

        return $parts;
    }
}






/**
* Armazena o log de uma ação executada.
*
* @param {Integer}      $Funcionarios_id        ID do usuário que executou a ação.
* @param {String}       $InstrucaoSQL           Instrução SQL que foi executada.
*
* @return {String}
*/
function get_client_ip() {
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

function WriteLogAction($Funcionarios_id, $InstrucaoSQL) {
    global $con;
    $DataDaAcao = new DateTime();


    $Funcionarios_id = (integer)$Funcionarios_id;
    $DataDaAcao = $DataDaAcao->format("Y-m-d H:i:s");
    $Acao = NULL;
    $Tabela = NULL;
    $InstrucaoSQL = str_replace("'", "''", stripslashes($InstrucaoSQL));
	$ip = get_client_ip();

    // Identifica ação que está sendo executada
    $SQLSplit = explode(" ", trim($InstrucaoSQL));
    $count = 0;
    do {
        if(trim($SQLSplit[$count]) != "") {
            $Acao = strtolower(trim($SQLSplit[$count]));
        }
        $count++;
    }
    while ($Acao == NULL);





    $getPosition = 3;
    $validWordCounter = 0;
    $count = 0;


    // Identifica a posição da palavra que define a tabela onde a ação está sendo executada
    switch($Acao) {
        case "insert":          // INSERT INTO "tablename"
            $getPosition = 3;
            break;

        case "update":          // UPDATE "tablename"
            $getPosition = 2;
            break;

        case "delete":          // DELETE FROM "tablename"
            $getPosition = 3;
            break;
    }



    // Identifica a tabela
    while($Tabela == NULL) {
        if(trim($SQLSplit[$count]) != "") {
            $validWordCounter++;

            if($validWordCounter == $getPosition) {
                $Tabela = strtolower(trim($SQLSplit[$count]));
            }
        }

        $count++;
    }





    $strSQL = "INSERT INTO 
                    log 
                    (Funcionarios_id, DataDaAcao, Acao, Tabela, InstrucaoSQL, ip) 
               VALUES 
                    (" . $Funcionarios_id . ", '" . $DataDaAcao . "', '" . $Acao . "', '" . $Tabela . "', '" . $InstrucaoSQL . "', '" . $ip . "')";


    db_query($con, $strSQL);
}




function RemoveAcentos($string){
	return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}









/**
	* Efetua o redimensionamento de uma imagem conforme os parametros de
	* configuração.
	*
	* @param       string      $absoluteSystemPathToOriginalImage
	*              Caminho completo até a imagem.
	*
	* @param       string      $absoluteSystemPathToNewImage
	*              Caminho completo até o local onde a nova  imagem será armazenada.
	*
	* @param       string      $resizeType
	*              Tipo de ajuste que será feito.
	*              Os seguintes valores são aceitos:
	*              exact         : redimenciona a imagem exatamente na medida definida em
	*                              "$imgMaxWidth" e "$imgMaxHeight".
	*                              Se um deste valores não for definido, manterá o valor inicial da imagem.
	*
	*              portrait      : redimensiona (verticalmente) a imagem parando quando encontra chegar na
	*                              altura máxima definida em "$imgMaxHeight".
	*
	*              landscape     : redimensiona (horizontalmente) a imagem parando quando encontra chegar na
	*                              largura máxima definida em "$imgMaxWidth".
	*
	*              auto          : redimensiona a imagem até que uma das dimensões encontre um dos valores
	*                              máximos definidos por "$imgMaxWidth" e "$imgMaxHeight".
	*
	* @param       ?int        $imgMaxWidth
	*              Largura máxima que a imagem deverá ter.
	*              Se não for definido, este valor será calculado conforme o tipo "$resizeType".
	*
	* @param       ?int        $imgMaxHeight
	*              Altura máxima que a imagem deverá ter.
	*              Se não for definido, este valor será calculado conforme o tipo "$resizeType".
	*
	* @param       ?bool       $imgCrop
	*              Quando "true", irá, após redimencionar a imagem, efetuar um crop(corte) na imagem resultante
	*              e salvará este corte ao invés da imagem como um todo.
	*              Para evitar que um crop exceda os limites de uma imagem que será redimencionada por um
	*              método dinâmico (portrair | landscape | auto) é recomendavel, mas não obrigatório,
	*              que esta opção seja usada em conjunto com o método "exact".
	*
	* @param       ?int        $imgCropWidth
	*              Largura do crop que será feito.
	*              Apenas surte efeito se "$resizeType" for definido como "crop".
	*
	* @param       ?int        $imgCropHeight
	*              Altura do crop que será feito.
	*              Apenas surte efeito se "$resizeType" for definido como "crop".
	*
	* @param       ?int        $imgCropX
	*              Posição no eixo X a partir de onde o corte da imagem deve ocorrer.
	*              Apenas surte efeito se "$resizeType" for definido como "crop".
	*
	* @param       ?int        $imgCropY
	*              Posição no eixo Y a partir de onde o corte da imagem deve ocorrer.
	*              Apenas surte efeito se "$resizeType" for definido como "crop".
	*
	* @return      bool
	*/
function resizeImage(
	$absoluteSystemPathToOriginalImage,
	$absoluteSystemPathToNewImage,
	$resizeType,
	$imgMaxWidth = null,
	$imgMaxHeight = null,
	$imgCrop = null,
	$imgCropWidth = null,
	$imgCropHeight = null,
	$imgCropX = null,
	$imgCropY = null
) {


	$r = false;


	// @type {Resource} Objeto que representa a imagem que será modificada.
	$imageOriginal = false;
	
	// @type {Resource} Objeto que representa a imagem após as alterações.
	$imageFinal = null;

	// @type {Resource} Objeto que representa a imagem cropada.
	$imageCropped = null;





	$ext = strtolower(strrchr($absoluteSystemPathToOriginalImage, "."));
	switch ($ext) {
		case ".jpg":
		case ".jpeg":
			$imageOriginal = @imagecreatefromjpeg($absoluteSystemPathToOriginalImage);
			break;

		case ".gif":
			$imageOriginal = @imagecreatefromgif($absoluteSystemPathToOriginalImage);
			break;

		case ".png":
			$imageOriginal = @imagecreatefrompng($absoluteSystemPathToOriginalImage);
			break;

		default:
			$imageOriginal = false;
			break;
	}





	// Se não for possível carregar a imagem...
	if ($imageOriginal == false) {
		$msg = "Could not load the target image. File \"" . $absoluteSystemPathToOriginalImage . "\" .";
		throwException("Fatal Error");
	} else {
		$imageOriginalWidth     = imagesx($imageOriginal);
		$imageOriginalHeight    = imagesy($imageOriginal);

		$imgMaxWidth            = ($imgMaxWidth == null) ? $imageOriginalWidth : $imgMaxWidth;
		$imgMaxHeight           = ($imgMaxHeight == null) ? $imageOriginalHeight : $imgMaxHeight;

		$imgCropWidth           = ($imgCropWidth == null) ? $imageOriginalWidth : $imgCropWidth;
		$imgCropHeight          = ($imgCropHeight == null) ? $imageOriginalHeight : $imgCropHeight;
		$imgCropX               = ($imgCropX == null) ? 0 : $imgCropX;
		$imgCropY               = ($imgCropY == null) ? 0 : $imgCropY;

		$imageFinalWidth        = null;
		$imageFinalHeight       = null;



		// Efetua calculos para encontrar as novas dimensões da imagem
		switch ($resizeType) {
			case "exact":
				$imageFinalWidth    = $imgMaxWidth;
				$imageFinalHeight   = $imgMaxHeight;
				break;

			case "portrait":
				$imageFinalWidth    = (int)($imgMaxHeight * ($imageOriginalWidth / $imageOriginalHeight));
				$imageFinalHeight   = $imgMaxHeight;
				break;

			case "landscape":
				$imageFinalWidth    = $imgMaxWidth;
				$imageFinalHeight   = (int)($imgMaxWidth * ($imageOriginalHeight / $imageOriginalWidth));
				break;

			case "auto":
				// Calcula a imagem como se fosse "portrait"
				if ($imageOriginalHeight > $imageOriginalWidth) {
					$imageFinalWidth    = (int)($imgMaxHeight * ($imageOriginalWidth / $imageOriginalHeight));
					$imageFinalHeight   = $imgMaxHeight;
				} // Calcula a imagem como se fosse "landscape"
				elseif ($imageOriginalHeight < $imageOriginalWidth) {
					$imageFinalWidth    = $imgMaxWidth;
					$imageFinalHeight   = (int)($imgMaxWidth * ($imageOriginalHeight / $imageOriginalWidth));
				} // Se a imagem é quadrada...
				else {
					// Adapta imagem "landscape"
					if ($imgMaxHeight > $imgMaxWidth) {
						$imageFinalWidth    = (int)($imgMaxHeight * ($imageOriginalWidth / $imageOriginalHeight));
						$imageFinalHeight   = $imgMaxHeight;
					} // Adapta imagem "portrait"
					elseif ($imgMaxHeight < $imgMaxWidth) {
						$imageFinalWidth    = $imgMaxWidth;
						$imageFinalHeight   = (int)($imgMaxWidth * ($imageOriginalHeight / $imageOriginalWidth));
					} // Adapta imagem para as novas dimensões
					else {
						$imageFinalWidth = $imgMaxWidth;
						$imageFinalHeight= $imgMaxHeight;
					}
				}
				break;
		}




		// Recria a imagem com as dimensões calculadas
		$imageFinal = imagecreatetruecolor($imageFinalWidth, $imageFinalHeight);
		imagecopyresampled( $imageFinal, $imageOriginal,
							0, 0,
							0, 0,
							$imageFinalWidth, $imageFinalHeight,
							$imageOriginalWidth, $imageOriginalHeight);




		// Se for para "cropar"
		if ($imgCrop) {
			// Efetua o corte.
			$imageCropped = imagecreatetruecolor($imgCropWidth, $imgCropHeight);
			imagecopyresampled( $imageCropped, $imageFinal,
								0, 0,
								$imgCropX, $imgCropY,
								$imgCropWidth, $imgCropHeight,
								$imgCropWidth, $imgCropHeight);
			imagejpeg($imageCropped, $absoluteSystemPathToNewImage, 90);


			$imageFinal = $imageCropped;
		}




		// Verifica se a extenção do nome da imagem final confere com a original
		// altera em caso de discordancia.
		$nExt = strtolower(strrchr($absoluteSystemPathToNewImage, "."));
		if ($nExt != $ext) {
			$absoluteSystemPathToNewImage .= "_" . $ext;
		}



		// Salva a imagem conforme sua extenção
		switch ($ext) {
			case ".jpg":
			case ".jpeg":
				imagejpeg($imageFinal, $absoluteSystemPathToNewImage, 90);
				break;

			case ".gif":
				imagegif($imageFinal, $absoluteSystemPathToNewImage);
				break;

			case ".png":
				imagepng($imageFinal, $absoluteSystemPathToNewImage, 0);
				break;
		}


		imagedestroy($imageOriginal);
		imagedestroy($imageFinal);


		// Testa se a imagem nova foi gerada com sucesso
		$r = file_exists($absoluteSystemPathToNewImage);
	}



	return $r;
}

function Prepara_URL_Amigavel($url) {
	$url_tratada = str_replace(",", "/", str_replace(">", "/", str_replace(" ","-", trim($url))));
	setlocale(LC_CTYPE, 'pt_BR');
	return strtolower(preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $url_tratada ) ));
}


function tira_pontos_tracos_cpf($cpf)
{
  $novoCpf = str_replace('.', '', $cpf);
  $novoCpf = str_replace('-', '', $novoCpf);
  $novoCpf = str_replace('/', '', $novoCpf);
  return $novoCpf;
}


function xss_clean($data)
{
	
	// Fix &entity\n;
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	//$old_data = $data;
	//$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);

	do 
	{
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);

	$data = xssafe($data);

	// we are done...
	return $data;
}

function xssafe($data,$encoding='UTF-8')
{
   return tira_aspas(htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding));
}


//========================================================================================================
// Monta menu lateral para páginas com menu
//========================================================================================================

function Menu_interna()
{

	$nivel1 = "";
	$nivel2 = "";
	$menu_montado = "";

	$nivel1 =  "<script type=\"text/javascript\">";
	$nivel1 .=	"if('" . $_SESSION["secao_id"] . "' == 'secao_id') {";
	$nivel1 .= "document.write('<a href=\"secao_link\" class=\"list-group-item menu_link\" style=\"color:#25725d;\"><i class=\"menu_pai\"></i> secao_nome</a>');";
	$nivel1 .= "} else {";
	$nivel1 .= "document.write('<a href=\"secao_link\" class=\"list-group-item\"><i class=\"menu_pai\"></i> secao_nome</a>');";
	$nivel1 .= "}";
	$nivel1 .= "</script>";

	$nivel2 =  "<script type=\"text/javascript\">";
	$nivel2 .= "if('" . $_SESSION["secao_id"] . "' == 'secao_id') {";
	$nivel2 .= "	document.write('<a href=\"secao_link\" class=\"list-group-item small\" style=\"color:#25725d;\"><i class=\"glyphicon glyphicon-chevron-right\"></i> secao_nome</a>');";
	$nivel2 .= "} else {";
	$nivel2 .= "	document.write('<a href=\"secao_link\" class=\"list-group-item small\"><i class=\"glyphicon glyphicon-chevron-right\"></i> secao_nome</a>');";
	$nivel2 .= "}";
	$nivel2 .= "</script>";

	$secao_id_principal = Busca_secao_nivel(2);
	$menu_montado .= '<div id="sidebar-nav">';
	$menu_montado .= '<div class="list-group">';
	$menu_montado .= '<a href="" class="list-group-item active" style="background-color: #25725d; border-color: #25725d;"><strong>' . Busca_secao_nome($secao_id_principal) . '</strong></a>';
	$menu_montado .= menu_plus2($secao_id_principal, '', $nivel1, '', '<div class="sublinks">', $nivel2, '</div>', '', '', '', '', '', '', '', '', '');
	$menu_montado .= '</div>';
	$menu_montado .= '</div>';
	return $menu_montado;
}

function Menu_interna_2($secao_id_principal)
{

	$nivel1 = "";
	$nivel2 = "";
	$menu_montado = "";

	$nivel1 =  "<script type=\"text/javascript\">";
	$nivel1 .=	"if('" . $_SESSION["secao_id"] . "' == 'secao_id') {";
	$nivel1 .= "document.write('<a href=\"secao_link\" class=\"list-group-item menu_link\" style=\"color:#008379;\"><i class=\"menu_pai\"></i>secao_nome</a>');";
	$nivel1 .= "} else {";
	$nivel1 .= "document.write('<a href=\"secao_link\" class=\"list-group-item\"><i class=\"menu_pai\"></i>secao_nome</a>');";
	$nivel1 .= "}";
	$nivel1 .= "</script>";

	$nivel2 =  "<script type=\"text/javascript\">";
	$nivel2 .= "if('" . $_SESSION["secao_id"] . "' == 'secao_id') {";
	$nivel2 .= "	document.write('<a href=\"secao_link\" class=\"list-group-item small\" style=\"color:#008379;\"><i class=\"glyphicon glyphicon-chevron-right\"></i> secao_nome</a>');";
	$nivel2 .= "} else {";
	$nivel2 .= "	document.write('<a href=\"secao_link\" class=\"list-group-item small\"><i class=\"glyphicon glyphicon-chevron-right\"></i> secao_nome</a>');";
	$nivel2 .= "}";
	$nivel2 .= "</script>";

	//$secao_id_principal = Busca_secao_nivel(2);
	$menu_montado .= '<div id="sidebar-nav">';
	$menu_montado .= '<div class="panel list-group">';
	$menu_montado .= '<a href="" class="list-group-item active" style="background-color: #f5f5f5;border-color: #f5f5f5;color: #666;"><strong>' . strtoupper(Busca_secao_nome($secao_id_principal)) . '</strong></a>';
	$menu_montado .= menu_plus2($secao_id_principal, '', $nivel1, '', '<div class="sublinks">', $nivel2, '</div>', '', '', '', '', '', '', '', '', '');
	$menu_montado .= '</div>';
	$menu_montado .= '</div>';
	return $menu_montado;
}

function Menu_interna_3($secao_id_principal){
	$nivel1 = "";
	$nivel2 = "";

	$nivel1 =  "<script type=\"text/javascript\">";
	$nivel1 .=	"if('" . $_SESSION["secao_id"] ."' == 'secao_id') {";
	$nivel1 .= "document.write('<a href=\"secao_link\" class=\"list-group-item menu_link\" style=\"color:#006848;\"><i class=\"menu_pai\" style=\"color: #006848 !important;\"></i> secao_nome</a>');";
	$nivel1 .= "}else{";
	$nivel1 .= "document.write('<a href=\"secao_link\" class=\"list-group-item\"><i class=\"menu_pai\" style=\"color: #006848 !important;\"></i> secao_nome</a>');";
	$nivel1 .= "}";
	$nivel1 .= "</script>";

	$nivel2 =  "<script type=\"text/javascript\">";
	$nivel2 .= "if('" . $_SESSION["secao_id"] ."' == 'secao_id') {";
	$nivel2 .= "	document.write('<a href=\"secao_link\" class=\"list-group-item small\" style=\"color:#006848;\"><i class=\"glyphicon glyphicon-chevron-right\" style=\"color: #006848 !important;\"></i> secao_nome</a>');";
	$nivel2 .= "}else{";
	$nivel2 .= "	document.write('<a href=\"secao_link\" class=\"list-group-item small\"><i class=\"glyphicon glyphicon-chevron-right\" style=\"color: #006848 !important;\"></i> secao_nome</a>');";
	$nivel2 .= "}";
	$nivel2 .= "</script>";

	$secao_id_principal = Busca_secao_nivel(1);
	echo '<div class="col-md-3">';
	echo	'<div id="sidebar-nav">';
	echo		'<div class="panel list-group">';
	echo			'<a href="../" class="list-group-item active">Home</a>';
	echo			menu_plus2($secao_id_principal , '', $nivel1, '', '<div class="sublinks">', $nivel2, '</div>', '', '', '', '', '', '', '', '', '');
	echo		'</div>';
	echo	'</div>';
	echo '</div>';
	echo '<div id="corpo_pagina" class="col-md-9">';
}