<?php
	session_start();
	require "conexao.inc.php";
	// Pasta onde o arquivo vai ser salvo
	$_UP['pasta'] = $_POST['caminho'];
	// Tamanho máximo do arquivo (em Bytes)
	$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
	// Array com as extensões permitidas
	$_UP['extensoes'] = array('pdf'); 
	// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
	$_UP['renomeia'] = true;
	// Array com os tipos de erros de upload do PHP
	$_UP['erros'][0] = 'Não houve erro';
	$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
	$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
	$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
	$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
	// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
	if ($_FILES['perfil']['error'] != 0) {
		die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['perfil']['error']]);
		exit; // Para a execução do script
	}
	// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
	// Faz a verificação da extensão do arquivo

	// $ponto = '.';
	// $xxx = $_FILES['perfil']['name'];
	// $extensao = strtolower(end(explode($ponto, $xxx)));
	// if (array_search($extensao, $_UP['extensoes']) === false) {
	//   echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif";
	//   exit;
	// }
	// Faz a verificação do tamanho do arquivo
	if ($_UP['tamanho'] < $_FILES['perfil']['size']) {
		echo "<script>alert('O imagem enviada é muito grande, envie imagens de até 2Mb.');</script>";
		exit;
	}
	// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
	// Primeiro verifica se deve trocar o nome do arquivo
	if ($_UP['renomeia'] == true) {
		// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
		$nome_final = 'perfil.jpg';
	} else {
		// Mantém o nome original do arquivo
		$nome_final = $_FILES['perfil']['name'];
	}

	// Depois verifica se é possível mover o arquivo para a pasta escolhida
	if (move_uploaded_file($_FILES['perfil']['tmp_name'], $_UP['pasta'] . $nome_final)) {
		// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
		// echo("<script>alert('Imagem enviada com sucesso!');</script>");
	} else {
		// Não foi possível fazer o upload, provavelmente a pasta está incorreta
		echo "<script>alert('Não foi possível enviar a imagem, tente novamente')</script>";
	}
?>