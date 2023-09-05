<?php 
	// Os dois header abaixo inicia a página sem o cache 
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sun, 11 Apr 2010 05:00:00 GMT");
	session_start();
	require "conexao.inc.php";
	setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' ); 
    date_default_timezone_set( 'America/Fortaleza' );
    $data_hoje = date('Y-m-d');
	if(!isset($_SESSION['ativo'])){
		// Definir horário de saída do cliente que será setado, caso ele não registre sua saída
		$verificaexiste = $conn->query("SELECT * FROM historico WHERE data!='".date('Y-m-d')."' and hora_saida = '00:00:00'");
		if($verificaexiste->rowCount()>0){
			$fecha = $conn->exec("UPDATE historico SET hora_saida = '21:30:00' WHERE data!='".date('Y-m-d')."' and hora_saida = '00:00:00'");
			if($fecha){
				$_SESSION['ativo'] = 0;
			}else{
				echo "<script>alert('Erro, sistema necessita de manutenção!');</script>";
			}
		}
	}
	if(isset($_POST['acao'])){
		switch ($_POST['acao']) {
			case 'passar':
					$cliente = $_POST['cliente'];
					$vhistorico = $conn->query("SELECT * FROM historico WHERE cliente = '$cliente' and hora_saida = '00:00:00'");
					if($vhistorico->rowCount()>0){
						$dataanterior = date('Y-'.(date('m')-1).'-d');
						if($dataanterior>date('Y-m-d')){
							$dataanterior = date((date('Y')-1).'-m-d');
						}
						$pegamaiorid = $conn->query("SELECT MAX(id) FROM historico WHERE data >= '$dataanterior'");
						while ($pmd = $pegamaiorid->fetch(PDO::FETCH_ASSOC)) {
							$id = $pmd['MAX(id)']+1;
						}
						// echo "<script>alert('".$id."');</script>";
						$saidac = $conn->exec("UPDATE historico SET id = '$id', hora_saida = '".date('H:i:s')."' WHERE cliente = '$cliente' and hora_saida='00:00:00'");
						if($saidac){
							$_SESSION['saidaa'] = $cliente;
						}else{
							echo "<script>alert('Saída manual negada, manutenção necessária!'); window.location = 'ps_clientes.php';</script>";
						}
					}else{
						$verifica_existe = $conn->query("SELECT * FROM cliente WHERE id = '$cliente'");
						if($verifica_existe->rowCount() > 0){
							$datae = date('Y-m-d');
							$horae = date('H:i:s'); // Pega a hora,minutos e segundos atual
							$horas = '00:00:00';//Define a hora, minuto e segundos
							$dataanterior = date('Y-'.(date('m')-1).'-d');
							if($dataanterior>date('Y-m-d')){
								$dataanterior = date((date('Y')-1).'-m-d');
							}
							$pegamaiorid = $conn->query("SELECT MAX(id) FROM historico WHERE data>='$dataanterior'");
							while ($pmd = $pegamaiorid->fetch(PDO::FETCH_ASSOC)) {
								$id = $pmd['MAX(id)']+1;
							}
							$entradac = $conn->exec("INSERT INTO historico VALUES('$id','".$datae."','".$horae."','".$horas."','".$cliente."')");
							//echo "<script>alert('".$datae.", ".$horae.", ".$horas.", ".$cliente."');</script>";
							if($entradac){
								//sleep(0.5); // Espera 1 segundo o tempo
								$_SESSION['passagema'] = $cliente;
							}else{
								echo "<script>alert('Entrada negada, manutenção necessária!, Erro: ".$datae.", ".$horae.", ".$horas.", ".$cliente."); window.location = 'ps_clientes.php';</script>";
							}
						}else{
							echo "<script>alert('Erro, cliente inexistente!'); window.location = 'ps_clientes.php';</script>";
						}
					}
				break;

			default:
				echo "<script>alert('Erro, manutenção necessária!'); window.location = 'ps_clientes.php';</script>";
				break;
		}
	}
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
	</style>
	</head>
	<body>
		<script type="text/javascript">
			var myVar = setInterval(myTimer ,1000);
		    function myTimer() {
		        var d = new Date(), displayDate;
		       if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
		          displayDate = d.toLocaleTimeString('pt-BR');
		       } else {
		          displayDate = d.toLocaleTimeString('pt-BR', {timeZone: 'America/Belem'});
		       }
		          document.getElementById("demo").innerHTML = displayDate;
		    }
		</script>
		<div id="fh5co-wrapper" style="height:55em;">
			<div id="fh5co-page">
				<div id="fh5co-header">
				<header id="fh5co-header-section">
					<div class="container">
						<div class="nav-header" style="margin-bottom: 1em; margin-top: 1em;">
							<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle"><i></i></a>
							<h1 id="fh5co-logo"><a href="index.php" style="color: gold;font-family: Agency FB, calibri;"><img src="icon.png" style="width: 40px;height: 40px;vertical-align: top;">FIT <span style="font-weight: bold;color: white;">LINE</span></a></h1>
							<!-- START #fh5co-menu-wrap -->
							<nav id="fh5co-menu-wrap" role="navigation" style="margin: 0;padding: 0;border:0;">
								<ul class="sf-menu" id="fh5co-primary-menu">
									<li id="demo" style="font-weight: lighter;color: white;font-size: 1em;margin-right: 1em;"></li>
									<li style="font-weight: lighter;color: white;font-size: 1em;margin-left: 1em;"><?php echo date('d/m/Y');?></li>
								</ul>
							</nav>
						</div>
					</div>
				</header>		
				</div>
				<div class="modal fade" id="entradap" data-backdrop="static">
		        <div class="modal-dialog">
		          <div class="modal-content">
		            <form method="post" action="ps_c.php" enctype="multipart/form-data">
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
			<!-- end:fh5co-header -->
				<div class="fh5co-hero" style="height:55em;">
				<div class="fh5co-overlay" style="height:55em;"></div>
					<div class="fh5co-cover" data-stellar-background-ratio="0.5" style="height:55em;background-image: url(images/home-image.jpg);">
						<div class="desc animate-box">
							<div class="container">
								<div class="row">
									<div class="col-md-7" style="padding: 1%;">
										<h2 style="font-size: 2em;">Insira sua <b>Chave de Acesso</b> <i class="material-icons" id="icbiometric">info_outline</i></h2>
										<!-- <p><span>Created with <i class="icon-heart2"></i> by the fine folks at <a href="http://freehtml5.co" class="fh5co-site-name">FreeHTML5.co</a></span></p> -->
										<!-- <span><a class="btn btn-primary" href="#">Iniciar o Dia</a></span> -->
									</div>
									<div class="col-md-5" style="float:right;max-height: 440px;overflow-y: hidden;">
										<form method="post" action=""><input type="hidden" name="acao" value="passar"><input type="number" name="cliente" placeholder="Chave de Acesso" title="Chave de Acesso" style="padding: 0.5em; font-size: 1.5em;width: 15em; font-weight: lighter;color: black; font-family: century gothic, Arial;" required autofocus></form>
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
		$(document).on("click","#cancelares",function(){
			$.ajax({
				url: 'cancela_registro.php',
				method: 'post',
				data: {'clidel': $('#clidel').attr('value')},
				success:function(data){
					window.location = 'ps_clientes.php';
				}
			});
		});
		$(document).keypress(function(e) {
    		if($('#entradap').attr('class') == "modal fade in"){
    			if(e.which == 13){
	    			$('#ok').click();
	    		}else if(e.which == 44){
	    			$('#cancelares').click();
				}
    		}
		});
	</script>
	<?php if(isset($_SESSION['passagema'])){
			//echo "<script>alert('Deu certo');</script>";
			$consultacliente = $conn->query("SELECT * FROM cliente WHERE id = '".$_SESSION['passagema']."'");
			// Só executa uma vez
			while ($cc = $consultacliente->fetch(PDO::FETCH_ASSOC)) {
				$consultapac = $conn->query("SELECT * FROM pagamento WHERE id_cliente = '".$_SESSION['passagema']."'");
				while ($pagamento = $consultapac->fetch(PDO::FETCH_ASSOC)) {
					$anexos = "";
					if($pagamento['data']<=date('Y-m-d')){
						$anexos .= "<label style=color:red>Por favor, procure a recepção!</label>";
					}
					// if($cc['anexo_mensal']=='0000-00-00'){
					// 	$anexos .= "<label style=color:gold>Anexo Mensal: Pendente</label>";
					// }
					// if($cc['anexo_trimestral']=='0000-00-00'){
					// 	$anexos .= "<label style=color:gold>Anexo Trimestral: Pendente</label>";
					// }
					// if($cc['anexo_reserva']=='0000-00-00'){
					// 	$anexos .= "<label style=color:gold>Anexo Reserva: Pendente</label>";
					// }
					$pagamento['data'] = DateTime::createFromFormat('Y-m-d',$pagamento['data'])->format('d/m/Y');
					echo "<script>
							$('#nomec').html('<i class=material-icons>check</i><label>Bem vindo(a),</label> ".$cc['nome']."');
							$('#info').append('<input type=hidden name=clie value=".$_SESSION['passagema']." id=clidel>".$anexos."');
							$('#entradap').modal();
						</script>";	
				}
			}
		}
		if(isset($_SESSION['saidaa'])){
			$consultacliente = $conn->query("SELECT * FROM cliente WHERE id = '".$_SESSION['saidaa']."'");
			// Só executa uma vez
			// echo "<script>alert('Deu certo');</script>";
			while ($cc = $consultacliente->fetch(PDO::FETCH_ASSOC)) {
				$consultapac = $conn->query("SELECT * FROM pagamento WHERE id_cliente = '".$_SESSION['saidaa']."'");
				while ($pagamento = $consultapac->fetch(PDO::FETCH_ASSOC)) {
					$anexos = "";
					if($pagamento['data']<=date('Y-m-d')){
						$anexos .= "<label style=color:red>Por favor, procure a recepção!</label>";
					}
					// if($cc['anexo_mensal']=='0000-00-00'){
					// 	$anexos .= "<label style=color:gold>Anexo Mensal: Pendente</label>";
					// }
					// if($cc['anexo_trimestral']=='0000-00-00'){
					// 	$anexos .= "<label style=color:gold>Anexo Trimestral: Pendente</label>";
					// }
					// if($cc['anexo_reserva']=='0000-00-00'){
					// 	$anexos .= "<label style=color:gold>Anexo Reserva: Pendente</label>";
					// }
					//echo "<script>alert('Deu certo');</script>";
					$pagamento['data'] = DateTime::createFromFormat('Y-m-d',$pagamento['data'])->format('d/m/Y');
					echo "<script>
							$('#nomec').html('<i class=material-icons>check</i><label>Volte sempre,</label> ".$cc['nome']."');
							$('#info').append('<input type=hidden name=clie value=".$_SESSION['saidaa']." id=clidel>".$anexos."');
							$('#entradap').modal();
						</script>";	
				}
			}
		}
		?>
	</body>
</html>

