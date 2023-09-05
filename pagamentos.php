<?php 
	session_start();
	require "conexao.inc.php";
	setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' ); 
    date_default_timezone_set( 'America/Fortaleza' );
	if(isset($_POST['acao'])){
		switch ($_POST['acao']) {
			case 'pagar':
				$id_cliente = $_POST['id_cliente'];
				$pegadordata = $conn->query("SELECT * FROM pagamento WHERE id_cliente = '$id_cliente'");
				if($pegadordata->rowCount()==0){
					echo "<script>alert('singos');</script>";
				}
				while($pg = $pegadordata->fetch(PDO::FETCH_ASSOC)){
					$dataa = $pg['data'];
					if($pg['id_promocao']==0){
						$valorn = $pg['valor'];
					}else{
						$valorp = $conn->query("SELECT * FROM promocao WHERE id = '".$pg['id_promocao']."'");
						while ($v = $valorp->fetch(PDO::FETCH_ASSOC)) {
							$valorn = ($pg['valor']*100)/(100-$v['desconto']);
						}
					}
				}
				if($_POST['datapag']==""){
					$ano = date('Y');
					$mes = date('m');
					$dia = date('d');
					if(($mes+1)>12){
						if(date('M-d-Y',mktime(0,0,0,1,$dia,($ano+1)))){
							$pdata = date(($ano+1).'-1-'.$dia);
						}else{
							$pdata = date(($ano+1).'-1-t');
						}
					}else{
						if(date('M-d-Y',mktime(0,0,0,($mes+1),$dia,$ano))){
							$pdata = date($ano.'-'.($mes+1).'-'.$dia);
						}else{
							$pdata = date($ano.'-'.($mes+1).'-t');
						}
					}
				}else{
					$pdata = $_POST['datapag'];
				}
				$alteradp = $conn->exec("UPDATE pagamento SET id_promocao = 0, valor = '$valorn', data = '$pdata' WHERE id_cliente = '$id_cliente'");
				$referencia = $id_cliente;
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
				if($alteradp){
					$deleta = $conn->exec("DELETE FROM pagamentosd WHERE data < '".date('Y-m-d')."'");
					$add_d = $conn->exec("INSERT INTO pagamentosd VALUES(default,'$id_cliente','$valorn','".date('Y-m-d')."')");
					// if($deleta){
					// 	echo "<script>alert('Deletou');</script>";	
					// }
					// if($add_d){
					// 	echo "<script>alert('Adicionou');</script>";	
					// }
					echo "<script>alert('Pagamento efetuado com sucesso!'); window.location = 'pagamentos.php';</script>";
				}else{
					echo "<script>alert('Erro ao efetuar pagamento!'); window.location = 'pagamentos.php';</script>";
				}
				break;
			
			default:
				echo "<script>alert('Erro, manutenção necessária!');</script>";
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
								<li>
									<a href="clientes.php">Clientes</a>
								</li>
								<li><a href="categorias.php">Categorias</a></li>
								<li><a href="promocoes.php">Promoções</a></li>
								<li class="active"><a href="pagamentos.php">Pagamentos</a></li>
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
				<div class="fixed-action-btn vertical" data-toggle="modal" data-target="#addc">
				    <a class="waves-effect waves-light btn-floating btn-large black darken-1" href="pagamentos_diarios.php" target="blank" title="Pagamentos gerais diários">
				      <i class="large material-icons">attach_money</i>
				    </a> 
				</div>
				<div class="row">
					<div class="col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0 text-center fh5co-table">
						<div class="fh5co-intro fh5co-table-cell animate-box">
							<table width="100%" class="table table-hover" id="datatables-example">
                                <thead class="thead-default">
                                    <tr>
                                        <th>Data de Pagamento (Ano-Mês-Dia)</th>
                                        <th>Cliente</th>
                                        <th>Promoção</th>
                                        <th>Valor de Pagamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
                                		$pagamento = $conn->query("SELECT * FROM pagamento ORDER BY data ASC");
                                		while ($cliente = $pagamento->fetch(PDO::FETCH_ASSOC)) {
                                			//$cliente['data'] = DateTime::createFromFormat('Y-m-d', $cliente['data'])->format('d/m/Y');
                                	?>
                                    <tr class="odd gradeX" data-toggle="modal" data-target="#pagar" <?php echo "value='".$cliente['id_cliente']."'";?>>
                                        <td class="center"><?php echo $cliente['data'];?></td>
                                        <td class="center"><?php echo $cliente['nome'];?></td>
                                        <td class="center"><?php $promo = $conn->query("SELECT * FROM promocao WHERE id = '".$cliente['id_promocao']."'");
                                        if($promo->rowCount()==0){
                                        	echo "Nenhuma promoção";
                                        }else{ while($pc = $promo->fetch(PDO::FETCH_ASSOC)){echo $pc['nome'];}}?></td>
                                        <td class="center"><?php echo "R$ ".$cliente['valor'];?></td>  
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
		<div class="modal fade" id="pagar" data-backdrop="static">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Pagamento do Cliente</h4>
                    </div>
                    <div class="modal-body">
                   		<label>Próxima data de pagamento:</label> <input type="date" name="datapag" value="<?php echo date('Y-m-d');?>" title="Insira a próxima data de pagamento.">
                      <input type="hidden" name="id_cliente">
                      <input type="hidden" name="acao" value="pagar">
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary yellow" style="color: #333;">Efetuar Pagamento</button>  
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
		});
		$(document).on("click","#datatables-example tbody tr",function(){
			$("#pagar .modal-body input[name='id_cliente']").attr("value",$(this).attr("value"));
		});
	</script>
	</body>
</html>

