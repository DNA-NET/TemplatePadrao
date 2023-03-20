<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">

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