<?php
	session_start();
	require "conexao.inc.php";
	$id = $_POST['id'];
	$sql = $conn->query("SELECT * FROM cliente WHERE id = '$id'");
	while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		echo "<input type='hidden' name='pasta' value='".$linha['perfil']."'><input type='hidden' name='acao' value='anexar'>";
		if($linha['anexo_mensal']==0){
			echo "<input type='file' title = 'Selecione o Anexo Mensal' name='am' accept='.pdf' style='margin:1%;display:inline'> Anexo Mensal";
		}
		if($linha['anexo_trimestral']==0){
			echo "<input type='file' title = 'Selecione o Anexo Trimestral' name='at' accept='.pdf' style='margin:1%;display:inline'> Anexo Trimestral";
		}
		if($linha['anexo_reserva']==0){
			echo "<input type='file' title = 'Selecione o Anexo Reserva' name='ar' accept='.pdf' style='margin:1%;display:inline'> Anexo Reserva";
		}
	}
?>