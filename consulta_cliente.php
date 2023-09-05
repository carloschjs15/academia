<?php 
	session_start();
	include "conexao.inc.php";
	$id = $_POST['id'];
	$sql = $conn -> query("SELECT * FROM cliente WHERE id='$id'");
	$categorias = $conn -> query("SELECT * FROM categoria");
	while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		echo "<input type='file' name='perfil' title='Selecione uma foto do cliente. Recomendável que a largura seja igual a altura.' accept='image/png, image/jpeg, image/jpg' style='width: 100%;'>
						<input type='text' name='nome' placeholder='Nome' title='Digite seu Nome' value='".$linha['nome']."' required autofocus>
                      <input type='date' name='data' title='Selecione sua Data de Nascimento' value='".$linha['data_nascimento']."' required>";
                      echo "<select name='plano' required>";
                      if($categorias->rowCount()==0){
                      	echo "<option>Sem categorias, por favor adicione uma nova!</option>";
                      }else{
	                      while ($categoria = $categorias->fetch(PDO::FETCH_ASSOC)) {
	                      	if($linha['categoria']==$categoria['id']){
	                    		echo "<option value='".$categoria['id']."' selected>".$categoria['nome']."</option>";
	                      	}else{
	                      		echo "<option value='".$categoria['id']."'>".$categoria['nome']."</option>";
	                      	}
	                      }
                	  }
                      echo "</select>
                      <input type='email' name='email' placeholder='Email' title='Digite o Email' value='".$linha['email']."'>
                      <input type='text' name='telefone' placeholder='Telefone' title='(XX) 9XXXX-XXXX' pattern='\([0-9]{2}\) [0-9]{5}-[0-9]{4}' value='".$linha['telefone']."' required>
                      <input type='text' value='".$linha['rua']."' name='rua' placeholder='Rua' title='Digite a Rua' required>
                      <input type='text' name='bairro' value='".$linha['bairro']."' placeholder='Bairro' title='Digite o Bairro' required>
                      <input type='number' name='numero' value='".$linha['numero']."' title='Digite o Número' placeholder='Número' required>
                      <input type='hidden' name='acao' value='edit'>
                      <input type='hidden' name='referencia' value='".$id."'>";
	}
?>