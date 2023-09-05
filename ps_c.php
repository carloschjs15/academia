<?php
	session_start();
	exec("MODE COM3 BAUD=9600 PARITY=n DATA=8 XON=on STOP=1");
	$fp = fopen("COM3", 'w+');
	// Escreve na porta
	fwrite($fp, '1');
	// Fecha a comunicação serial
	fclose($fp);
	session_destroy();
	echo "<script>window.location = 'ps_clientes.php';</script>";
?>