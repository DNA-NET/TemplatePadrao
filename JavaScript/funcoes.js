// JavaScript Document
$(document).ready(function(){
	//Mascara paara CPF e CNPJ
	$("#FuncionariosCPF_alterar").keydown(function(){
		try {
			$("#FuncionariosCPF_alterar").unmask();
		} catch (e) {}

		var tamanho = $("#FuncionariosCPF_alterar").val().length;

		if(tamanho < 11){
			$("#FuncionariosCPF_alterar").mask("999.999.999-99");
		} else {
			$("#FuncionariosCPF_alterar").mask("99.999.999/9999-99");
		}

		// ajustando foco
		var elem = this;
		setTimeout(function(){
			// mudo a posição do seletor
			elem.selectionStart = elem.selectionEnd = 10000;
		}, 0);
		// reaplico o valor para mudar o foco
		var currentValue = $(this).val();
		$(this).val('');
		$(this).val(currentValue);
	});

	//Mascara pra Telefone
	$('#FuncionariosTelefone_alterar').mask('(00) 0000-00009');
	$('#FuncionariosTelefone_alterar').blur(function(event) {
		if($(this).val().length == 15){ // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
			$('#FuncionariosTelefone_alterar').mask('(00) 00000-0009');
		} else {
			$('#FuncionariosTelefone_alterar').mask('(00) 0000-00009');
		}
	});



	$("a[name=aba]").click(function () {
            debugger;
			$("div[name=layers]").hide();
			$("a[name=aba]").attr('class','');
			$(this).blur();
	  		$(this).parent().next().children().fadeIn();
			//$(this).parent().children('div').show();
			$(this).addClass('select');
});

});

// JavaScript Document
$(document).ready(function () {


    $("a[name=abel]").click(function () {
        debugger;
        $("div[name=camadas]").hide();
        $("a[name=abel]").attr('class', '');
        $(this).blur();
        $(this).parent().next().children().fadeIn();
        //$(this).parent().children('div').show();
        $(this).addClass('select');
    });

});


function Popup(id)      
	{         
		var DocumentContainer = document.getElementById(id);
		var mywindow = window.open('', 'Imprime', 'width=800,scrollbars=yes');        
		mywindow.document.write('<html><head><title>:: CRA-SP ::</title>');         
		mywindow.document.write('<link rel="stylesheet" href="../App_themes/skin_impressao.css" type="text/css" />');         
		mywindow.document.write('</head><body ><div id="conteudo">');         
		mywindow.document.write(DocumentContainer.innerHTML);         
		mywindow.document.write('</div></body></html>');         
		mywindow.document.close();         
		mywindow.print();         
		return true;     
	}


function formatar_mascara(src, mascara) {
	 var campo = src.value.length;
	 var saida = mascara.substring(0,1);
	 var texto = mascara.substring(campo);
	 if(texto.substring(0,1) != saida) {
		src.value += texto.substring(0,1);
	 }
}

