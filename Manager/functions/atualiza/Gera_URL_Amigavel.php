<?php
//=============================================================================================================
// Gera URL Amigavel
//=============================================================================================================
function Gera_URL_Amigavel()
{
    global $con;

    if (isset($_REQUEST["secao_id"])) {
        if (strpos($_REQUEST["secao_id"], ",") === false) {
            try {
                $URL_amigavel = '/ipen';
                $URL_Original = $_SERVER["REQUEST_URI"];
                $dtA = mysqli_query($con, "select url_amigavel from url_amigavel where url_original = '" . $URL_Original . "'");

                if (mysqli_num_rows($dtA) > 0) {
                    $row1 = mysqli_fetch_assoc($dtA);
                    $URL_amigavel = $row1["url_amigavel"];
                } else {
                    $dtS = mysqli_query($con, "select secao_nome_ordem as nome from secao where secao_id = " . $_REQUEST["secao_id"]);

                    if (mysqli_num_rows($dtS) > 0) {
                        $row = mysqli_fetch_assoc($dtS);
                        $URL_amigavel .= Prepara_URL_Amigavel($row["nome"]);
                    }

                    $campo = "0";
                    if (isset($_REQUEST["campo"])) {
                        $campo = $_REQUEST["campo"];
                        $dtC = mysqli_query($con, "select institucional_nome as nome from institucional where institucional_id = " . $_REQUEST["campo"]);

                        if (mysqli_num_rows($dtC) > 0) {
                            $row = mysqli_fetch_assoc($dtC);
                            $URL_amigavel .= "/" . Prepara_URL_Amigavel($row["nome"]);
                        }
                    }

                    $URL_amigavel = str_replace("/portal_por/ipen", "/ipen", $URL_amigavel);

                    mysqli_query($con, "insert into url_amigavel (secao_id, campo, url_original, url_amigavel, url_data) values (" . $_REQUEST["secao_id"] . ", " . $campo . ", '" . $URL_Original . "', '" . $URL_amigavel . "', CURDATE())");
                }

?>
<script type="text/javascript">
var stateObj = {
    foo: "<?php echo $URL_amigavel ?>"
};

function change_my_url() {
    history.pushState(stateObj, "page 2", "<?php echo $URL_amigavel ?>");
}

window.onload = function() {
    change_my_url()
};
</script>

<?php
            } catch (Exception $e) {
                $_SESSION["erro"] .= "Problemas com gerção da URL amigavel - " . $e->getMessage();
            }
        }
    }
}