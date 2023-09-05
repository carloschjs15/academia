<?php
	session_start();
	include "conexao.inc.php";
	$id = $_POST['id'];
	$sql = $conn->query("SELECT * FROM categoria WHERE id = '$id'");
	while($categoria = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "<input type='text' name='nome' placeholder='Nome' title='Digite o Nome' required autofocus style='width: 66%;' value='".$categoria['nome']."'>
                      <input type='number' name='valor' min='1' step='.05' title='Insira o valor da categoria' required style='width: 33%;' value='".$categoria['valor']."'><input type='hidden' name='referencia' value='".$categoria['id']."'>";
	}
?>