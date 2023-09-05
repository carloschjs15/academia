<?php
	session_start();
	include "conexao.inc.php";
	setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' ); 
    date_default_timezone_set( 'America/Fortaleza' );
    $r = $conn->query("SELECT * FROM historico ORDER BY id DESC");
    $c = 0;
    while ($linha = $r->fetch(PDO::FETCH_ASSOC)) {
    	if($c == 500){
    		$id = $linha['id'];
    		break;
    	}
    	$c++;
    }

    $m = $conn->exec("DELETE FROM historico WHERE id < '$id'");
    // echo "'$id'";
    // if($m){
    // 	echo "<script>alert('".$id."');</script>";
    // }
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
	<!-- <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/> -->
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
	</head>
	<body style="background-color: black;">
		<footer>
			<div id="footer" style="background-color: black;">
				<div class="container">
					<div class="row">
						<table class="table">
							<thead>
								<th>Data</th>
								<th>Chegada</th>
								<th>Saída</th>
								<th>Aluno <!-- <a href="" style="float: right">Geral</label> --><label style="float: right;">Total de pessoas (<?php $contagem = $conn->query("SELECT * FROM historico WHERE data = '".date('Y-m-d')."' GROUP BY cliente");echo date('d/m/Y')."): ".($contagem->rowCount()); ?>  </label></th>
							</thead>
							<tbody>
								<!-- Colocar histórico de entrada ou saída -->
								<?php $entradasaida = $conn->query("SELECT * FROM historico GROUP BY cliente, data ORDER BY id DESC");
								if($entradasaida->rowCount()>0){
									while ($es = $entradasaida->fetch(PDO::FETCH_ASSOC)) {
										if($es['hora_saida']=="00:00:00"){
											$horasaida = "---";
										}else{
											$horasaida = $es['hora_saida'];	
										}
										$es['data'] = DateTime::createFromFormat('Y-m-d',$es['data'])->format('d/m/Y');
										$consultanomec = $conn->query("SELECT * FROM cliente WHERE id = '".$es['cliente']."'");
										while($cnc = $consultanomec->fetch(PDO::FETCH_ASSOC)){
											echo "<tr><td>".$es['data']."</td><td>".$es['hora_entrada']."</td><td>".$horasaida."</td><td>".$cnc['nome']."</td></tr>";
										}
									}
								}else{
									echo "<tr><td>Sem </td><td>entradas</td><td>ou saídas</td><td>de clientes!</td><tr>";
								}
								?>

							</tbody>
						</table>
					</div>
				</div>
					
					<!-- <div class="row copy-right">
						<div class="col-md-6 col-md-offset-3 text-center">
							<p class="fh5co-social-icons">
								<a href="#"><i class="icon-twitter2"></i></a>
								<a href="#"><i class="icon-facebook2"></i></a>
								<a href="#"><i class="icon-instagram"></i></a>
								<a href="#"><i class="icon-dribbble2"></i></a>
								<a href="#"><i class="icon-youtube"></i></a>
							</p>
							<p>Copyright 2016 Free Html5 <a href="#">Fitness</a>. All Rights Reserved. <br>Made with <i class="icon-heart3"></i> by <a href="http://freehtml5.co/" target="_blank">Freehtml5.co</a> / Demo Images: <a href="https://unsplash.com/" target="_blank">Unsplash</a></p>
						</div>
					</div> -->
			</div>
		</footer>
	<!-- END fh5co-wrapper -->

	<!-- jQuery -->


	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Stellar -->
	<script src="js/jquery.stellar.min.js"></script>
	<!-- Superfish -->
	<script src="js/hoverIntent.js"></script>
	<script src="js/superfish.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
	<!-- Main JS (Do not remove) -->
	<script src="js/main.js"></script>
	</body>
</html>