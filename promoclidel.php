<?php
	session_start();
	require "conexao.inc.php";
	$id_promocao = $_POST['id_promocao'];
	$sql = $conn->query("SELECT * FROM pagamento WHERE id_promocao = '$id_promocao'");
	// $sql = $conn->query("SELECT * FROM pagamento WHERE id_promocao = '$id_promocao'");
	if($sql->rowCount()>0){
		echo "<select name='cliente' title='Selecione um cliente a ser retirado.' autofocus>";
		while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
			$consulta_cliente = $conn->query("SELECT * FROM cliente WHERE id = '".$linha['id_cliente']."'");
			if($consulta_cliente->rowCount()==0){
				echo "<option>Erro, manutenção necessária!</option>";
			}
			while ($cli = $consulta_cliente->fetch(PDO::FETCH_ASSOC)) {
				echo "<option value='".$linha['id_cliente']."'>".$cli['nome']."</option>";
			}
		}
		echo "</select>";
	}else{
		echo "<p style='font-size:100%; text-align:center;'>Sem nenhum cliente a ser retirado!</p>";
	}

?>