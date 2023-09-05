<?php 
	session_start();
	include "conexao.inc.php";
	if(isset($_POST['acao'])){
		switch ($_POST['acao']) {
			case 'add':
				$nome = $_POST['nome'];
				$valor = $_POST['valor'];
				$sql = $conn->exec("INSERT INTO categoria VALUES(default,'$nome','$valor')");
				if($sql){
					echo "<script>alert('Categoria adicionada com sucesso!'); window.location = 'categorias.php';</script>";
				}else{
					echo "<script>alert('Erro ao adicionar categoria!'); window.location = 'categorias.php';</script>";
				}
				break;

			case 'edit':
				if(!isset($_POST['nome'])){
					echo "<script>alert('Por favor, selecione uma categoria antes de finalizar o processo!'); window.location = 'categorias.php';</script>";
				}
				$nome = $_POST['nome'];
				$valor = $_POST['valor'];
				$referencia = $_POST['referencia'];
				$pagamentos = $conn->query("SELECT * FROM cliente WHERE categoria = '$referencia'");
				if($pagamentos->rowCount()!=0){
					while ($paga = $pagamentos->fetch(PDO::FETCH_ASSOC)) {
						$pesquisap = $conn->query("SELECT * FROM pagamento WHERE id_cliente = '".$paga['id']."'");
						while($altera = $pesquisap->fetch(PDO::FETCH_ASSOC)){
							if($altera['id_promocao']==0){
								$alterador = $conn->exec("UPDATE pagamento SET valor = '$valor' WHERE id_cliente = '".$paga['id']."'");
							}else{
								$verificap = $conn->query("SELECT * FROM promocao WHERE id = '".$altera['id_promocao']."'");
								while($d = $verificap->fetch(PDO::FETCH_ASSOC)){
									$desconto = $d['desconto'];
								}
								$novop = $valor*((100-$desconto)/100);
								$novopagamento = $conn->exec("UPDATE pagamento SET valor = '$novop' WHERE id_cliente = '".$paga['id']."'");
							}
						}
					}
				}
				$sql = $conn -> exec("UPDATE categoria SET nome = '$nome', valor = '$valor' WHERE id = '$referencia'");
				if($sql){
					echo "<script>alert('Categoria editada com sucesso!'); window.location = 'categorias.php';</script>";
				}else{
					echo "<script>alert('Erro ao editar categoria!'); window.location = 'categorias.php';</script>";
				}
				break;

			case 'del':
				if($_POST['referencia']!=""){
					$id = $_POST['referencia'];
					$referencia="";
						if($id==""){
							$tipo="";
						}
						if(strlen($id)==1){
							$referencia = $id;	
						}else{
							for ($i=0; $i < strlen($id); $i++) { 
					    		if($id[$i]!=","){
					    			$referencia.=$id[$i];
				    			}else{
				    				$referencia.="' or id='";
				    			}
			    			}
	    			}
				}
				if($_POST['referencia']!=""){
					$verificac = $conn->query("SELECT * FROM cliente WHERE categoria = '$referencia'");
					if($verificac->rowCount()==0){
						$sql = $conn -> exec("DELETE FROM categoria WHERE id = '$referencia'");
						if($sql){
							echo "<script>alert('Categoria(s) deletada(s) com sucesso!'); window.location = 'categorias.php';</script>";
						}else{
							echo "<script>alert('Erro ao deletar categoria(s)!');window.location = 'categorias.php';</script>";
						}
					}else{
						echo "<script>alert('Erro ao deletar categoria, existem clientes inclusos nela, por favor troque as categorias dos mesmos, para que a categoria possa ser deletada!'); window.location = 'categorias.php';</script>";
					}
				}else{
					echo "<script>alert('Nenhuma categoria a ser deletada!'); window.location = 'categorias.php';</script>";	
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
		.schedule li a.active{
			background-color: rgba(255,255,0,0.8);
			color: dimgray !important;
		}
		.schedule li a:hover{
			color: rgba(255,255,0,0.8) !important;
			background-color: dimgray;
			border-radius: 30px;
		}
		::selection{
			background-color: rgba(255,255,0,0.8);
			color: dimgray !important;
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
								<li class="active"><a href="categorias.php">Categorias</a></li>
								<li><a href="promocoes.php">Promoções</a></li>
								<li><a href="pagamentos.php">Pagamentos</a></li>
								<li><a href="manual.php">Manual</a></li>
							</ul>
						</nav>
					</div>
				</div>
			</header>		
		</div>
		<!-- end:fh5co-hero -->

		
		<div id="fh5co-schedule-section" class="fh5co-hero">
				<div class="fixed-action-btn vertical" data-toggle="modal" data-target="#addc">
		    <a class="waves-effect waves-light btn-floating btn-large white" title="Configurar Categorias">
		      <i class="large material-icons" style="color: dimgray;">settings</i>
		    </a>
		  	<ul>
		      <li><a class="btn-floating waves-effect" style="background-color: dimgray;" data-target="#add_categoria" data-toggle="modal" id="add"><i class="material-icons">add</i></a></li>
		      <li><a class="btn-floating waves-effect black" data-target="#del_categoria" data-toggle="modal" id="del"><i class="material-icons">delete</i></a></li>
		      <li><a class="btn-floating waves-effect yellow darken-1" data-target="#edit_categoria" data-toggle="modal" id="edit"><i class="material-icons">edit</i></a></li>
		      <!-- <li><a class="btn-floating waves-effect blue"><i class="material-icons">attach_file</i></a></li> -->
    		</ul>
 		</div>
 		<div class="modal fade" id="add_categoria" data-backdrop="static">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Adicionar Categoria</h4>
                    </div>
                    <div class="modal-body">
                      <input type="text" name="nome" placeholder="Nome" title="Digite o Nome" required autofocus style="width: 66%;">
                      <input type="number" name="valor" min="1" value="1.00" step=".05" title="Insira o valor da categoria" required style="width: 33%;">
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
        <div class="modal fade" id="del_categoria" data-backdrop="static">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Excluir Categoria(s)</h4>
                    </div>
                    <div class="modal-body" id="painel_del">
                    	<p style="text-align:center; font-size: 100%;border-bottom: 1px solid rgba(0,0,0,0.2);">Selecione uma categoria a ser deletada.</p>
                    	<?php $categorias_del = $conn -> query("SELECT * FROM categoria ORDER BY id");
                    		if($categorias_del->rowCount()>0){
	                    		while ($cdel = $categorias_del -> fetch(PDO::FETCH_ASSOC)) {
	                    			echo "<button type='button' class='btn btn-primary' style='background-color:#333;margin:1px;' value='".$cdel['id']."'>".$cdel['nome']."</button>";
	                    		}
                    		}else{
                    			echo "<p style='text-align:center; font-size: 100%;'>Sem categorias disponíveis, por favor adicione uma nova!</p>";
                    		}
                    	?>
                    	<input type="hidden" name="referencia" value="">
                    	<input type="hidden" name="acao" value="del">
                    </div>
                    <div class="modal-footer">
                      <p class="pull-left" style="background-color: yellow; padding: 0px 5px 0px 5px;color: #333; border-radius: 5px; box-shadow: 0px 2px 0px rgba(0,0,0,0.1);">Selecionado</p>
                      <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
                      <button type="button" class="btn btn-default" data-dismiss="modal" id="candel">Cancelar</button>
                    </div>
                </form>
              </div>
            </div>
        </div>
        <div class="modal fade" id="edit_categoria" data-backdrop="static">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Editar Categoria(s)</h4>
                    </div>
                    <div class="modal-body" id="painel_edit">
                    	<p style="text-align:center; font-size: 100%;border-bottom: 1px solid rgba(0,0,0,0.2);">Selecione uma categoria a ser editada.</p>
                    	<?php $categorias_edit = $conn -> query("SELECT * FROM categoria ORDER BY id");
                    		if($categorias_edit->rowCount()>0){
	                    		while ($cdel = $categorias_edit -> fetch(PDO::FETCH_ASSOC)) {
	                    			echo "<button type='button' class='btn btn-primary' style='background-color:#333;margin:1px;' value='".$cdel['id']."'>".$cdel['nome']."</button>";
	                    		}
                    		}else{
                    			echo "<p style='text-align:center; font-size: 100%;'>Sem categorias disponíveis, por favor adicione uma nova!</p>";
                    		}
                    	?>
                    	<input type="hidden" name="acao" value="edit">
                    </div>
                    <div class="modal-footer">
						<!--<p class="pull-left" style="background-color: yellow; padding: 0px 5px 0px 5px;color: #333; border-radius: 5px; box-shadow: 0px 2px 0px rgba(0,0,0,0.1);">Selecionado</p> -->
                      <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
                      <button type="button" class="btn btn-default" data-dismiss="modal" id="canedit">Cancelar</button>
                    </div>
                </form>
              </div>
            </div>
        </div>
				<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="heading-section text-center animate-box">
							<h2>Categorias</h2>
							<p style="border-bottom: 1px solid rgba(0,0,0,0.2);">Seu tempo, preço e exercícios em melhor qualidade.</p>
						</div>
					</div>
				</div>
				<div class="row animate-box">
					<div class="col-md-10 col-md-offset-1 text-center">
							<?php 
								$consulta_categorias = $conn -> query("SELECT * FROM categoria ORDER BY id");
								if($consulta_categorias->rowCount()>0){
							?>
						<ul class="schedule">
							<?php $cont = 0; while($categorias = $consulta_categorias -> fetch(PDO::FETCH_ASSOC)){
								if($cont==0){
									echo "<li><a href='#' class='active' data-sched='".$cont."'>".$categorias['nome']."</a></li>";
								}else{
									echo "<li><a href='#' data-sched='".$cont."'>".$categorias['nome']."</a></li>";
								}?>
							<?php $cont++; } ?>
						</ul>
					</div>
					<div class="row text-center">

						<div class="col-md-12 schedule-container">
							<?php $consulta_vcategoria = $conn -> query("SELECT * FROM categoria ORDER BY id");
								$cont = 0;
								while ($categoria = $consulta_vcategoria -> fetch(PDO::FETCH_ASSOC)) {
							?>
							<div class="schedule-content <?php if($cont==0){echo 'active';}?>" data-day="<?php echo $cont;?>">
								<!-- col-md-3 -->
								<div class="col-md-4 col-sm-12 col-md-offset-4">
									<div class="program program-schedule">
										<i class="large material-icons" style="color: dimgray;">attach_money</i>
										<!-- <small>Preço de</small> -->
										<h3><?php echo $categoria['valor'];?></h3>
										<span>Mensal</span>
									</div>
								</div>
								
								<!-- <div class="col-md-3 col-sm-6">
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
							</div>-->


							<!-- <div class="schedule-content" data-day="monday">
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
							</div> -->
		
						</div>
						<?php $cont++;}?>
						
					</div>
				</div>
							<?php 
								}else{
									echo "<p>Categorias inexistentes, por favor adicione uma nova!</p>";
								}
							?>
			</div>
			


						

				</div>
			</div>
		</div>
		<!--
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
			$("#painel_del .btn.btn-primary").click(function(){
				if($(this).attr("style")!="background-color:#333;margin:1px;"){
					$(this).attr("style","background-color:#333;margin:1px;");
					valores = $("#painel_del input[name='referencia']").attr("value");
					valor = $(this).attr("value");
					ultivalor = "";
                    cont = "";
                    pass=1;
                    for (var i = 0; i < valores.length; i++) {
                        if(valores[i]!=","){
                            cont+=valores[i];
                        }else{
                            if(cont != valor){
                                if(ultivalor!=""){
                                    ultivalor+=","+cont;
                                }else{
                                    ultivalor+=cont;
                                }
                            }
                            cont="";
                        }
                        if(valores[pass]==null){
                            if(cont != valor){
                                if(ultivalor!=""){
                                    ultivalor+=","+cont;
                                }else{
                                    ultivalor+=cont;
                                }
                            }    
                            cont="";
                        } 
                        pass++;
                    }
                    $("#painel_del input[name='referencia']").attr("value",ultivalor);
				}else{
					$(this).css({"background-color":"yellow","color":"#333","transition":"1s"});
					valores = $("#painel_del input[name='referencia']").attr("value");
					valor = $(this).attr("value");
					if(valores=="" || valores==null || valores==undefined){
						$("#painel_del input[name='referencia']").attr("value",valor);
					}else{
						$("#painel_del input[name='referencia']").attr("value",valores+","+valor);
                	}
					
				}
			});
			$("#candel").click(function(){
				$("#painel_del .btn.btn-primary").attr("style","background-color:#333;margin:1px;");
				$("#painel_del input[name='referencia']").attr("value","");
			});
			$("#painel_edit .btn.btn-primary").click(function(){
				id = $(this).attr("value");
				$.ajax({
					url: 'gera_id.php',
					method: 'post',
					data: {'id':id},
					success:function(data){
						$("#painel_edit button, #painel_edit p").hide();
						$("#painel_edit").append(data);
						$("#edit_categoria .modal-footer").append("<button type='button' id = 'volta' class='btn btn-default pull-left'>Voltar</button>");
					}
				});
			});
			$("#canedit").click(function(){
				$("#painel_edit input[name='nome'],#painel_edit input[name='valor'], #painel_edit input[name='referencia']").remove();
				$("#volta").remove();
				$("#painel_edit button, #painel_edit p").show();
			});
		});
		$(document).on('click','#volta', function(){
			$("#painel_edit input[name='nome'],#painel_edit input[name='valor'], #painel_edit input[name='referencia']").remove();
			$("#volta").remove();
			$("#painel_edit button, #painel_edit p").show();
		});
	</script>
	</body>
</html>

