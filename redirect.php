<?php
session_start();

if($_SESSION['xss'] != $_POST["t"]) { echo "Token no valido!"; die(); }

require_once("lib/PayclubPlugin.php");
$ps = new PayclubSend($_POST['mid'], $_POST['localid']);
$formfields = $ps->setParamametros(['trxid' => mktime(), 
                                   'subt' => $_POST['val']*100,
                                   'tax1' => '0',
                                   'tax2' => '0',
                                   'tip' => '0',
                                   'ref1' => 'PXTest',
                                   'ref2' => 'Diners',
                                   'ref3' => '',
                                   'ref4' => '',
                                   'ref5' => '']
                                 )->getFormFields();

// Create Fields & Values
$fields = "";
foreach ($formfields as $field=>$value) {
    $fields .="<input type='hidden' name='{$field}' id='{$field}' value='{$value}'>\n";
}

?>
<html>
    <body>
        <font face='Verdana, Geneva, sans-serif'>Est&aacute; siendo redirigido al portal de PayClub de Dinersclub para realizar el pago...</font>
        <form id='dnmod_shared_checkout' name='dnmod_shared_checkout' action='https://www.optar.com.ec/webmpi/vpos' method='post'>
            <?php echo  $fields; ?>
            <font face='Verdana, Geneva, sans-serif'>Si no se redirecciona el portal presiona el bot&oacute;n </font>
            <input type='submit' id='send' value='Enviar'>
        </form>
        <script type="text/javascript">
            (function() {
                document.getElementById("dnmod_shared_checkout").submit();
            })();
        </script>
    </body>
</html>
