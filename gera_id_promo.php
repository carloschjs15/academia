<?php
	session_start();
	include "conexao.inc.php";
	$id = $_POST['id'];
	$sql = $conn->query("SELECT * FROM promocao WHERE id = '$id'");
	while($categoria = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "<input type='text' name='nome' placeholder='Nome' title='Digite o Nome' required autofocus style='width: 77%;' value='".$categoria['nome']."'>
                      <input type='number' name='valor' min='1' step='.5' title='Insira o valor da categoria' required style='width: 10%;' value='".$categoria['desconto']."'><label>%</label><input type='hidden' name='referencia' value='".$categoria['id']."'>";
	}
?>