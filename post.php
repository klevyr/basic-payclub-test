<?php 
session_start(); 
$_SESSION["xss"] = md5(uniqid(mt_rand(), true));
?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
	<title>CAPP :: Comprobar Posproceso </title>
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
				if ($("#inputXmlReq").val().length > 0 && $("#inputVI").val().length > 0 && $("#inputSKey").val().length > 0){
					var _x = $("#inputXmlReq").val();
					$("#xml").val(_x);
					
					var _v = $("#inputVI").val();
					$("#vi").val(_v);
					
					var _s = $("#inputSKey").val();
					$("#sk").val(_s);
					
					$( "#frmpostval" ).submit();
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
		<div class="col-lg-6 col-offset-5">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h2 class="panel-title pull-left" style="padding-top: 7.5px;">Validaci&oacute;n Parametrizaci&oacute;n Payclub Express</h2>
				</div>
				<div class="panel-body">
					<form id="frmpostval" action="postval.php" method="POST">
					  <div class="form-group">
					    <label for="inputXmlReq">Tramsa XMLREQ</label>
					    <input type="text" class="form-control" id="inputXmlReq" placeholder="XMLReq" min="0" data-bind="value:inputXmlReq" required="required">
					  </div>
					  <div class="form-group">
					    <label for="inputVI">Vector</label>
					    <input type="text" class="form-control" id="inputVI" placeholder="VI" min="0" data-bind="value:inputVI" required="required">
					  </div>
					  <div class="form-group">
					    <label for="inputSKey">Simetric Key</label>
					    <input type="text" class="form-control" id="inputSKey" placeholder="Sim.Key" min="0" data-bind="value:inputSKey" required="required">
					  </div>
					  <input type="hidden" id="t" name="t" value="<?php echo $_SESSION['xss']; ?>" />
					  <input type="hidden" id="xml" name="xml" value="" />
					  <input type="hidden" id="vi" name="vi" value="" />
					  <input type="hidden" id="sk" name="sk" value="" />
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
