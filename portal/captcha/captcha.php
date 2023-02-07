<?php
   session_start(); // inicial a sessao
   header("Content-type: image/png"); // define o tipo do arquivo
    
    function captcha($palavra){
		
        $imagemCaptcha = imagecreatefrompng("fundocaptch.png"); // define a largura e a altura da imagem
        
		$fonteCaptcha = imageloadfont("arial.gdf"); //voce deve ter essa ou outra fonte de sua preferencia em sua pasta
        
        $corCaptcha = imagecolorallocate($imagemCaptcha,4,58,132); 
                
        $_SESSION["palavra"] = $palavra; // atribui para a sessao a palavra gerada
        
		imagestring($imagemCaptcha,$fonteCaptcha,30,12,$palavra,$corCaptcha);
            
        imagepng($imagemCaptcha); // gera a imagem

        imagedestroy($imagemCaptcha); // limpa a imagem da memoria
    }
    
    $palavra = $_GET["l"]; // recebe a largura
    captcha($palavra); // executa a funcao captcha passando os parametros recebidos
?>