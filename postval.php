<?php
session_start();

if($_SESSION['xss'] != $_POST["t"]) { echo "Token no valido!"; die(); }

require_once("lib/PayclubPlugin.php");
$ps = new PayclubPosproc();
$ps->setIV( urldecode($_POST['vi']) ) ;
$ps->setSimetricKey( urldecode($_POST['sk']) );

$xmlReq = $ps->getXMLReq( ($_POST['xml']) );

?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
	<title>CAPP :: Validaci&oacute;n Payclub Express</title>
	  	<meta charset="iso-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<!--link rel="stylesheet" href="css/theme.css"-->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/docs.min.js"></script>
	<script src="js/ie10-viewport-bug-workaround.js"></script>
	
</head>
<body>
	<br/>
		<div id="container theme-showcase" role="main">
	<div class="col-md-10 col-md-offset-0">
		<div class="col-lg-6 col-offset-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">PostProceso Validating</h3>
                </div>
                <div class="panel-body">
                  <code>
                      <?php
                      foreach ($xmlReq as $k => $v) {
                          print( "[{$k}] => [{$v}] "."\r\n<br>" );
                      }
                      ?>
                  </code>
                </div>
          </div>
			</div>
		</div>
	</div>


	</div>
	<div id="footer" class="footer navbar-fixed-bottom" style="text-align: center;">
		<div class="row">
			<div class="col-md-5" style='text-align: right;'><small>PayExTest &copy; 2016</small></div>
		</div>
	</div>
</body>
</html>