<?php
	session_start();
	include "conexao.inc.php";
	setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' ); 
    date_default_timezone_set( 'America/Fortaleza' );
    //$deleta_datasa = $conn->exec("DELETE FROM pagamentosd WHERE data < '".date('Y-m-d')."'");
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

	<link rel="shortcut icon" href="icon.png">
	<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
	<!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Datatables CSS -->
    <link href="tabelas/datatables-plugins/datatables.bootstrap.css" rel="stylesheet">

    <!-- Datatables Responsive CSS -->
    <link href="tabelas/datatables-responsive/datatables.responsive.css" rel="stylesheet">

	<link href="apis/icon.css" rel="stylesheet">

	<link href='css/roboto.css' rel='stylesheet' type='text/css'>
	
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
	<body style="background-color: black;">
		<div align="center">
			<label style="color: white; font-weight: bolder;font-family: century gothic; font-size: 1.2em;">De:</label> <input type="date" id = "de" name = "de" style="border-radius: 5px;" value = '<?php echo date('Y-01-01')?>'> <label style="color: white; font-weight: bolder;font-family: century gothic; font-size: 1.2em;"> Até:</label> <input style="border-radius: 5px;" id = "ate" type="date" name = "ate" value = '<?php echo date('Y-m-d')?>'> <button class="btn btn-primary white" style="color: black; font-family: century gothic;" id="consulta">Consultar</button>
		</div>
		<footer>
			<div id="footer" style="background-color: black;">
				<div class="container">
					<div class="row" id="container">
						<table width="100%" class="table table-hover" id="datatables-example">
							<thead class="thead-default">
								<th>Cliente</th>
								<th>Valor <label style="float: right;">Valor Total: <?php $vt = 0;$contagem = $conn->query("SELECT SUM(valor) FROM pagamentosd WHERE data = '".date('Y-m-d')."'");while($linha = $contagem->fetch(PDO::FETCH_ASSOC)){echo $linha['SUM(valor)'];} ?>  </label></th>
							</thead>
							<tbody>
								<!-- Colocar histórico de entrada ou saída -->
								<?php $entradasaida = $conn->query("SELECT * FROM pagamentosd WHERE data = '".date('Y-m-d')."' ORDER BY id DESC");
								if($entradasaida->rowCount()>0){
									while ($es = $entradasaida->fetch(PDO::FETCH_ASSOC)) {
										$valor_pag = $conn->query("SELECT * FROM pagamento WHERE id_cliente = '".$es['id_cliente']."'");
										while($val = $valor_pag->fetch(PDO::FETCH_ASSOC)){
											$valorp = $val['valor'];
										}
										$valores = $conn->query("SELECT * FROM cliente WHERE id = '".$es['id_cliente']."'");
										while ($linha = $valores->fetch(PDO::FETCH_ASSOC)) {
											echo "<tr class='odd gradeX'>
													<td class='center'>".$linha['nome']."</td>
													<td class='center'>".$es['valor']."</td>
												</tr>";
										}
									}
								}else{
									echo "<tr><td>Sem Pagamentos</td><td>Hoje!</td><tr>";
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

	<!-- Datatables JavaScript -->
    <script src="tabelas/datatables/js/jquery.datatables.min.js"></script>
    <script src="tabelas/datatables-plugins/datatables.bootstrap.min.js"></script>
    <script src="tabelas/datatables-responsive/datatables.responsive.js"></script>

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
	<script type="text/javascript">
		$(document).ready(function(){
			$('#datatables-example').DataTable({
            	responsive: true
        	});
		});
		$(document).on("click", "body div #consulta",function(){
			$.ajax({
				url: 'gera_tabela_pag.php',
				method: 'post',
				data: {'de':$('#de').val(), 'ate':$('#ate').val(),},
				success:function(data){
					$('body footer #footer #container').html('');
					$('body footer #footer #container').html(data);
					$('#datatables-example').DataTable({
		            	responsive: true
		        	});
				}
			});
		});
	</script>
	</body>
</html>