<?php
	// Os dois header abaixo inicia a página sem o cache 
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sun, 11 Apr 2010 05:00:00 GMT");
	session_start();
	include "conexao.inc.php";
	if(!isset($_COOKIE['id'])){
		echo "<script>alert('Erro ao acessar página, manutenção necessária!'); window.location = 'clientes.php';</script>";
	}
	if(isset($_POST['acao'])){
		switch ($_POST['acao']) {
			case 'anexar':
				$nome_pasta = $_POST['pasta'];
				$verifica_anexoc = $conn->query("SELECT * FROM  cliente WHERE perfil = '$nome_pasta'");
				while ($v = $verifica_anexoc->fetch(PDO::FETCH_ASSOC)) {
					if($v['anexo_mensal']!='0000-00-00'){
						$mudouam = $v['anexo_mensal'];
					}else{
						$mudouam = '0000-00-00';
					}
					if($v['anexo_trimestral']!='0000-00-00'){
						$mudouat = $v['anexo_trimestral'];
					}else{
						$mudouat = '0000-00-00';
					}
					if($v['anexo_reserva']!='0000-00-00'){
						$mudouar = $v['anexo_reserva'];
					}else{
						$mudouar = '0000-00-00';
					}
				}
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
						echo "<script>alert('Anexos adicionado ao cliente!'); window.location = 'ficha_cliente.php';</script>";
					}else{
						if($mudouam==0 && $mudouat==0 && $mudouar==0){
							echo "<script>alert('Nenhum anexo foi adicionado ao cliente!'); window.location = 'ficha_cliente.php';</script>";	
						}
						echo "<script>alert('Erro ao adicionar anexos ao cliente!'); window.location = 'ficha_cliente.php';</script>";
					}
				break;
			
			case 'deletar':
				$ficha = $_POST['ficha'];
				$perfil = $_POST['pasta'];
				system("del usuarios\\".$perfil."\\".$ficha.".pdf /q");
				switch ($ficha) {
					case 'am':
						$sql = $conn->exec("UPDATE cliente SET anexo_mensal = '0000-00-00' WHERE perfil = '$perfil'");
						if($sql){
							echo "<script>alert('Ficha deletada com sucesso!'); window.location = 'ficha_cliente.php';</script>";	
						}else{
							echo "<script>alert('Erro ao deletar ficha!'); window.location = 'ficha_cliente.php';</script>";
						}
						break;
					case 'at':
						$sql = $conn->exec("UPDATE cliente SET anexo_trimestral = '0000-00-00' WHERE perfil = '$perfil'");
						if($sql){
							echo "<script>alert('Ficha deletada com sucesso!'); window.location = 'ficha_cliente.php';</script>";	
						}else{
							echo "<script>alert('Erro ao deletar ficha!'); window.location = 'ficha_cliente.php';</script>";
						}
						break;
					case 'ar':
						$sql = $conn->exec("UPDATE cliente SET anexo_reserva = '0000-00-00' WHERE perfil = '$perfil'");
						if($sql){
							echo "<script>alert('Ficha deletada com sucesso!'); window.location = 'ficha_cliente.php';</script>";	
						}else{
							echo "<script>alert('Erro ao deletar ficha!'); window.location = 'ficha_cliente.php';</script>";
						}
						break;
					default:
						echo "<script>alert('Erro, Manutenção necessária!'); window.location = 'ficha_cliente.php';</script>";
						break;
				}
				break;

			default:
				echo "<script>alert('Erro, Manutenção necessária no JavaScript!'); window.location = 'ficha_cliente.php';</script>";
				break;
		}
	}
	$id = $_COOKIE['id'];
	$sql = $conn -> query("SELECT * FROM cliente WHERE id='$id'");
	if($sql->rowCount()==0){
		echo "<script>alert('Cliente não encontrado!'); window.close();</script>";
	}
	while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		$categorias = $conn->query("SELECT * FROM categoria WHERE id = '".$linha['categoria']."'");
			while($categoria = $categorias->fetch(PDO::FETCH_ASSOC)){
				$valor = $categoria['nome'];
				// if($categoria['id']==$linha['categoria']){
				// 	$option .= "<option value='".$categoria['id']."' selected>".$categoria['nome']."</option>";
				// }else{
				// 	$option .= "<option value='".$categoria['id']."'>".$categoria['nome']."</option>";
				// }
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
		<div class="modal fade" id="visualiza" data-backdrop="static">
            <div class="modal-dialog" id="modal_1">
              <div class="modal-content" id="modal_2">
                    <div class="modal-header">
                      <button type="button" class="close" id="closesai" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="visualizador">
                      <!-- Puchar todos os slides -->
                      <!-- <input type="file" name="imag" accept="image/png, image/jpeg, image/jpg"> -->
                        <img src="<?php echo 'usuarios/'.$linha['perfil'].'/perfil.jpg';?>" title="<?php echo $linha['nome'];?>">
                    </div>
              </div>
            </div>
        </div>
		<footer>
			<div id="footer" style="background-color: black;">
				<div class="container">
					<div class="row">
						<div class="col-md-6 animate-box">
							<h3 class="section-title"><img src="<?php echo 'usuarios/'.$linha['perfil'].'/perfil.jpg';?>" id="imagem_perfil" data-toggle="modal" data-target="#visualiza" style="max-height:100px;max-width: 100px; cursor: pointer;"> <?php echo $linha['nome'];?></h3>
							<ul class="contact-info">
								<li><i class="icon-list" style="color: gold;"></i><?php echo $valor;?></li>
								<!--Adicionar mais coisas na ficha-->
								<li><i class="icon-calendar" style="color: gold;"></i>Data de Nascimento: <?php $linha['data_nascimento'] = DateTime::createFromFormat('Y-m-d',$linha['data_nascimento'])->format('d/m/Y'); echo $linha['data_nascimento'];?></li>
								<li><i class="icon-envelope" style="color: gold;"></i><?php echo $linha['email'];?></li>
								<li><i class="icon-phone" style="color: gold;"></i><?php echo $linha['telefone'];?></li>
								<li><i class="icon-map-marker" style="color: gold;"></i><?php echo "Bairro: ".$linha['bairro']." <br>Rua: ".$linha['rua']." <br>Nº ".$linha['numero'];?></li>
							</ul>
						</div>
					<div class="col-md-6 animate-box" id="anexos_es">
							<h3 class="section-title">Histórico de Visitas</h3>
							<div style="max-height: 380px;width: 100%;overflow: auto;">
								<?php $verificah = $conn->query("SELECT * FROM historico WHERE cliente = '".$linha['id']."' ORDER BY id DESC");
								if($verificah->rowCount()==0){
									echo "O cliente ainda não visitou a academia.";
								}else{
									echo "<table class='table'><thead><tr><th>Data</th><th>Hora de Entrada</th><th>Hora de Saída</th></tr></thead><tbody>";
									while ($hc = $verificah->fetch(PDO::FETCH_ASSOC)) {
										if($hc['hora_saida']=='00:00:00'){
											$hc['hora_saida'] = "Ainda na academia";
										}
										echo "<tr><td>".DateTime::createFromFormat('Y-m-d',$hc['data'])->format('d/m/Y')."</td><td>".$hc['hora_entrada']."</td><td>".$hc['hora_saida']."</td></tr>";
									}
									echo "</tbody></table>";
								}
								?>
							</div>
							<button class="btn btn-default" title="Anexos" data-toggle="modal" data-target="#anexosc"><i class="material-icons" style="padding: 0;margin: 0;vertical-align: middle;font-size: 150%;">link</i></button>
							<!-- <input type="file" name=""> -->
							<!-- <input type="file" name="am" id = 'am' accept='.pdf' style="display: none;"> -->
							<!-- <label for="">teste</label> -->
							<!-- <button class="btn btn-default" title="Anexo Reserva"><i class="material-icons" style="padding: 0;border:0; margin: 0;vertical-align: bottom;">attach_file</i> <button class="btn btn-primary" title="Apagar">&times;</button> </button> 
							 <button class="btn btn-default" title="Anexo Trimestral"><i class="material-icons" style="padding: 0;border:0; margin: 0;vertical-align: bottom;">attach_file</i></button>
							 <button class="btn btn-default" for = "am" title="Anexo Mensal"><i class="material-icons" style="padding: 0;border:0; margin: 0;vertical-align: bottom;">attach_file</i></button> -->
						</div>
					</div>
					</div>
					<div class="modal fade" id="anexosc" data-backdrop="static">
			            <div class="modal-dialog">
			              <div class="modal-content">
			                <form method="post" action="" enctype="multipart/form-data">
			                    <div class="modal-header">
			                      <button type="button" class="close" data-dismiss="modal">&times;</button>
			                      <h4 class="modal-title">Anexos do Cliente</h4>
			                    </div>
			                    <div class="modal-body" style="text-align: center;">
							 		<?php if($linha['anexo_mensal']=='0000-00-00' && $linha['anexo_trimestral']=='0000-00-00' && $linha['anexo_reserva']=='0000-00-00'){echo "";}if($linha['anexo_mensal']!='0000-00-00'){?>
							 		<div class="anexosd" onclick="<?php echo "window.open('usuarios/".$linha['perfil']."/am.pdf')";?>">
							 			<i class="small material-icons" style="padding: 0;border:0; margin: 0;vertical-align: middle;">insert_drive_file</i> Anexo Mensal
							 		</div>		
							 		<?php }if($linha['anexo_trimestral']!='0000-00-00'){?>
									<div class="anexosd" onclick="<?php echo "window.open('usuarios/".$linha['perfil']."/at.pdf')";?>">
							 			<i class="small material-icons" style="padding: 0;border:0; margin: 0;vertical-align: middle;">insert_drive_file</i> Anexo Trimestral							 			
							 		</div>
							 		<?php }if($linha['anexo_reserva']!='0000-00-00'){?>
			                    	<div class="anexosd" onclick="<?php echo "window.open('usuarios/".$linha['perfil']."/ar.pdf')";?>">
										<i class="small material-icons" style="padding: 0;border:0; margin: 0;vertical-align: middle;">insert_drive_file</i> Anexo Reserva
									</div> 
									<?php }if($linha['anexo_reserva']=='0000-00-00' and $linha['anexo_mensal']=='0000-00-00' and $linha['anexo_trimestral']=='0000-00-00'){ echo "<label>Sem anexos disponíveis no momento!</label>";}?>
									<input type="hidden" name="pasta" value="<?php echo $linha['perfil'];?>">
									<input type="hidden" name="acao" value="">
								</div>
			                    <div class="modal-footer">
			                      <button type="button" class="btn btn-primary pull-left" id="configura" style="color: white;background-color:gray;box-shadow: 0px 2px 4px gray;">Configurar</button>
			                      <?php if($linha['anexo_mensal']!='0000-00-00' || $linha['anexo_trimestral']!='0000-00-00' || $linha['anexo_reserva']!='0000-00-00'){?>
			                      <button type="button" class="btn btn-default pull-left" id = "deleta" style="color: #333;background-color:yellow;box-shadow: 0px 2px 4px gray;">Deletar</button>
			                      <?php }?>
			                      <button type="button" class="btn btn-default" data-dismiss="modal" style="color: #333;box-shadow: 0px 2px 4px gray;" id="canc">Cancelar</button>
			                    </div>
			                </form>
			              </div>
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
			</div>
		</footer>
	

	</div>

	</div>
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
	<script type="text/javascript">
		$(document).ready(function(){
			$("#configura").click(function(){
				if($("#configura").text()=="Configurar"){
					$("#anexosc .modal-body .anexosd").hide();
					$("#anexosc .modal-body label").remove();
					$("#anexosc .modal-body input[name='acao']").attr("value","anexar");
					$("#anexosc .modal-body").append("<input type='file' title = 'Selecione o Anexo Mensal' name='am' accept='.pdf' style='margin:1%;display:inline'> <label style='color:gold;'>Anexo Mensal</label> <input type='file' title = 'Selecione o Anexo Trimestral' name='at' accept='.pdf' style='margin:1%;display:inline'> <label style='color:gold;'>Anexo Trimestral</label> <input type='file' title = 'Selecione o Anexo Reserva' name='ar' accept='.pdf' style='margin:1%;display:inline'> <label style='color:gold;'>Anexo Reserva</label>");
					$("#configura").html("Voltar");
					$("#deleta").hide();
					$("#anexosc .modal-footer").append("<button class='btn btn-primary' type='submit' style='box-shadow:0px 2px 4px gray;background-color:yellow;color:#333;'>Finalizar</button>");
					// $("#anexosc .modal-body p").hide();
				}else if($("#configura").text()=="Voltar"){
					// $("#anexosc .modal-body p").show();
					$("#anexosc .modal-body .anexosd").show();
					$("#anexosc .modal-footer button[type='submit'], #anexosc .modal-body input[type='file'],#anexosc .modal-body label").remove();
					$("#configura").html("Configurar");
					$('#deleta').show();
					// $("#anexosc .modal-body input[name='acao']").attr("value","anexar");
				}
			});
			$("#deleta").click(function(){
				if($("#deleta").text()=="Deletar"){
					$("#configura").hide();
					$("#deleta").html("Voltar");
					$("#anexosc .modal-body .anexosd").hide();
					$("#anexosc .modal-body input[name='acao']").attr("value","deletar");
					$("#anexosc .modal-body").append("<p style='font-size:100%; border-bottom: 1px solid rgba(0,0,0,0.2);margin:0;padding:0;width:100%;'>Selecione uma ficha a ser deletada!</p><select name='ficha' style='width:100%;margin:0;padding:0;'><option value='am'>Anexo Mensal</option><option value='at'>Anexo Trimestral</option><option value='ar'>Anexo Reserva</option></select>");
					$("#anexosc .modal-footer").append("<button class='btn btn-primary' type='submit' style='box-shadow:0px 2px 4px gray;background-color:yellow;color:#333;'>Finalizar</button>");
				}else if($("#deleta").text()=="Voltar"){
					$("#configura").show();
					$("#deleta").html("Deletar");
					$("#anexosc .modal-body .anexosd").show();
					$("#anexosc .modal-footer button[type='submit'], #anexosc .modal-body select,#anexosc .modal-body p").remove();
				}
			});
			$("#canc").click(function(){
				$("#anexosc .modal-body input[type='file'],#anexosc .modal-body label,#anexosc .modal-footer button[type='submit']").remove();
				$("#configura").html("Configurar");
				$("#deleta").show();
				$("#anexosc .modal-body .anexosd").show();
			});
			// $("button[for='am']").click(function(){
			// 	$("#am").click();
			// });
			// $("#am").change(function(){
			// 	if($("#am").val()!=""){
			// 		caminho = $("#imagem_perfil").attr("src");
			// 		var q = caminho.length;
			// 		caminho = caminho.substr(0,(q-10));
			// 		let form = new FormData();
			// 	    form.append("pdf", "Anexo Mensal");
			// 	    form.append("file", $('#am').prop('files')[0]);
			// 	    $.ajax({
			// 	        url: "envio_pdf.php",
			// 	        type: "post",
			// 	        data: {form, "caminho":caminho},
			// 	        cache : false,
			// 	        processData: false,
			// 	        success:function(data){

			// 	        }
			// 	    });		
			// 	}
			// });
		});
	</script>
	</body>
</html>
<?php }?>