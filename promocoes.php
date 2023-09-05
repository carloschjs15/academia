	<?php 
	session_start();
	include "conexao.inc.php";
	if(isset($_POST['acao'])){
		switch ($_POST['acao']) {
			case 'add':
				$nome = $_POST['nome'];
				$valor = $_POST['valor'];
				$sql = $conn->exec("INSERT INTO promocao VALUES('default','$nome',0,'$valor')");
				if($sql){
					echo "<script>alert('Promoção adicionada com sucesso!'); window.location = 'promocoes.php';</script>";
				}else{
					echo "<script>alert('Erro ao adicionar promoção!'); window.location = 'promocoes.php';</script>";
				}
				break;

			case 'edit':
				if(!isset($_POST['nome'])){
					echo "<script>alert('Por favor, selecione uma categoria antes de finalizar o processo!'); window.location = 'promocoes.php';</script>";
				}
				$nome = $_POST['nome'];
				$valor = $_POST['valor'];
				$referencia = $_POST['referencia'];
				$muda_clip = $conn->query("SELECT * FROM pagamento WHERE id_promocao = '$referencia'");
				if($muda_clip->rowCount()!=0){
					while ($clip = $muda_clip->fetch(PDO::FETCH_ASSOC)) {
						$desconto_c = $conn->query("SELECT * FROM promocao WHERE id = '$referencia'");
						while ($desconto = $desconto_c->fetch(PDO::FETCH_ASSOC)) {
										//39,6*100 = 3960/88=45
							$valor_nor = ($clip['valor']*100)/(100-$desconto['desconto']);
							$novovd = $valor_nor*((100-$valor)/100);
							$atualiza_clientes_promocao = $conn->exec("UPDATE pagamento SET valor = '$novovd' WHERE id_promocao='$referencia'");
						}	
					}
				}
				$sql = $conn -> exec("UPDATE promocao SET nome = '$nome', desconto = '$valor' WHERE id = '$referencia'");
				if($sql){
					echo "<script>alert('Promoção editada com sucesso!'); window.location = 'promocoes.php';</script>";
				}else{
					echo "<script>alert('Erro ao editar promoção!'); window.location = 'promocoes.php';</script>";
				}
				break;

			case 'del':
				if($_POST['referencia']!=""){
					$id = $_POST['referencia'];
					$referencia="";
					$referenciap = "";
						if($id==""){
							$tipo="";
						}
						if(strlen($id)==1){
							$referencia = $id;
							$referenciap = $id;	
						}else{
							for ($i=0; $i < strlen($id); $i++) { 
					    		if($id[$i]!=","){
					    			$referencia.=$id[$i];
					    			$referenciap.=$id[$i];
				    			}else{
				    				$referencia.="' or id='";
				    				$referenciap.="' or id_promocao='";
				    			}
			    			}
	    			}
				}
				if($_POST['referencia']!=""){
					$muda_clip = $conn->query("SELECT * FROM pagamento WHERE id_promocao = '$referenciap'");
					if($muda_clip->rowCount()!=0){
						while ($clip = $muda_clip->fetch(PDO::FETCH_ASSOC)) {
							$desconto_c = $conn->query("SELECT * FROM promocao WHERE id = '$referencia'");
							while ($desconto = $desconto_c->fetch(PDO::FETCH_ASSOC)) {
											//39,6*100 = 3960/88=45
								$valor_nor = ($clip['valor']*100)/(100-$desconto['desconto']);
								$atualiza_clientes_promocao = $conn->exec("UPDATE pagamento SET valor = '$valor_nor', id_promocao=0 WHERE id_promocao='$referencia'");
							}	
						}
					}
					$sql = $conn -> exec("DELETE FROM promocao WHERE id = '$referencia'");
					if($sql){
						echo "<script>alert('Promoção(ões) deletada(s) com sucesso!'); window.location = 'promocoes.php';</script>";
					}else{
						echo "<script>alert('Erro ao deletar categoria(s)!');window.location = 'promocoes.php';</script>";
					}
				}else{
					echo "<script>alert('Nenhuma categoria a ser deletada!'); window.location = 'promocoes.php';</script>";	
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
								<li><a href="categorias.php">Categorias</a></li>
								<li class="active"><a href="promocoes.php">Promoções</a></li>
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
		    <a class="waves-effect waves-light btn-floating btn-large white" title="Configurar Promoções">
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
                      <h4 class="modal-title">Adicionar Promoção</h4>
                    </div>
                    <div class="modal-body">
                      <input type="text" name="nome" placeholder="Nome" title="Digite o Nome" required autofocus style="width: 77%;">
                      <input type="number" name="valor" min="1" value="1.0" step=".5" max="100" title="Insira a porcentagem desconto" required style="width: 10%;">%
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
                      <h4 class="modal-title">Excluir Promoção(ões)</h4>
                    </div>
                    <div class="modal-body" id="painel_del">
                    	<p style="text-align:center; font-size: 100%;border-bottom: 1px solid rgba(0,0,0,0.2);">Selecione uma promoção a ser deletada.</p>
                    	<?php $categorias_del = $conn -> query("SELECT * FROM promocao ORDER BY id");
                    		if($categorias_del->rowCount()>0){
	                    		while ($cdel = $categorias_del -> fetch(PDO::FETCH_ASSOC)) {
	                    			echo "<button type='button' class='btn btn-primary' style='background-color:#333;margin:1px;' value='".$cdel['id']."'>".$cdel['nome']."</button>";
	                    		}
                    		}else{
                    			echo "<p style='text-align:center; font-size: 100%;'>Sem promoções disponíveis, por favor adicione uma nova!</p>";
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
                      <h4 class="modal-title">Editar Promoção</h4>
                    </div>
                    <div class="modal-body" id="painel_edit">
                    	<p style="text-align:center; font-size: 100%;border-bottom: 1px solid rgba(0,0,0,0.2);">Selecione uma promoção a ser editada.</p>
                    	<?php $categorias_edit = $conn -> query("SELECT * FROM promocao ORDER BY id");
                    		if($categorias_edit->rowCount()>0){
	                    		while ($cdel = $categorias_edit -> fetch(PDO::FETCH_ASSOC)) {
	                    			echo "<button type='button' class='btn btn-primary' style='background-color:#333;margin:1px;' value='".$cdel['id']."'>".$cdel['nome']."</button>";
	                    		}
                    		}else{
                    			echo "<p style='text-align:center; font-size: 100%;'>Sem Promoções disponíveis, por favor adicione uma nova!</p>";
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
        	<div class="modal fade" id="add_cliente_promoc" data-backdrop="static">
	            <div class="modal-dialog">
	              <div class="modal-content">
	                <form method="post" action="insdelpc.php" enctype="multipart/form-data">
	                    <div class="modal-header">
	                      <button type="button" class="close" data-dismiss="modal">&times;</button>
	                      <h4 class="modal-title">Inserir cliente em promoção</h4>
	                    </div>
	                    <div class="modal-body">
	                      <?php 
	                      // $verifica_promo = $conn -> query("SELECT * FROM promocao");
	                      $sql_clientesp = $conn->query("SELECT * FROM cliente ORDER BY nome");
	                      if($sql_clientesp->rowCount()==0){
	                      	echo "<p style='text-align:center; font-size:100%;'>Sem clientes para inserir na promoção!</p>";
	                      }else{
	                      	echo "<p style='text-align:center; font-size:100%;border-bottom:1px solid rgba(0,0,0,0.2);'>Selecione um cliente!</p>";
	                      	echo "<select name='cliente' required title='Selecione um cliente para inserir na promoção!' autofocus>";
	                      	while($clientep = $sql_clientesp->fetch(PDO::FETCH_ASSOC)){
	                      		echo "<option value='".$clientep['id']."'>".$clientep['nome']."</option>";
	                      	}
	                      	echo "</select>";
	                      }
	                      ?>
	                      <input type="hidden" name="id" value="">
	                      <input type="hidden" name="acao" value="">
	                    </div>
	                    <div class="modal-footer">
	                      <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
	                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	                    </div>
	                </form>
	              </div>
	            </div>
	        </div>
	        <!--Começar-->
	        <div class="modal fade" id="del_cliente_promo" data-backdrop="static">
	            <div class="modal-dialog">
	              <div class="modal-content">
	                <form method="post" action="insdelpc.php" enctype="multipart/form-data">
	                    <div class="modal-header">
	                      <button type="button" class="close" data-dismiss="modal">&times;</button>
	                      <h4 class="modal-title">Retirar cliente da promoção</h4>
	                    </div>
	                    <div class="modal-body">
	                      

	                      <input type="hidden" name="id" value="">
	                      <input type="hidden" name="acao" value="">
	                    </div>
	                    <div class="modal-footer">
	                      <button type="submit" class="btn btn-primary yellow" style="color: #333;">Finalizar</button>  
	                      <button type="button" class="btn btn-default" data-dismiss="modal" id="cancre">Cancelar</button>
	                    </div>
	                </form>
	              </div>
	            </div>
	        </div>
				<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="heading-section text-center animate-box">
							<h2>Promoções</h2>
							<!-- <p style="border-bottom: 1px solid rgba(0,0,0,0.2);">Seu tempo, preço e exercícios em melhor qualidade.</p> -->
						</div>
					</div>
				</div>
				<div class="row animate-box">
							<?php 
								$consulta_promocoes = $conn -> query("SELECT * FROM promocao ORDER BY id");
								if($consulta_promocoes->rowCount()>0){
							?>
					<div class="col-md-10 col-md-offset-1 text-center">
						<ul class="schedule">
							<?php 
							$cont = 0; 
							while($categorias = $consulta_promocoes -> fetch(PDO::FETCH_ASSOC)){
								if($cont==0){
									echo "<li><a href='#' class='active' data-sched='".$categorias['id']."'>".$categorias['nome']."</a></li>";
								}else{
									echo "<li><a href='#' data-sched='".$categorias['id']."'>".$categorias['nome']."</a></li>";
								}
								$cont++; 
							} ?>
						</ul>
					</div>
					<div class="row text-center">
						<div class="col-md-12 schedule-container">
							<?php $consulta_vcategoria = $conn -> query("SELECT * FROM promocao ORDER BY id");
								$cont = 0;
								while ($categoria = $consulta_vcategoria -> fetch(PDO::FETCH_ASSOC)) {
							?>
							<div class="schedule-content <?php if($cont==0){echo 'active';}?>" data-day="<?php echo $categoria['id'];?>">
								<!-- col-md-3 -->
								<div class="col-md-3 col-sm-12 col-md-offset-0" style="box-shadow: none !important;">
									<div class="program program-schedule">
										<i class="large material-icons" style="color: dimgray;">attach_money</i>
										<!-- <small>Preço de</small> -->
										<h3><?php echo $categoria['desconto']."%";?></h3>
										<span>Desconto Mensal</span>
									</div>
								</div>
								<div class="col-md-7 col-sm-12 col-md-offset-0" style="height: 400px;overflow-y: auto;-webkit-box-shadow: none;-moz-box-shadow:none;box-shadow: none !important;">
									<div class="program program-schedule">
										<table width="100%" class="table table-hover" id="datatables-example">
			                                <thead class="thead-default">
			                                    <tr>
			                                        <th>Nome</th>
			                                        <!-- <th>Data de Nascimento</th> -->
			                                        <th>Categoria</th>
			                                        <th>Email</th>
			                                        <th>Telefone</th>
			                                        <!-- <th>Bairro</th> -->
			                                        <!-- <th>Rua</th> -->
			                                        <!-- <th>Número</th>  -->
			                                    </tr>
			                                </thead>
			                                <tbody>
			                                	<?php 
													// Separa ids
													$ids = " WHERE ";
													$conta = "";
													$contavirgula = 0;
													for ($i = 0; $i < strlen($categoria['clientes']); $i++) {
								                        if($categoria['clientes'][$i]!=","){
								                            $conta.=$categoria['clientes'][$i];
								                            // echo "<script>alert('".$conta."');</script>";
								                        }else{
								                        	$contavirgula++;
								                        	$ids.="id ='".$conta."' or "; 
								                        	// echo "<script>alert('".$contavirgula."');</script>"; 
								                        	$conta = "";
								                     	}
								                    }
								                    // echo "<script>alert('".."');</script>";
								                    if($categoria['clientes']=="0"){
								                    	$ids .= " id = '0'";
								                    	// echo "<script>alert('teste');</script>";
								                    }else if($contavirgula==0){
								                    	$ids.="id ='".$conta."' ORDER BY nome";
								                    	// $ids = substr($ids, 0,-4);
								                    }else{
								                    	$ids.="id ='".$conta."' ORDER BY nome"; 
								                    	// $ids = substr($ids, 0,-4);
								                    }
								                    // echo "<script>alert('".$categoria['clientes']."');</script>";
													$sql_cli_prom = $conn->query("SELECT * FROM cliente".$ids);
													if($sql_cli_prom->rowCount()>0){
													while($cliente = $sql_cli_prom->fetch(PDO::FETCH_ASSOC)){
													// $cliente['data_nascimento'] = DateTime::createFromFormat('Y-m-d', $cliente['data_nascimento'])->format('d/m/Y');
												?>
			                                    <tr class="odd gradeX" <?php echo "value='".$cliente['id']."'";?> data-toggle="modal" data-target="#">
			                                        <td class="center"><?php echo $cliente['nome'];?></td>
			                                        <td class="center"><?php $categorias = $conn->query("SELECT * FROM categoria WHERE id = '".$cliente['categoria']."'");while($catego = $categorias->fetch(PDO::FETCH_ASSOC)){echo $catego['nome'];}?></td>
			                                        <td class="center"><?php echo $cliente['email'];?></td>
			                                        <td class="center"><?php echo $cliente['telefone'];?></td>
			                                         
			                                    </tr>
			                                    <?php
			                                		}
			                                	}
			                                	?>
			                                </tbody>
			                            </table> 
									</div>
								</div>
								<div class="col-md-2 col-sm-12 col-md-offset-0" style="box-shadow: none !important;">
									<div class="program program-schedule"><!--program program-schedule btn btn-default black-->
										<button class="btn btn-default black" style="width: 120px;" value="<?php echo $categoria['id'];?>" data-toggle="modal" data-target="#add_cliente_promoc"><i class="large material-icons" style="color: dimgray;font-size: 125%;vertical-align: middle;">person_add</i> Inserir</button>
										<button class="btn btn-default black" style="width: 120px;" value="<?php echo $categoria['id'];?>" data-toggle="modal" data-target="#del_cliente_promo"><i class="large material-icons" style="color: dimgray;font-size: 125%;vertical-align: middle;">remove</i> Retirar</button>
										<!-- <small>Preço de</small> -->
										
										<!-- <span>Inserir/Retirar</span> -->
									</div>
								</div>
		
						</div>
						
						<?php $cont++;}?>
					</div>
				</div>
							<?php 
								}else{
									echo "<p style='text-align:center;font-size:100%;'>Promoções inexistentes, por favor adicione uma nova!</p>";
								}
							?>
			</div>
			


						

				</div>
			</div>
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
					url: 'gera_id_promo.php',
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
			$(".col-md-2.col-sm-12.col-md-offset-0 .program.program-schedule .btn.btn-default.black").click(function(){
				if($(this).attr("data-target")=="#add_cliente_promoc"){
					$("#add_cliente_promoc input[name='acao']").attr("value","add");
					valor = $(this).attr("value");
					$("#add_cliente_promoc input[name='id']").attr("value",valor);
				}else{
					$("#del_cliente_promo input[name='acao']").attr("value","del");
					valor = $(this).attr("value");
					$("#del_cliente_promo input[name='id']").attr("value",valor);
					$.ajax({
						url:"promoclidel.php",
						method:"post",
						data: {"id_promocao":valor},
						success:function(data){
							$("#del_cliente_promo .modal-body").append(data);
						}
					})
				}
			});
			$("#cancre").click(function(){
				$("#del_cliente_promo select").remove();
				$("#del_cliente_promo p").remove();
			});
		});
		$(document).on('click','#volta', function(){
			$("#painel_edit input[name='nome'],#painel_edit input[name='valor'], #painel_edit input[name='referencia'], #painel_edit label").remove();
			$("#volta").remove();
			$("#painel_edit button, #painel_edit p").show();
		});
	</script>
	</body>
</html>

