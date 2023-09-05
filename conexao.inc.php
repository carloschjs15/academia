<?php
	try{
	$conn = new PDO('mysql:host=localhost;dbname=academia', 'root', ''); 
	$conn -> exec("SET CHARACTER SET utf8");
	}
	catch(PDOException $e){
		echo "<script> alert('Erro na conexão!'); </script>";
	}	
?>