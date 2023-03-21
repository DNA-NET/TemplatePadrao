<?php
session_start(); 
if (isset($_POST['inpHtml'])) { 
	$_SESSION['content'] = $_POST['inpHtml'];
	header("Location: example2.php");
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#" /> 
     
    <link href="assets/minimalist-blocks/content.css" rel="stylesheet" type="text/css" />
    <link href="contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />

    <style>
        .container {  margin: 120px auto; max-width: 800px; width:100%; padding:0 35px; box-sizing: border-box;}
    </style>
</head>
<body>

<div class="container">
    <?php
	if(empty($_SESSION['content'])==true) {
		/* Sample initial content (ex. from database) */
		echo '<div class="row clearfix">
			<div class="column full">
				<h2 class="size-32" style="text-align: center;">Simply Beautiful</h2>
			</div>
		</div>
		<div class="row clearfix">
			<div class="column full">
				<p class="size-16" style="text-align: center; letter-spacing: 3px;">( SOMETHING TO SHARE )</p>
			</div>
		</div>
		<div class="row clearfix">
			<div class="column full">
				<div class="spacer height-80"></div>
			</div>
		</div>
        <div class="row clearfix">
            <div class="column half">
                <img src="assets/minimalist-blocks/example.jpg">
            </div>
            <div class="column half">
                <p style="text-align: justify;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            </div>
		</div>';
	} else {
		echo $_SESSION['content'];
	}
    ?>
</div>

<!-- Hidden Form Fields to post content -->
<form id="form1" method="post" style="display:none">
	<input type="hidden" id="inpHtml" name="inpHtml" />
	<input type="submit" id="btnPost" value="submit" />
</form>

<div class="is-tool" style="position:fixed;width:70px;height:50px;top:30px;left:30px;right:auto;border:none;display:block;">
	<button id="btnSave" class="classic" style="width:70px;height:50px;">SAVE</button>
</div>

<script src="contentbuilder/contentbuilder.min.js" type="text/javascript"></script>
<script src="assets/minimalist-blocks/content.js" type="text/javascript"></script>

<script type="text/javascript">

var builder = new ContentBuilder({
    container: '.container',
});

var btnSave = document.querySelector('#btnSave');
btnSave.addEventListener('click', (e) => {
    
    builder.saveImages('saveimage.php', function(){
        
        //Get html
        var html = builder.html(); //Get content

        //Submit the html to the server for saving. For example, if you're using html form:
        document.querySelector('#inpHtml').value = html;
        document.querySelector('#btnPost').click();

    });

});

</script>

</body>
</html>
