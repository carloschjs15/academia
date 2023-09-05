<?php
	session_start();
	require "conexao.inc.php";
	$mostra_ultimo_registro_cli = $conn->query("SELECT MAX(id) FROM historico WHERE cliente = '".$_POST['clidel']."'");
	while($urecli = $mostra_ultimo_registro_cli->fetch(PDO::FETCH_ASSOC)){
		$verifica_entrada = $conn->query("SELECT * FROM historico WHERE id = '".$urecli['MAX(id)']."'");
		while($vfe = $verifica_entrada->fetch(PDO::FETCH_ASSOC)){
			if($vfe['hora_saida']!='00:00:00'){
				$cancela_saida = $conn->exec("UPDATE historico SET hora_saida = '00:00:00' WHERE id = '".$urecli['MAX(id)']."'");
			}else{
				$deleta_cliente = $conn->exec("DELETE FROM historico WHERE id = '".$urecli['MAX(id)']."'");
			}
		}
	}
	session_destroy();
?>