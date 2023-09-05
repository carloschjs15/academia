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
	// Backup da base de dados --password=8759
	system("del C:\\xampp\\htdocs\\fitline\\academia.sql");
	system("C:/xampp/mysql/bin/mysqldump.exe --host=localhost --user=root --databases academia > C:/xampp/htdocs/fitline/academia.sql");

	if(isset($_POST['acao'])){
		switch ($_POST['acao']) {
			case 'anexar':
				$nome_pasta = $_POST['pasta'];
				$mudouat = $mudouar = $mudouam = '0000-00-00';
				if((!empty($_FILES['am']['name']))){ // Verifica se foi enviando imagem
						// Pasta onde o arquivo vai ser salvo
						$_UP['pasta'] = 'usuarios/'.$nome_pasta.'/';
						// Tamanho máximo do arquivo (em Bytes)
						$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
						// Array com as extensões permitidas
						$_UP['extensoes'] = array('pdf'); // Desimportando qual das 3 extensões sejam enviadas no fim sempre a imagem convertida será .jpg
						// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
						$_UP['renomeia'] = true;
						// Array com os tipos de erros de upload do PHP
						$_UP['erros'][0] = 'Não houve erro';
						$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
						$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
						$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
						$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
						// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
						if ($_FILES['am']['error'] != 0) {
							die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['am']['error']]);
							exit; // Para a execução do script
						}
						// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
						// Faz a verificação da extensão do arquivo

						// $ponto = '.';
						// $xxx = $_FILES['am']['name'];
						// $extensao = strtolower(end(explode($ponto, $xxx)));
						// if (array_search($extensao, $_UP['extensoes']) === false) {
						//   echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif";
						//   exit;
						// }
						// Faz a verificação do tamanho do arquivo
						if ($_UP['tamanho'] < $_FILES['am']['size']) {
							echo "<script>alert('O imagem enviada é muito grande, envie imagens de até 2Mb.');</script>";
							exit;
						}
						// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
						// Primeiro verifica se deve trocar o nome do arquivo
						if ($_UP['renomeia'] == true) {
							// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
							$nome_final = 'am.pdf';
						} else {
							// Mantém o nome original do arquivo
							$nome_final = $_FILES['am']['name'];
						}

						// Depois verifica se é possível mover o arquivo para a pasta escolhida
						if (move_uploaded_file($_FILES['am']['tmp_name'], $_UP['pasta'] . $nome_final)) {
							// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
							// echo("<script>alert('Imagem enviada com sucesso!');</script>");
							if((date('m')+1)>12){
								if(date('M-d-Y',mktime(0,0,0,1,date('d'),(date('Y')+1)))){
									$mudouam = date((date('Y')+1).'-1-d');	
								}else{
									$mudouam = date((date('Y')+1).'-1-t');
								}
							}else{
								if(date('M-d-Y',mktime(0,0,0,(date('m')+1),date('d'),(date('Y'))))){
									$mudouam = date('Y-'.(date('m')+1).'-d');
								}else{
									$mudouam = date('Y-'.(date('m')+1).'-t');
								}
							}
						} else {
							// Não foi possível fazer o upload, provavelmente a pasta está incorreta
							echo "<script>alert('Não foi possível enviar a imagem, tente novamente')</script>";
						}
					}
					if((!empty($_FILES['at']['name']))){ // Verifica se foi enviando imagem
						// Pasta onde o arquivo vai ser salvo
						$_UP['pasta'] = 'usuarios/'.$nome_pasta.'/';
						// Tamanho máximo do arquivo (em Bytes)
						$_UP['tamanho'] = 1024 * 1024 *2; // 2Mb
						// Array com as extensões permitidas
						$_UP['extensoes'] = array('pdf'); // Desimportando qual das 3 extensões sejam enviadas no fim sempre a imagem convertida será .jpg
						// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
						$_UP['renomeia'] = true;
						// Array com os tipos de erros de upload do PHP
						$_UP['erros'][0] = 'Não houve erro';
						$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
						$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
						$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
						$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
						// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
						if ($_FILES['at']['error'] != 0) {
							die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['at']['error']]);
							exit; // Para a execução do script
						}
						// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
						// Faz a verificação da extensão do arquivo

						// $ponto = '.';
						// $xxx = $_FILES['at']['name'];
						// $extensao = strtolower(end(explode($ponto, $xxx)));
						// if (array_search($extensao, $_UP['extensoes']) === false) {
						//   echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif";
						//   exit;
						// }
						// Faz a verificação do tamanho do arquivo
						if ($_UP['tamanho'] < $_FILES['at']['size']) {
							echo "<script>alert('O imagem enviada é muito grande, envie imagens de até 2Mb.');</script>";
							exit;
						}
						// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
						// Primeiro verifica se deve trocar o nome do arquivo
						if ($_UP['renomeia'] == true) {
							// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
							$nome_final = 'at.pdf';
						} else {
							// Mantém o nome original do arquivo
							$nome_final = $_FILES['at']['name'];
						}

						// Depois verifica se é possível mover o arquivo para a pasta escolhida
						if (move_uploaded_file($_FILES['at']['tmp_name'], $_UP['pasta'] . $nome_final)) {
							// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
							// echo("<script>alert('Imagem enviada com sucesso!');</script>");
							if((date('m')+3)>12){
								if(date('M-d-Y',mktime(0,0,0,((date('m')+3)-12),date('d'),(date('Y')+1)))){
									$mudouat = date((date('Y')+1).'-'.((date('m')+3)-12).'-d');	
								}else{
									$mudouat = date((date('Y')+1).'-'.((date('m')+3)-12).'-t');
								}
							}else{
								if(date('M-d-Y',mktime(0,0,0,(date('m')+3),date('d'),(date('Y'))))){
									$mudouat = date('Y-'.(date('m')+3).'-d');
								}else{
									$mudouat = date('Y-'.(date('m')+3).'-t');
								}
							}
						} else {
							// Não foi possível fazer o upload, provavelmente a pasta está incorreta
							echo "<script>alert('Não foi possível enviar a imagem, tente novamente')</script>";
						}
					}
					if((!empty($_FILES['ar']['name']))){ // Verifica se foi enviando imagem
						// Pasta onde o arquivo vai ser salvo
						$_UP['pasta'] = 'usuarios/'.$nome_pasta.'/';
						// Tamanho máximo do arquivo (em Bytes)
						$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
						// Array com as extensões permitidas
						$_UP['extensoes'] = array('pdf'); // Desimportando qual das 3 extensões sejam enviadas no fim sempre a imagem convertida será .pdf
						// Renomeia o arquivo? (Se true, o arquivo será salvo como .pdf e um nome único)
						$_UP['renomeia'] = true;
						// Array com os tipos de erros de upload do PHP
						$_UP['erros'][0] = 'Não houve erro';
						$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
						$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
						$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
						$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
						// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
						if ($_FILES['ar']['error'] != 0) {
							die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['ar']['error']]);
							exit; // Para a execução do script
						}
						// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
						// Faz a verificação da extensão do arquivo

						// $ponto = '.';
						// $xxx = $_FILES['ar']['name'];
						// $extensao = strtolower(end(explode($ponto, $xxx)));
						// if (array_search($extensao, $_UP['extensoes']) === false) {
						//   echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif";
						//   exit;
						// }
						// Faz a verificação do tamanho do arquivo
						if ($_UP['tamanho'] < $_FILES['ar']['size']) {
							echo "<script>alert('O imagem enviada é muito grande, envie imagens de até 2Mb.');</script>";
							exit;
						}
						// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
						// Primeiro verifica se deve trocar o nome do arquivo
						if ($_UP['renomeia'] == true) {
							// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .pdf
							$nome_final = 'ar.pdf';
						} else {
							// Mantém o nome original do arquivo
							$nome_final = $_FILES['ar']['name'];
						}

						// Depois verifica se é possível mover o arquivo para a pasta escolhida
						if (move_uploaded_file($_FILES['ar']['tmp_name'], $_UP['pasta'] . $nome_final)) {
							// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
							// echo("<script>alert('Imagem enviada com sucesso!');</script>");
							$mudouar = date((date('Y')+1).'-m-d');
						} else {
							// Não foi possível fazer o upload, provavelmente a pasta está incorreta
							echo "<script>alert('Não foi possível enviar a imagem, tente novamente')</script>";
						}
					}
					$sql = $conn->exec("UPDATE cliente SET anexo_mensal = '$mudouam', anexo_trimestral = '$mudouat', anexo_reserva = '$mudouar' WHERE perfil = '$nome_pasta'");
					if($sql){
						echo "<script>alert('Anexos adicionado ao cliente!'); window.location = 'index.php';</script>";
					}else{
						if($mudouam==0 && $mudouat==0 && $mudouar==0){
							echo "<script>alert('Nenhum anexo foi adicionado ao cliente!'); window.location = 'index.php';</script>";	
						}
						echo "<script>alert('Erro ao adicionar anexos ao cliente!'); window.location = 'index.php';</script>";
					}
				break;

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
							echo "<script>alert('Saída manual negada, manutenção necessária!'); window.location = 'index.php';</script>";
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
								echo "<script>alert('Entrada negada, manutenção necessária!, Erro: ".$datae.", ".$horae.", ".$horas.", ".$cliente."); window.location = 'index.php';</script>";
							}
						}else{
							echo "<script>alert('Erro, cliente inexistente!'); window.location = 'index.php';</script>";
						}
					}
				break;

			case 'deleta_registro':
				$id = $_POST['registro'];
				$sql = $conn->exec("DELETE FROM historico WHERE id = '$id'");
				if($sql){
					echo "<script>alert('Registro deletado com sucesso!'); window.location = 'index.php';</script>";
				}else{
					echo "<script>alert('Erro ao deletar registro!'); window.location = 'index.php';</script>";
				}
				break;

			default:
				echo "<script>alert('Erro, manutenção necessária!'); window.location = 'index.php';</script>";
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
		<div id="fh5co-wrapper">
		<div id="fh5co-page">
		<div id="fh5co-header">
			<header id="fh5co-header-section">
				<div class="container">
					<div class="nav-header">
						<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle"><i></i></a>
						<h1 id="fh5co-logo"><a href="index.php" style="color: gold;font-family: Agency FB, calibri;"><img src="icon.png" style="width: 40px;height: 40px;vertical-align: top;">FIT <span style="font-weight: bold;color: white;">LINE</span></a></h1>
						<!-- START #fh5co-menu-wrap -->
						<nav id="fh5co-menu-wrap" role="navigation">
							<ul class="sf-menu" id="fh5co-primary-menu">
								<li class="active">
									<a href="index.php">Início</a>
								</li>
								<!-- <li>
									<a href="classes.html" class="fh5co-sub-ddown">Classes</a>
									 <ul class="fh5co-sub-menu">
									 	<li><a href="left-sidebar.html">Web Development</a></li>
									 	<li><a href="right-sidebar.html">Branding &amp; Identity</a></li>
										<li>
											<a href="#" class="fh5co-sub-ddown">Free HTML5</a>
											<ul class="fh5co-sub-menu">
												<li><a href="http://freehtml5.co/preview/?item=build-free-html5-bootstrap-template" target="_blank">Build</a></li>
												<li><a href="http://freehtml5.co/preview/?item=work-free-html5-template-bootstrap" target="_blank">Work</a></li>
												<li><a href="http://freehtml5.co/preview/?item=light-free-html5-template-bootstrap" target="_blank">Light</a></li>
												<li><a href="http://freehtml5.co/preview/?item=relic-free-html5-template-using-bootstrap" target="_blank">Relic</a></li>
												<li><a href="http://freehtml5.co/preview/?item=display-free-html5-template-using-bootstrap" target="_blank">Display</a></li>
												<li><a href="http://freehtml5.co/preview/?item=sprint-free-html5-template-bootstrap" target="_blank">Sprint</a></li>
											</ul>
										</li>
										<li><a href="#">UI Animation</a></li>
										<li><a href="#">Copywriting</a></li>
										<li><a href="#">Photography</a></li> 
									</ul>
								</li> -->
								<li>
									<a href="clientes.php">Clientes</a>
								</li>
								<li><a href="categorias.php">Categorias</a></li>
								<li><a href="promocoes.php">Promoções</a></li>
								<li><a href="pagamentos.php">Pagamentos</a></li>
								<li><a href="manual.php">Manual</a></li>
								<li id="minimiza"><i class="material-icons">expand_less</i></li>
								<li><form method="post" action=""><input type="hidden" name="acao" value="passar"><input type="text" name="cliente" placeholder="Chave de Acesso" title="Chave de Acesso" style="border-radius: 5px; padding: 2px; margin: 0; margin-left: 5px; font-size: 15px;" required autofocus></form></li>
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
		<div class="modal fade" id="emc" data-backdrop="static">
	        <div class="modal-dialog">
	          <div class="modal-content">
	            <form method="post" action="" enctype="multipart/form-data">
	                <div class="modal-header">
	                  <button type="button" class="close" data-dismiss="modal">&times;</button>
	                  <h4 class="modal-title">Entrada/Saída de Cliente</h4>
	                </div>
	                <div class="modal-body">
	                  <select name='cliente' required title="Selecione um cliente para que efetue a passagem manual.">
	                  <?php $consulta_categorias = $conn -> query("SELECT * FROM cliente ORDER BY nome");
	                  	while($categoria = $consulta_categorias->fetch(PDO::FETCH_ASSOC)){?>
	                  	<option value="<?php echo $categoria['id'];?>"><?php echo $categoria['nome'];?></option>
	                  	<?php }?>
	                  </select>
	                  <input type="hidden" name="acao" value="passar">
	                </div>
	                <div class="modal-footer">
	                  <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
	                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	                </div>
	            </form>
	          </div>
	        </div>
	    </div>
	   	<div class="modal fade" id="excluies" data-backdrop="static">
	        <div class="modal-dialog">
	          <div class="modal-content">
	            <form method="post" action="" enctype="multipart/form-data">
	                <div class="modal-header">
	                  <button type="button" class="close" data-dismiss="modal">&times;</button>
	                  <h4 class="modal-title">Deletar registro de entrada/saída</h4>
	                </div>
	                <div class="modal-body">
	                  <select name='registro' required title="Selecione um registro para que seja deletado.">
	                  <?php 
	                  // if(isset($_SESSION['saidaa'])){
	                  // 	echo "<script>alert('teste');</script>";
	                  // }
	                  $consulta_categorias = $conn -> query("SELECT * FROM historico ORDER BY id DESC");
	                  	while($categoria = $consulta_categorias->fetch(PDO::FETCH_ASSOC)){
	                  	$consultaclienteh = $conn->query("SELECT * FROM cliente WHERE id = '".$categoria['cliente']."'");
	                  	while($cch = $consultaclienteh->fetch(PDO::FETCH_ASSOC)){
	                  		if($categoria['hora_saida']=='00:00:00'){$categoria['hora_saida']='Não definido';}?>
	                  	<option value="<?php echo $categoria['id'];?>"><?php echo "(".$categoria['hora_entrada']." - ".$categoria['hora_saida'].") ".$cch['nome'];?></option>
	                  	<?php }}?>
	                  </select>
	                  <input type="hidden" name="acao" value="deleta_registro">
	                </div>
	                <div class="modal-footer">
	                  <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
	                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	                </div>
	            </form>
	          </div>
	        </div>
	    </div>
		<!-- end:fh5co-header -->
		<div class="fh5co-hero">
			<div class="fh5co-overlay"></div>
			<div class="fh5co-cover" data-stellar-background-ratio="0.5" style="background-image: url(images/home-image.jpg);">
						<?php $consulc = $conn->query("SELECT * FROM  cliente");
							if($consulc->rowCount()>0){?>
							<div class="fixed-action-btn vertical" style="margin-bottom: 10em;">
							    <a class="waves-effect waves-light btn-floating btn-large white" title="Configurações da Tela">
							      <i class="large material-icons" style="color: dimgray;">settings</i>
							    </a>
							  	<ul>
							      <li><a class="btn-floating waves-effect" style="background-color: rgba(255,255,255,0.5);" data-target="#emc" data-toggle="modal"><i class="material-icons" style="color:gold;">transfer_within_a_station</i></a></li>
							      <li><a class="btn-floating waves-effect" style="background-color: gold;" data-target="#excluies" data-toggle="modal"><i class="material-icons" style="color:rgba(255,255,255,0.5);">clear</i></a></li>
							      <!-- <li><a class="btn-floating waves-effect blue"><i class="material-icons">attach_file</i></a></li> -->
					    		</ul>
					 		</div>
							<?php }?>
							<div class="fixed-action-btn vertical" id="abt">
							    <a class="waves-effect waves-light btn-floating btn-large black" title="Abrir trava">
							      <i class="large material-icons" style="color: white;">lock_open</i>
							    </a>
					 		</div>
					 		<div class="fixed-action-btn vertical" id="backup_p">
							    <a class="waves-effect waves-light btn-floating btn-large yellow dark-2" href="academia.sql" download title="Abrir trava">
							      <i class="large material-icons" style="color: white;">usb</i>
							    </a>
					 		</div>
				<div class="desc animate-box">
					<div class="container">
						<div class="row">
							<div class="col-md-7" style="padding: 1%;">
								<h2 style="font-size: 45px;">Insira sua <b>Chave de Acesso</b> <i class="material-icons" id="icbiometric">info_outline</i></h2>
								<!-- <p><span>Created with <i class="icon-heart2"></i> by the fine folks at <a href="http://freehtml5.co" class="fh5co-site-name">FreeHTML5.co</a></span></p> -->
								<!-- <span><a class="btn btn-primary" href="#">Iniciar o Dia</a></span> -->
							</div>
							<div class="col-md-5" style="float:right;max-height: 440px;overflow-y: hidden;">
								<table class="table">
									<thead>
										<th>Data</th>
										<th>Chegada</th>
										<th>Saída</th>
										<th>Aluno <!-- <a href="" style="float: right">Geral</label> --><a href = "geral_es.php" target="_blank" class="large material-icons" style="float:right; color: yellow; font-size: 15px;">settings</a></th>
									</thead>
									<tbody>
										<!-- Colocar histórico de entrada ou saída -->
										<?php $c = 0;$entradasaida = $conn->query("SELECT * FROM historico ORDER BY id DESC");
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
													$c++;
													if($c > 9){
														break;
													}
												}
											}
										}else{
											echo "<tr><td>Sem </td><td>entradas</td><td>ou saídas</td><td>de clientes!</td><tr>";
										}
										?>

									</tbody>
								</table>
							</div>
							<?php
								$clientes = $conn->query("SELECT * FROM cliente WHERE anexo_mensal ='0000-00-00' or anexo_trimestral = '0000-00-00' or anexo_reserva = '0000-00-00' ORDER BY nome");
								if($clientes->rowCount()>0){
							?>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="anexos" data-backdrop="static">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                      <button type="button" class="close" id="fecha" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Ficha(s) do Cliente</h4>
                    </div>
                    <div class="modal-body">
                      
                    </div>
                    <div class="modal-footer">
                      <!-- <button type="button" class="btn btn-primary black pull-left" id="delc">Excluir</button>
                      <button type="button" class="btn btn-primary pull-left" id="btnfic" style="background-color: dimgray;">Ficha</button> -->
                      <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
                      <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel">Cancelar</button>
                    </div>
                </form>
              </div>
            </div>
        </div>
		<!-- end:fh5co-hero -->
		<!-- <div id="fh5co-schedule-section" class="fh5co-lightgray-section">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="heading-section text-center animate-box">
							<h2>Class Schedule</h2>
							<p>Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
					</div>
				</div>
				<div class="row animate-box">
					<div class="col-md-10 col-md-offset-1 text-center">
						<ul class="schedule">
							<li><a href="#" class="active" data-sched="sunday">Sunday</a></li>
							<li><a href="#" data-sched="monday">Monday</a></li>
							<li><a href="#" data-sched="tuesday">Tuesday</a></li>
							<li><a href="#" data-sched="wednesday">Wednesday</a></li>
							<li><a href="#" data-sched="thursday">Thursday</a></li>
							<li><a href="#" data-sched="monday">Monday</a></li>
							<li><a href="#" data-sched="saturday">Saturday</a></li>
						</ul>
					</div>
					<div class="row text-center">

						<div class="col-md-12 schedule-container">

							<div class="schedule-content active" data-day="sunday">
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-dumbell.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Body Building</h3>
										<span>John Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-yoga.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Yoga Programs</h3>
										<span>James Smith</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-cycling.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Cycling Program</h3>
										<span>Rita Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-boxing.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Boxing Fitness</h3>
										<span>John Dose</span>
									</div>
								</div>
							</div>


							<div class="schedule-content" data-day="monday">
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-yoga.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Yoga Programs</h3>
										<span>James Smith</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-dumbell.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Body Building</h3>
										<span>John Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-boxing.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Boxing Fitness</h3>
										<span>John Dose</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-cycling.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Cycling Program</h3>
										<span>Rita Doe</span>
									</div>
								</div>
								
							</div>


							<div class="schedule-content" data-day="tuesday">
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-dumbell.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Body Building</h3>
										<span>John Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-yoga.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Yoga Programs</h3>
										<span>James Smith</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-cycling.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Cycling Program</h3>
										<span>Rita Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-boxing.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Boxing Fitness</h3>
										<span>John Dose</span>
									</div>
								</div>
							</div>


							<div class="schedule-content" data-day="wednesday">
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-yoga.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Yoga Programs</h3>
										<span>James Smith</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-dumbell.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Body Building</h3>
										<span>John Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-boxing.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Boxing Fitness</h3>
										<span>John Dose</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-cycling.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Cycling Program</h3>
										<span>Rita Doe</span>
									</div>
								</div>
							</div>
	

							<div class="schedule-content" data-day="thursday">
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-dumbell.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Body Building</h3>
										<span>John Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-yoga.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Yoga Programs</h3>
										<span>James Smith</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-cycling.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Cycling Program</h3>
										<span>Rita Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-boxing.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Boxing Fitness</h3>
										<span>John Dose</span>
									</div>
								</div>
							</div>
		

							<div class="schedule-content" data-day="friday">
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-yoga.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Yoga Programs</h3>
										<span>James Smith</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-dumbell.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Body Building</h3>
										<span>John Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-boxing.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Boxing Fitness</h3>
										<span>John Dose</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-cycling.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Cycling Program</h3>
										<span>Rita Doe</span>
									</div>
								</div>
							</div>
		

							<div class="schedule-content" data-day="saturday">
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-dumbell.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Body Building</h3>
										<span>John Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-yoga.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Yoga Programs</h3>
										<span>James Smith</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-cycling.svg" alt="">
										<small>06AM-7AM</small>
										<h3>Cycling Program</h3>
										<span>Rita Doe</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="program program-schedule">
										<img src="images/fit-boxing.svg" alt="Cycling">
										<small>06AM-7AM</small>
										<h3>Boxing Fitness</h3>
										<span>John Dose</span>
									</div>
								</div>
							</div>
		
						</div>

						
					</div>
				</div>
			</div>
		</div>
		<div class="fh5co-parallax" style="background-image: url(images/home-image-3.jpg);" data-stellar-background-ratio="0.5">
			<div class="overlay"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0 text-center fh5co-table">
						<div class="fh5co-intro fh5co-table-cell animate-box">
							<h1 class="text-center">Commit To Be Fit</h1>
							<p>Made with love by the fine folks at <a href="http://freehtml5.co">FreeHTML5.co</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="fh5co-programs-section">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="heading-section text-center animate-box">
							<h2>Our Programs</h2>
							<p>Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-md-4 col-sm-6">
						<div class="program animate-box">
							<img src="images/fit-dumbell.svg" alt="Cycling">
							<h3>Body Combat</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							<span><a href="#" class="btn btn-default">Join Now</a></span>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="program animate-box">
							<img src="images/fit-yoga.svg" alt="">
							<h3>Yoga Programs</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							<span><a href="#" class="btn btn-default">Join Now</a></span>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="program animate-box">
							<img src="images/fit-cycling.svg" alt="">
							<h3>Cycling Program</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							<span><a href="#" class="btn btn-default">Join Now</a></span>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="program animate-box">
							<img src="images/fit-boxing.svg" alt="Cycling">
							<h3>Boxing Fitness</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							<span><a href="#" class="btn btn-default">Join Now</a></span>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="program animate-box">
							<img src="images/fit-swimming.svg" alt="">
							<h3>Swimming Program</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							<span><a href="#" class="btn btn-default">Join Now</a></span>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="program animate-box">
							<img src="images/fit-massage.svg" alt="">
							<h3>Massage</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
							<span><a href="#" class="btn btn-default">Join Now</a></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="fh5co-team-section" class="fh5co-lightgray-section">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="heading-section text-center animate-box">
							<h2>Meet Our Trainers</h2>
							<p>Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-md-4 col-sm-6">
						<div class="team-section-grid animate-box" style="background-image: url(images/trainer-1.jpg);">
							<div class="overlay-section">
								<div class="desc">
									<h3>John Doe</h3>
									<span>Body Trainer</span>
									<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
									<p class="fh5co-social-icons">
										<a href="#"><i class="icon-twitter-with-circle"></i></a>
										<a href="#"><i class="icon-facebook-with-circle"></i></a>
										<a href="#"><i class="icon-instagram-with-circle"></i></a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="team-section-grid animate-box" style="background-image: url(images/trainer-2.jpg);">
							<div class="overlay-section">
								<div class="desc">
									<h3>James Smith</h3>
									<span>Swimming Trainer</span>
									<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
									<p class="fh5co-social-icons">
										<a href="#"><i class="icon-twitter-with-circle"></i></a>
										<a href="#"><i class="icon-facebook-with-circle"></i></a>
										<a href="#"><i class="icon-instagram-with-circle"></i></a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="team-section-grid animate-box" style="background-image: url(images/trainer-3.jpg);">
							<div class="overlay-section">
								<div class="desc">
									<h3>John Doe</h3>
									<span>Chief Executive Officer</span>
									<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="fh5co-social-icons">
										<a href="#"><i class="icon-twitter-with-circle"></i></a>
										<a href="#"><i class="icon-facebook-with-circle"></i></a>
										<a href="#"><i class="icon-instagram-with-circle"></i></a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="team-section-grid animate-box" style="background-image: url(images/trainer-4.jpg);">
							<div class="overlay-section">
								<div class="desc">
									<h3>John Doe</h3>
									<span>Chief Executive Officer</span>
									<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="fh5co-social-icons">
										<a href="#"><i class="icon-twitter-with-circle"></i></a>
										<a href="#"><i class="icon-facebook-with-circle"></i></a>
										<a href="#"><i class="icon-instagram-with-circle"></i></a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="team-section-grid animate-box" style="background-image: url(images/trainer-5.jpg);">
							<div class="overlay-section">
								<div class="desc">
									<h3>John Doe</h3>
									<span>Chief Executive Officer</span>
									<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="fh5co-social-icons">
										<a href="#"><i class="icon-twitter-with-circle"></i></a>
										<a href="#"><i class="icon-facebook-with-circle"></i></a>
										<a href="#"><i class="icon-instagram-with-circle"></i></a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="team-section-grid animate-box" style="background-image: url(images/trainer-6.jpg);">
							<div class="overlay-section">
								<div class="desc">
									<h3>John Doe</h3>
									<span>Chief Executive Officer</span>
									<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="fh5co-social-icons">
										<a href="#"><i class="icon-twitter-with-circle"></i></a>
										<a href="#"><i class="icon-facebook-with-circle"></i></a>
										<a href="#"><i class="icon-instagram-with-circle"></i></a>
									</p>
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
		<div class="fh5co-parallax" style="background-image: url(images/home-image-2.jpg);" data-stellar-background-ratio="0.5">
			<div class="overlay"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3 col-md-pull-3 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0 fh5co-table">
						<div class="fh5co-intro fh5co-table-cell box-area">
							<div class="animate-box">
								<h1>Fitness Classes this summer</h1>
								<p>Pay now and get 25% Discount</p>
								<a href="#" class="btn btn-primary">Become A Member</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="fh5co-pricing-section" class="fh5co-pricing fh5co-lightgray-section">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="heading-section text-center animate-box">
							<h2>Pricing Plan</h2>
							<p>Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="pricing">
					<div class="col-md-3 animate-box">
						<div class="price-box animate-box">
							<h2 class="pricing-plan">Starter</h2>
							<div class="price"><sup class="currency">$</sup>9<small>/month</small></div>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
							<ul class="classes">
								<li>15 Cardio Classes</li>
								<li class="color">10 Swimming Lesson</li>
								<li>10 Yoga Classes</li>
								<li class="color">20 Aerobics</li>
								<li>10 Zumba Classes</li>
								<li class="color">5 Massage</li>
								<li>10 Body Building</li>
							</ul>
							<a href="#" class="btn btn-default">Select Plan</a>
						</div>
					</div>

					<div class="col-md-3 animate-box">
						<div class="price-box animate-box">
							<h2 class="pricing-plan">Basic</h2>
							<div class="price"><sup class="currency">$</sup>27<small>/month</small></div>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
							<ul class="classes">
								<li>15 Cardio Classes</li>
								<li class="color">10 Swimming Lesson</li>
								<li>10 Yoga Classes</li>
								<li class="color">20 Aerobics</li>
								<li>10 Zumba Classes</li>
								<li class="color">5 Massage</li>
								<li>10 Body Building</li>
							</ul>
							<a href="#" class="btn btn-default">Select Plan</a>
						</div>
					</div>

					<div class="col-md-3 animate-box">
						<div class="price-box animate-box popular">
							<h2 class="pricing-plan pricing-plan-offer">Pro <span>Best Offer</span></h2>
							<div class="price"><sup class="currency">$</sup>74<small>/month</small></div>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
							<ul class="classes">
								<li>15 Cardio Classes</li>
								<li class="color">10 Swimming Lesson</li>
								<li>10 Yoga Classes</li>
								<li class="color">20 Aerobics</li>
								<li>10 Zumba Classes</li>
								<li class="color">5 Massage</li>
								<li>10 Body Building</li>
							</ul>
							<a href="#" class="btn btn-select-plan btn-sm">Select Plan</a>
						</div>
					</div>

					<div class="col-md-3 animate-box">
						<div class="price-box animate-box">
							<h2 class="pricing-plan">Unlimited</h2>
							<div class="price"><sup class="currency">$</sup>140<small>/month</small></div>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
							<ul class="classes">
								<li>15 Cardio Classes</li>
								<li class="color">10 Swimming Lesson</li>
								<li>10 Yoga Classes</li>
								<li class="color">20 Aerobics</li>
								<li>10 Zumba Classes</li>
								<li class="color">5 Massage</li>
								<li>10 Body Building</li>
							</ul>
							<a href="#" class="btn btn-default">Select Plan</a>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
		
		<div id="fh5co-blog-section">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="col-md-12">
							<div class="heading-section animate-box">
								<h2>Recent from Blog</h2>
							</div>
						</div>
						<div class="col-md-12 col-md-offset-0">
							<div class="fh5co-blog animate-box">
								<div class="inner-post">
									<a href="#"><img class="img-responsive" src="images/blog-1.jpg" alt=""></a>
								</div>
								<div class="desc">
									<h3><a href=""#>Starting new session of body building this summer</a></h3>
									<span class="posted_by">Posted by: Admin</span>
									<span class="comment"><a href="">21<i class="icon-bubble22"></i></a></span>
									<p>Far far away, behind the word mountains</p>
									<a href="#" class="btn btn-default">Read More</a>
								</div> 
							</div>
						</div>
						<div class="col-md-12 col-md-offset-0">
							<div class="fh5co-blog animate-box">
								<div class="inner-post">
									<a href="#"><img class="img-responsive" src="images/blog-1.jpg" alt=""></a>
								</div>
								<div class="desc">
									<h3><a href=""#>Starting new session of body building this summer</a></h3>
									<span class="posted_by">Posted by: Admin</span>
									<span class="comment"><a href="">21<i class="icon-bubble22"></i></a></span>
									<p>Far far away, behind the word mountains</p>
									<a href="#" class="btn btn-default">Read More</a>
								</div> 
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<div class="heading-section animate-box">
								<h2>Upcoming Events</h2>
							</div>
						</div>
						<div class="col-md-12 col-md-offset-0">
							<div class="fh5co-blog animate-box">
								<div class="meta-date text-center">
									<p><span class="date">14</span><span>June</span><span>2016</span></p>
								</div>
								<div class="desc desc2">
									<h3><a href=""#>Starting new session of body building this summer</a></h3>
									<span class="posted_by">Posted by: Admin</span>
									<span class="comment"><a href="">21<i class="icon-bubble22"></i></a></span>
									<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
									<a href="#" class="btn btn-default">Read More</a>
								</div> 
							</div>
						</div>
						<div class="col-md-12 col-md-offset-0">
							<div class="fh5co-blog animate-box">
								<div class="meta-date text-center">
									<p><span class="date">13</span><span>June</span><span>2016</span></p>
								</div>
								<div class="desc desc2">
									<h3><a href=""#>Starting new session of body building this summer</a></h3>
									<span class="posted_by">Posted by: Admin</span>
									<span class="comment"><a href="">21<i class="icon-bubble22"></i></a></span>
									<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
									<a href="#" class="btn btn-default">Read More</a>
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<footer>
			<div id="footer">
				<div class="container">
					<div class="row">
						<div class="col-md-4 animate-box">
							<h3 class="section-title">About Us</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics.</p>
						</div>

						<div class="col-md-4 animate-box">
							<h3 class="section-title">Our Address</h3>
							<ul class="contact-info">
								<li><i class="icon-map-marker"></i>198 West 21th Street, Suite 721 New York NY 10016</li>
								<li><i class="icon-phone"></i>+ 1235 2355 98</li>
								<li><i class="icon-envelope"></i><a href="#">info@yoursite.com</a></li>
								<li><i class="icon-globe2"></i><a href="#">www.yoursite.com</a></li>
							</ul>
						</div>
						<div class="col-md-4 animate-box">
							<h3 class="section-title">Drop us a line</h3>
							<form class="contact-form">
								<div class="form-group">
									<label for="name" class="sr-only">Name</label>
									<input type="name" class="form-control" id="name" placeholder="Name">
								</div>
								<div class="form-group">
									<label for="email" class="sr-only">Email</label>
									<input type="email" class="form-control" id="email" placeholder="Email">
								</div>
								<div class="form-group">
									<label for="message" class="sr-only">Message</label>
									<textarea class="form-control" id="message" rows="7" placeholder="Message"></textarea>
								</div>
								<div class="form-group">
									<input type="submit" id="btn-submit" class="btn btn-send-message btn-md" value="Send Message">
								</div>
							</form>
						</div>
					</div>
					<div class="row copy-right">
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
					</div>
				</div>
			</div>
		</footer>
	

	</div>

	</div> -->
	<!-- END fh5co-wrapper -->

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
			$("#minimiza i").click(function(){
				// alert($("#minimiza i").text());
				if($("#minimiza").text()=="expand_less"){
					$("#fh5co-header-section").css({"background-color":"transparent","transition":"2s"});
					// $("#fh5co-header-section li").css({"display":"none","transition","2s"});
					$("#minimiza i").html("<i class='material-icons'>expand_more</i>");
					$("li a").hide('slow');
					$(".fixed-action-btn.vertical").hide();
					$("#fcp").hide();
				}else{
					$("#fh5co-header-section").removeAttr("style");
					$("#minimiza i").html("<i class='material-icons'>expand_less</i>");
					$("li a").show();
					$("#fcp").show();
					$(".fixed-action-btn.vertical").show();
				}
			});
			$(".linhac").click(function(){
				id = $(this).attr("value");
				$.ajax({
					url: 'anexosc.php',
					method: 'post',
					data: {'id':id},
					success:function(data){
						$("#anexos .modal-body").append(data);
					}
				});
			});
			$("#cancel, #fecha").click(function(){
				$("#anexos .modal-body input").remove();
			});
		});
		$(document).on("click","#abt",function(){
			$.ajax({
				url: 'ps_c.php',
				method: 'post',
				data: {},
				success:function(data){
				}
			});
		});
		$(document).on("click","#cancelares",function(){
			$.ajax({
				url: 'cancela_registro.php',
				method: 'post',
				data: {'clidel': $('#clidel').attr('value')},
				success:function(data){
					window.location = 'index.php';
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

