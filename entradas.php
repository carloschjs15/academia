<?php
	setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' ); 
    date_default_timezone_set( 'America/Fortaleza' );
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Fitline</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Sistema de Academia" />
	<meta name="keywords" content="html5, template, bootstrap, css3, mobile first, responsividade" />
	<meta name="author" content="Carlos Henrique" />

	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link href='css/roboto.css' rel='stylesheet' type='text/css'>
	<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
	<link rel="shortcut icon" href="icon.png">

	<link href="apis/icon.css" rel="stylesheet">
	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Superfish -->
	<link rel="stylesheet" href="css/superfish.css">

	<link rel="stylesheet" href="css/style.css">


	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	<style type="text/css">
		*::selection{
		  background-color: rgba(255,255,0,0.8);
		  color: dimgray;
		}

		::-webkit-input-placeholder{
			color: gold; 
		}
		:-moz-placeholder{
			color: gold;
		}
		::-moz-placeholder{
			color: gold;
		}
		:-ms-input-placeholder{
			color: gold;
		}
		#cliente{
			padding: 0.5em;
			font-size: 1.5em;
			width: auto;
			color: black;
		}
	</style>
	<script type="text/javascript">
		var myVar = setInterval(myTimer ,1000);
	    function myTimer() {
	        var d = new Date(), displayDate;
	       if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
	          displayDate = d.toLocaleTimeString('pt-BR', {timeZone: 'America/Fortaleza'});
	       } else {
	          displayDate = d.toLocaleTimeString('pt-BR', {timeZone: 'America/Fortaleza'});
	       }
	          document.getElementById("demo").innerHTML = displayDate;
	    }
	</script>
	</head>
	<body>
		<div id="fh5co-wrapper">
			<div id="fh5co-page">
				<div id="fh5co-header">
					<header id="fh5co-header-section">
						<div class="container">
							<div class="nav-header" style="margin: 0; padding: 0;">
								<h1 id="fh5co-logo" style="margin: 0.5em 0 0.5em 0;"><a href="index.php" style="color: gold;font-family: Agency FB, calibri;"><img src="icon.png" style="width: 40px;height: 40px;vertical-align: top;">FIT <span style="font-weight: bold;color: white;">LINE</span></a></h1>
								<!-- START #fh5co-menu-wrap -->
								<nav id="fh5co-menu-wrap" role="navigation">
									<ul class="sf-menu" id="fh5co-primary-menu">
										<li><div id="demo" style="color: white; font-family: century gothic; font-size: 1em;font-weight: bold;margin: 0; padding: 0;"></div><label style="color: white; font-family: century gothic; font-size: 1em;font-weight: bold;margin: 0; padding: 0;"><?php echo date('d/m/Y');?></label></li>
									</ul>
								</nav>
							</div>
						</div>
					</header>		
				</div>
				<div class="fh5co-hero" style="max-height: 60em;">

					<div class="modal fade" id="entradap" data-backdrop="static">
				        <div class="modal-dialog">
				          <div class="modal-content">
				            <form method="post" action="" enctype="multipart/form-data">
				                <div class="modal-header">
				                  <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				                  <h3 class="modal-title" id='nomec'></h3>
				                </div>
				                <div class="modal-body" id='info'>
				              		<!--Informaçãoes do cliente-->
				                	<input type="hidden" name="acao" value="destruir">
				                </div>
				                <div class="modal-footer">
				                  <button type="submit" class="btn btn-primary yellow" style="color: #333;" id="ok">OK</button>
				                  <button type="button" class="btn btn-primary black" style="color: white;" id="cancelares">Cancelar</button>  
				                  <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button> -->
				                </div>
				            </form>
				          </div>
				        </div>
				    </div>

					<div class="fh5co-overlay"></div>
					<div class="fh5co-cover" data-stellar-background-ratio="0.5" style="background-image: url(images/home-image.jpg); max-height: 60em;">
						<div class="desc animate-box">
							<div class="container">
								<div class="row">
									<div class="col-md-7" style="padding: 1%;">
										<h2 style="font-size: 2.5em;">Insira sua <b>Chave de Acesso</b> <i class="material-icons" id="icbiometric" style="vertical-align: middle;">info_outline</i></h2>
										<!-- <p><span>Created with <i class="icon-heart2"></i> by the fine folks at <a href="http://freehtml5.co" class="fh5co-site-name">FreeHTML5.co</a></span></p> -->
										<!-- <span><a class="btn btn-primary" href="#">Iniciar o Dia</a></span> -->
									</div>
									<div class="col-md-5" style="float:right;max-height: 440px;overflow-y: hidden;">
											<input type="number" name="cliente" placeholder="Chave de Acesso" title="Chave de Acesso" id="cliente" autofocus>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<!-- <script src="js/bootstrap.min.js"></script> -->
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Stellar -->
	<script src="js/jquery.stellar.min.js"></script>
	<!-- Superfish -->
	<script src="js/hoverIntent.js"></script>
	<script src="js/superfish.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
	<!-- Main JS (Do not remove) -->
	<!--Permite abrir modals pelo javascript-->
	<script type="text/javascript" src="js/bootstrap.min.js"></script> 
	<script src="js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#cliente").on("keypress", function(){
				if(e.which == 13){
					cliente = $("#cliente").val();
					if(cliente != ""){
						$.ajax({
							method: 'post',
							url: 'ps_clientes.php',
							data: {'cliente': cliente},
							success:function(data){
								if(data != "entrada" && data != "saida"){
									alert(data);
								}else{
									if(data == "entrada"){
										$('#nomec').html("<i class='material-icons'>check</i><label>Bem vindo(a),</label> ".$cc['nome']);
										$('#info').append("<input type=hidden name=clie id=clidel>".$anexos);
										$('#entradap').modal();
									}
								}
							}
						});
					}
				}
			});
		});
	</script>
	</body>
</html>

