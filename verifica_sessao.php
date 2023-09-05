<?php
	session_start();
	require "conexao.inc.php";

	if(isset($_SESSION['passagema'])){
		echo "<script>alert('Entrada aceita!');</script>";
	}else if(isset($_SESSION['saidaa'])){
		echo "<script>alert('Saída aceita!');</script>";
	}else{
		echo "<script>alert('Erro!');</script>";
	}
?>