<?php
	session_start();
	require "conexao.inc.php";
	$sql = $conn->query("SELECT * FROM  pagamentosd WHERE data <= '".$_POST['ate']."' AND data >= '".$_POST['de']."' ORDER BY id DESC");
	if($sql->rowCount() == 0){
		echo "<label style='text-align:center;'>Sem registros de pagamentos dentro deste período!</label>";
	}
	while($linha = $sql -> fetch(PDO::FETCH_ASSOC)){
		echo "<table width='100%' class='table table-hover' id='datatables-example'>
							<thead class='thead-default'>
								<th>Cliente</th>
								<th>Valor <label style='float: right;'>Valor Total: "; 
								$vt = 0;
								$contagem = $conn->query("SELECT SUM(valor) FROM pagamentosd WHERE data <= '".$_POST['ate']."' AND data >= '".$_POST['de']."'");
								while($linha = $contagem->fetch(PDO::FETCH_ASSOC)){echo $linha['SUM(valor)'];}
								echo "</label></th>
							</thead>
							<tbody>";
							$entradasaida = $conn->query("SELECT * FROM pagamentosd WHERE data <= '".$_POST['ate']."' AND data >= '".$_POST['de']."' ORDER BY id DESC");
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
							echo "</tbody>
						</table>";
		break;
	}
?>