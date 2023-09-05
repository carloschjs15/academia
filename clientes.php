<?php 
	session_start();
	include "conexao.inc.php";
	setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' ); 
    date_default_timezone_set( 'America/Fortaleza' );
	if(isset($_POST['acao'])){
		$nome = $_POST['nome'];
		$data = $_POST['data'];
		$plano = $_POST['plano'];
		$email = $_POST['email'];
		$telefone = $_POST['telefone'];
		$rua = $_POST['rua'];
		$bairro = $_POST['bairro'];
		$numero = $_POST['numero'];
		switch ($_POST['acao']) {
			case 'add':
				// email,telefone,rua,bairro e numero
				if($plano==null || $plano==""){
					echo "<script>alert('Por favor adicione uma categoria, antes de proceder!'); window.location = 'categorias.php';</script>";
				}else{
					//$nome_perfil = $nome_pasta;
					$sql = $conn->exec("INSERT INTO cliente VALUES(default,'$nome','$data','$plano','$email','$telefone','$rua','$bairro','$numero','".date('Y-m-d')."','','0000-00-00','0000-00-00','0000-00-00')");
					// system("move C:\\xampp\htdocs\".$nome_pasta." C:\\xampp\htdocs\fitness\usuarios");
					// $datap = $conn->exec("INSERT INTO pagamento ")
					$consulta = $conn->query("SELECT * FROM  cliente ORDER BY id DESC");
					$t = 0;
					while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
						if($t==0){
							$data = $_POST['datan'];
							// echo "<script>alert('".$data."');</script>";
							$pega_valorc = $conn->query("SELECT valor FROM categoria WHERE id = '".$linha['categoria']."'");
							while ($valorc = $pega_valorc->fetch(PDO::FETCH_ASSOC)) {
								//id_cliente,nome,data,id_promocao,valor
								echo "<script>alert('".$linha['id'].",".$linha['nome'].",".$data.",".$valorc['valor']."');</script>";
								$datap = $conn->exec("INSERT INTO pagamento VALUES('".$linha['id']."','".$linha['nome']."','".$data."',0,'".$valorc['valor']."')");
								$insere_pd = $conn->exec("INSERT INTO pagamentosd VALUES(default,  '".$linha['id']."',".$valorc['valor'].",'".date('Y-m-d')."')");
							}
						}
						$t++;
					}
					if($sql){
						echo "<script>alert('Cliente adicionado com sucesso!'); window.location = 'clientes.php';</script>";
					}else{
						echo "<script>alert('Erro ao adicionar cliente!'); window.location = 'clientes.php';</script>";
					}
				}
				break;

			case 'edit':
				$referencia = $_POST['referencia'];
					$consulta_cliente_loca = $conn->query("SELECT * FROM cliente WHERE id = '$referencia'");
					while ($cclo = $consulta_cliente_loca->fetch(PDO::FETCH_ASSOC)) {
						$pastaan = $cclo['perfil'];
						if($cclo['categoria']!=$plano){
							$pagamento = $conn->query("SELECT * FROM pagamento WHERE id_cliente = '".$cclo['id']."'");
							while($pag = $pagamento->fetch(PDO::FETCH_ASSOC)){
								$valorcategoria = $conn->query("SELECT * FROM categoria WHERE id = '$plano'");
								while ($vc = $valorcategoria->fetch(PDO::FETCH_ASSOC)) {
									$valorc = $vc['valor'];
								}
								if($pag['id_promocao']==0){
									$alterador = $conn->exec("UPDATE pagamento SET valor = '$valorc' WHERE id_cliente = '$referencia'");
								}else{
									$prom = $conn->query("SELECT * FROM promocao WHERE id = '".$pag['id_promocao']."'");
									while($p = $prom->fetch(PDO::FETCH_ASSOC)){
										$desconto = (100-$p['desconto'])/100;
									}
									$novop = $valorc*$desconto;
									$novop = number_format($novop,2,'.',''); //Troca a vírgula que separa as casas decimais pelo ponto
									$alterador = $conn->exec("UPDATE pagamento SET valor = '$novop' WHERE id_cliente = '$referencia'");
								}
							}
						}
					}
					//system("rename C:\\xampp\\htdocs\\usuarios\\".$pastaan." ".$nome_pasta);
						$altera_pagamento = $conn -> exec("UPDATE pagamento SET nome = '$nome' WHERE id_cliente = '$referencia'");
						$sql = $conn -> exec("UPDATE cliente SET nome = '$nome', data_nascimento = '$data', categoria = '$plano', email = '$email', telefone = '$telefone', rua = '$rua', bairro = '$bairro', numero = '$numero' WHERE id = '$referencia'");
				if($sql){
					echo "<script>alert('Cliente editado com sucesso!'); window.location = 'clientes.php';</script>";
				}else{
					if($mudou){
						echo "<script>alert('Cliente editado com sucesso!'); window.location = 'clientes.php';</script>";	
					}
					echo "<script>alert('Erro, nada a ser alterado!'); window.location = 'clientes.php';</script>";
				}
				break;

			case 'del':
				$referencia = $_POST['referencia'];
				$consulta_cliente_local = $conn->query("SELECT * FROM cliente WHERE id = '$referencia'");
				while ($ccl = $consulta_cliente_local->fetch(PDO::FETCH_ASSOC)) {
					$perfil = $ccl['perfil'];
				}
				//system("rd C:\\xampp\\htdocs\\usuarios\\".$perfil." /s /q");
				$conpro = $conn->query("SELECT * FROM promocao ORDER BY id");
				while ($delcp = $conpro->fetch(PDO::FETCH_ASSOC)) {
                    $valores = $delcp['clientes'];
                    $valor = "";
                    $pega = "";
                    $pass=1;
                    for ($i = 0; $i < strlen($valores); $i++) {
                        if($valores[$i]!=","){
                            $pega.=$valores[$i];
                        }else{
                            if($pega != $referencia){
                                if($valor!=""){
                                    $valor.=",".$pega;
                                }else{
                                    $valor.=$pega;
                                }
                            }
                            $pega="";
                        }
                        // echo "<script>alert('".$valores[$pass]."');</script>";
                        if($valores[$pass]==null){
                            if($pega != $referencia){
                                if($valor!=""){
                                    $valor.=",".$pega;
                                }else{
                                    $valor.=$pega;
                                }
                            }    
                            $pega="";
                        } 
                        $pass++;
                    }
                    if($valor==""){
                    	$valor=0;
                    }        	
                    $delpromocaocli = $conn->exec("UPDATE promocao SET clientes = '$valor' WHERE id = '".$delcp['id']."'");
                    
				}
				$sql = $conn -> exec("DELETE FROM cliente WHERE id = '$referencia'");
				$deleta_pagamento = $conn->exec("DELETE FROM pagamento WHERE id_cliente = '$referencia'");
				$deleta_historico = $conn->exec("DELETE FROM historico WHERE cliente = '$referencia'");
				if($sql){
					echo "<script>alert('Cliente deletado com sucesso!'); window.location = 'clientes.php';</script>";
				}else{
					echo "<script>alert('Erro ao deletar cliente!');window.location = 'clientes.php';</script>";
				}
				break;

			default:
				# code...
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
	<body>
		<div id="fh5co-wrapper">
		<div id="fh5co-page">
		<div id="fh5co-header">
			<header id="fh5co-header-section">
				<div class="container">
					<div class="nav-header">
						<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle"><i></i></a>
						<h1 id="fh5co-logo"><a href="index.php" style="color: gold;font-family: Agency FB, calibri;"><img src="icon.png" style="width: 40px;height: 40px;vertical-align: top;">FIT <span style="font-weight: bold;color: white;">LINE</span></a></h1><!-- START #fh5co-menu-wrap -->
						<nav id="fh5co-menu-wrap" role="navigation">
							<ul class="sf-menu" id="fh5co-primary-menu">
								<li>
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
								<li class="active">
									<a href="clientes.php">Clientes</a>
								</li>
								<li><a href="categorias.php">Categorias</a></li>
								<li><a href="promocoes.php">Promoções</a></li>
								<li><a href="pagamentos.php">Pagamentos</a></li>
								<li><a href="manual.php">Manual</a></li>
								<!-- <li id="minimiza"><i class="material-icons">expand_less</i></li> -->
							</ul>
						</nav>
					</div>
				</div>
			</header>		
		</div>
		<!-- end:fh5co-header -->
		<div class="fh5co-hero" style="background-image: url(images/home-image-5.jpg);" data-stellar-background-ratio="0.5">
			<div class="overlay"></div>
			<div class="container" id="container_clientes">
				<div class="row">
					<div class="col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0 text-center fh5co-table">
						<div class="fh5co-intro fh5co-table-cell animate-box">
							<table width="100%" class="table table-hover" id="datatables-example">
                                <thead class="thead-default">
                                    <tr>
                                        <th>Nome</th>
                                    	<th>Chave de Acesso</th>
                                        <th>Data de Nascimento</th>
                                        <th>Categoria</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Bairro</th>
                                        <th>Rua</th>
                                        <th>Número</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
                                		$mostra_clientes = $conn->query("SELECT * FROM cliente");
                                		while ($cliente = $mostra_clientes->fetch(PDO::FETCH_ASSOC)) {
                                			$cliente['data_nascimento'] = DateTime::createFromFormat('Y-m-d', $cliente['data_nascimento'])->format('d/m/Y');
                                	?>
                                    <tr class="odd gradeX" <?php echo "value='".$cliente['id']."'";?> data-toggle="modal" data-target="#editc">
                                        <td class="center"><?php echo $cliente['nome'];?></td>
                                        <td class="center"><?php echo $cliente['id'];?></td>
                                        <td class="center"><?php echo $cliente['data_nascimento'];?></td>
                                        <td class="center"><?php $categorias = $conn->query("SELECT * FROM categoria WHERE id = '".$cliente['categoria']."'");while($categoria = $categorias->fetch(PDO::FETCH_ASSOC)){echo $categoria['nome'];}?></td>
                                        <td class="center"><?php echo $cliente['email'];?></td>
                                        <td class="center"><?php echo $cliente['telefone'];?></td>
                                        <td class="center"><?php echo $cliente['bairro'];?></td>
                                        <td class="center"><?php echo $cliente['rua'];?></td>
                                        <td class="center"><?php echo $cliente['numero'];?></td>  
                                    </tr>
                                    <?php
                                		}
                                	?>
                                </tbody>
                            </table> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="fixed-action-btn vertical" data-toggle="modal" data-target="#addc">
		    <a class="waves-effect waves-light btn-floating btn-large yellow darken-1" title="Adicionar Clientes">
		      <i class="large material-icons">add</i>
		    </a> 
		</div>
		<div class="modal fade" id="addc" data-backdrop="static">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Adicionar Cliente</h4>
                    </div>
                     <!-- email,telefone,rua,bairro e numero -->
                    <div class="modal-body">
                      <!-- Puchar todos os slides -->
                      <!-- <input type="file" name="imag" accept="image/png, image/jpeg, image/jpg"> -->
                      <input type="file" name="perfil" title="Selecione uma foto do cliente. Recomendável que a largura seja igual a altura." accept='image/png, image/jpeg, image/jpg' style="width: 100%;">
                      <input type="text" name="nome" placeholder="Nome" title="Digite o Nome" required autofocus>
                      <input type="date" name="data" title="Selecione a Data de Nascimento" required>
                      <?php $consulta_categorias = $conn -> query("SELECT * FROM categoria");
                      if($consulta_categorias->rowCount()==0){
                      	echo "<select name='plano' required disabled><option>Categorias indisponíveis por favor adicione uma nova categoria!</option>";
                      }else{?>
                      	<?php echo "<select name='plano' required>";while($categoria = $consulta_categorias->fetch(PDO::FETCH_ASSOC)){?>
                      	<option value="<?php echo $categoria['id'];?>"><?php echo $categoria['nome'];?></option>
                      	<?php } }?>
                      </select>
                      <input type="email" name="email" placeholder="Email" title="Digite o Email">
                      <!-- A "\" vai colocar o caractere que está no lado direito
                      Os "[]" irão delimitar os tipos de valores que irão poder ser usados no caso da situação a seguir de 0 a 9
                      As "{}" a quantidade de algarismos utilizados -->
                      <input type="text" name="telefone" placeholder="Telefone" title="(XX) XXXXX-XXXX" pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" required maxlength="15">
                      <input type="text" name="rua" placeholder="Rua" title="Digite a Rua" required>
                      <input type="text" name="bairro" placeholder="Bairro" title="Digite o Bairro" required>
                      <input type="number" name="numero" min="1" title="Digite o Número" placeholder="Nª" value="0" required style="width: 10%">
                      <input type="date" name="datan" value = "<?php echo date('Y-m-d');?>" title="Digite a data de Pagamento" style="width: 22.5%" required>
                      <input type="hidden" name="acao" value="add">
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
              </div>
            </div>
        </div>
        <div class="modal fade" id="editc" data-backdrop="static">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Editar Cliente</h4>
                    </div>
                    <div class="modal-body">
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary black pull-left" id="delc">Excluir</button>
                      <button type="button" class="btn btn-primary pull-left" id="btnfic" style="background-color: dimgray;">Ficha</button>
                      <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
                      <button type="button" class="btn btn-default" data-dismiss="modal" id="cancedit">Cancelar</button>
                    </div>
                </form>
              </div>
            </div>
        </div>
		<!-- end: fh5co-parallax -->
		<!-- 
		<div id="fh5co-schedule-section" class="fh5co-lightgray-section">
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
	 -->

	</div>
	<!-- END fh5co-page -->

	</div>
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
		// function verifica(){
		// 	if($("#addc input[name='email']").val()!="" || $("#addc input[name='email']").val()==undefined){
		// 		$("#addc input[name='telefone']").removeAttr("required");
		// 	}
		// 	else if($("#addc input[name='telefone']").val()!="" || $("#addc input[name='email']").val()==undefined){
		// 		$("#addc input[name='email']").removeAttr("required");
		// 	}else if(){
		// 		$("#addc input[name='telefone']").attr("required");
		// 		$("#addc input[name='email']").attr("required");	
		// 	}
		// }
		$(document).ready(function(){
			$('#datatables-example').DataTable({
            	responsive: true
        	});
        	$("#cancedit").click(function(){
        		$("#editc .modal-body input, #editc .modal-body select").remove();
        	});
        	$("#btnfic").click(function(){
        		var id = $("input[name='referencia']").attr("value");
        		var caracter = id;
			    var expires;
				var date; 
		    	var value;
		    	date = new Date(); //  criando o COOKIE com a data atual
		    	date.setTime(date.getTime()+(1000*24*60*60*1000));
		    	expires = date.toUTCString();
		    	value = caracter;
		    	document.cookie = "id"+"="+value+"; expires="+expires+"; path=/";
		    	window.open("ficha_cliente.php");
        	});
        	$("#delc, #delcf").click(function(){
        		// $("input[name='referencia']").attr("value","del");
        		if(confirm("Você está deletando o cliente!")==true){
        		acao = "del";
        		referencia = $("input[name='referencia']").attr("value");
	        		$.ajax({
	        			url: "clientes.php",
	        			method: "post",
	        			data: {"acao":acao, "referencia":referencia},
	        			success:function(data){
	        				alert("Cliente deletado com sucesso!");
	        				window.location = "clientes.php";
	        			}
	        		});
        		}
        	});
   //      	$("#minimiza i").click(function(){
			// 	// alert($("#minimiza i").text());
			// 	if($("#minimiza").text()=="expand_less"){
			// 		$("#fh5co-header-section").css({"background-color":"transparent","transition":"2s"});
			// 		// $("#fh5co-header-section li").css({"display":"none","transition","2s"});
			// 		$("#minimiza i").html("<i class='material-icons'>expand_more</i>");
			// 		$("li a").hide('slow');
			// 	}else{
			// 		$("#fh5co-header-section").removeAttr("style");
			// 		$("#minimiza i").html("<i class='material-icons'>expand_less</i>");
			// 		$("li a").show();
			// 	}
			// });
		});
		$(document).on("click", "#datatables-example tbody .gradeX", function(){
        		id = $(this).attr("value");
        		$.ajax({
                    url: 'consulta_cliente.php',
                    method: 'post',
                    data: {"id": id},
                    success:function(data){
                        $("#editc .modal-body").append(data);
                    }
                });
        	});
	</script>
	</body>
</html>

