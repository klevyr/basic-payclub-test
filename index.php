<?php 
session_start(); 
$_SESSION["xss"] = md5(uniqid(mt_rand(), true));
#autoload
require_once("vendor/autoload.php");
#
$config = new \Payclub\Config('prod');
?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
	<title><?php echo $config->get('apptitle'); ?></title>
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
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#invokeButton").click(function(){
				if ($("#inputMid").val().length > 0 && $("#inputVal").val().length > 0){
					var _mid = $("#inputMid").val();
					$("#mid").val(_mid);
					
					var _val = $("#inputVal").val();
					$("#val").val(_val);
					
					$( "#frmpayexp" ).submit();
				}else 
					alert("Datos imcompletos, por favor revisar!");
			});
		});
	</script>
</head>
<body>
	<br/>
		<div id="container theme-showcase" role="main">
	<div class="col-md-10 col-md-offset-0">
		<div class="col-lg-5 col-offset-5">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h2 class="panel-title pull-left" style="padding-top: 7.5px;"><?php echo $config->get('appdesc'); ?></h2>
				</div>
				<div class="panel-body">
					<form id="frmpayexp" action="redirect.php" method="POST">
					  <div class="form-group">
					    <label for="inputMid">RUC Payclub</label>
					    <input type="text" class="form-control" id="inputMid" placeholder="RUC" min="0" data-bind="value:inputMid" required="required">
					  </div>
					  <div class="form-group">
					    <label class="sr-only" for="inputVal">Monto</label>
					    <div class="input-group">
					      <div class="input-group-addon">$</div>
					      <input type="text" class="form-control" id="inputVal" placeholder="Monto" min="0" data-bind="value:inputVal" required="required">
					      <div class="input-group-addon">.00</div>
					    </div>
					  </div>
					  <div class="form-group">
						<select class="form-control" name="localid" id="localid">
								<option value="GN01" selected="selected">Localidad por Defecto GN01</option>
								<option value="GN02">Localidad Secundaria GN02</option>
						</select>
				      </div>
					  <input type="hidden" id="t" name="t" value="<?php echo $_SESSION['xss']; ?>" />
					  <input type="hidden" id="mid" name="mid" value="" />
					  <input type="hidden" id="val" name="val" value="" />
				</form>
				</div>
				<div class="panel-footer">&nbsp;<div id="infostatus" style="float:Left;"></div>
					<div class="btn-group pull-right">
						<div id="invokeButton" class="btn btn-success btn-sm"><i class="fa fa-paper-plane fa-sm"></i> &nbsp;Enviar </div>
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
