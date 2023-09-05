<?php 
	//Continuar após terminar o cadastro de clientes já calculando data de pagamento
	session_start();
	include "conexao.inc.php";
	$id = $_POST['id'];
	$cliente = $_POST['cliente'];
	$consulta_cli = $conn->query("SELECT * FROM promocao WHERE id = '$id'");
	while($linha = $consulta_cli->fetch(PDO::FETCH_ASSOC)){
		$clientes = $linha['clientes'];
		$desconto = (100-$linha['desconto'])/100;
	}
	$consulta_cliente = $conn->query("SELECT * FROM cliente WHERE id = '$cliente'");
	while ($c = $consulta_cliente->fetch(PDO::FETCH_ASSOC)) {
		$consulta_categoria = $conn->query("SELECT * FROM categoria WHERE id = '".$c['categoria']."'");
		while ($v = $consulta_categoria->fetch(PDO::FETCH_ASSOC)) {
			$valor = $v['valor']*$desconto;
			$valor_normal = $v['valor'];
		}
	}
	switch ($_POST['acao']) {
		case 'add':
			$ver = true;
			$verifica_promo = $conn->query("SELECT * FROM pagamento WHERE id_cliente='$cliente' AND id_promocao=0");
			if($verifica_promo->rowCount()>0){
				if($clientes=="0"){
					$sql = $conn->exec("UPDATE promocao set clientes = '$cliente' WHERE id = '$id'");
					$sql_paga = $conn->exec("UPDATE pagamento set id_promocao = '$id', valor = '$valor' WHERE id_cliente = '$cliente'");
					if($sql){
						echo "<script>alert('Cliente inserido na promoção!'); window.location = 'promocoes.php';</script>";
					}else{
						echo "<script>alert('Erro ao inserir cliente!'); window.location = 'promocoes.php';</script>";
					}
				}else{
					$verifica = "";
					if($clientes==$cliente){
						echo "<script>alert('Cliente já inserido na promoção!'); window.location = 'promocoes.php';</script>";
					}else{
						for($i = 0; $i < strlen($clientes); $i++){
							if($clientes[$i]!=","){
								$verifica.=$clientes[$i];
							}else{
								if($verifica==$cliente){
									echo "<script>alert('Cliente já inserido na promoção!'); window.location = 'promocoes.php';</script>";
									$ver=false;
								}
								$verifica="";
							}
						}
						if($verifica==$cliente){
							echo "<script>alert('Cliente já inserido na promoção!'); window.location = 'promocoes.php';</script>";
									$ver=false;
						}
						if($ver){
							$atu = $clientes.",".$cliente;
							$sql = $conn->exec("UPDATE promocao set clientes = '$atu' WHERE id = '$id'");
							$sql_paga = $conn->exec("UPDATE pagamento set id_promocao = '$id', valor = '$valor' WHERE id_cliente = '$cliente'");
							if($sql){
								echo "<script>alert('Cliente inserido com sucesso!'); window.location = 'promocoes.php';</script>";
							}else{
								echo "<script>alert('Erro ao inserir cliente!'); window.location = 'promocoes.php';</script>";
							}
						}
					}
				}
			}else{
				echo "<script>alert('Acesso negado, cliente só pode estar incluso em uma promoção no mês.'); window.location = 'promocoes.php';</script>";
			}
			break;
		case 'del':
			$atualiza_paga = $conn->exec("UPDATE pagamento SET id_promocao = 0, valor = '$valor_normal' WHERE id_cliente='$cliente'");
			$referencia = $cliente;
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
				if($atualiza_paga){
					echo "<script>alert('Cliente retirado da promoção com sucesso!'); window.location = 'promocoes.php';</script>";
				}else{
					echo "<script>alert('Erro ao retirar cliente da promoção!'); window.location = 'promocoes.php';</script>";
				}

			break;
		default:
			# code...
			break;
	}

?>