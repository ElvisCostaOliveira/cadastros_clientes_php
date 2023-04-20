<?php
// Conexão com o banco de dados
$conexao = mysqli_connect("localhost", "usuario", "senha", "banco");

// Verifica se a requisição é para inclusão de novo cliente
if(isset($_POST["acao"]) && $_POST["acao"] == "incluir") {
  // Recebe os dados do formulário
  $nome = $_POST["nome"];
  $email = $_POST["email"];
  $telefone = $_POST["telefone"];
  
  // Insere os dados no banco de dados
  $sql = "INSERT INTO clientes (nome, email, telefone) VALUES ('$nome', '$email', '$telefone')";
  mysqli_query($conexao, $sql);
  
  // Redireciona para a página de listagem de clientes
  header("Location: listar_clientes.php");
  exit();
}

// Verifica se a requisição é para atualização de um cliente
if(isset($_POST["acao"]) && $_POST["acao"] == "atualizar") {
  // Recebe os dados do formulário
  $id = $_POST["id"];
  $nome = $_POST["nome"];
  $email = $_POST["email"];
  $telefone = $_POST["telefone"];
  
  // Atualiza os dados no banco de dados
  $sql = "UPDATE clientes SET nome='$nome', email='$email', telefone='$telefone' WHERE id=$id";
  mysqli_query($conexao, $sql);
  
  // Redireciona para a página de listagem de clientes
  header("Location: listar_clientes.php");
  exit();
}

// Verifica se a requisição é para exclusão de um cliente
if(isset($_GET["acao"]) && $_GET["acao"] == "excluir") {
  // Recebe o ID do cliente a ser excluído
  $id = $_GET["id"];
  
  // Exclui o cliente do banco de dados
  $sql = "DELETE FROM clientes WHERE id=$id";
  mysqli_query($conexao, $sql);
  
  // Redireciona para a página de listagem de clientes
  header("Location: listar_clientes.php");
  exit();
}

// Verifica se a requisição é para edição de um cliente
if(isset($_GET["acao"]) && $_GET["acao"] == "editar") {
  // Recebe o ID do cliente a ser editado
  $id = $_GET["id"];
  
  // Busca os dados do cliente no banco de dados
  $sql = "SELECT * FROM clientes WHERE id=$id";
  $result = mysqli_query($conexao, $sql);
  $cliente = mysqli_fetch_assoc($result);
}

?>

<!-- Formulário de cadastro de cliente -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" name="id" value="<?php if(isset($cliente)) echo $cliente["id"]; ?>">
  <input type="hidden" name="acao" value="<?php if(isset($cliente)) echo "atualizar"; else echo "incluir"; ?>">
  <label for="nome">Nome:</label>
  <input type="text" name="nome" value="<?php if(isset($cliente)) echo $cliente["nome"]; ?>">
  <label for="email">E-mail:</label>
  <input type="email" name="email" value="<?php if(isset($cliente)) echo $cliente["email"]; ?>">
  <label for="telefone">Telefone:</label>
  <input type="tel" name="telefone" value="<?php if(isset($cliente)) echo $cliente["telefone"]; ?>">
  <button type="submit"><?php if(isset($cliente)) echo "Atualizar"; else echo "Salvar"; ?></button>
</form>

<!-- Tabela de listagem de clientes -->
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>E-mail</th>
      <th>Telefone</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Busca os dados dos clientes no banco de dados e exibe na tabela
    $sql = "SELECT * FROM clientes";
    $result = mysqli_query($conexao, $sql);
    while($cliente = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>{$cliente['id']}</td>";
      echo "<td>{$cliente['nome']}</td>";
      echo "<td>{$cliente['email']}</td>";
      echo "<td>{$cliente['telefone']}</td>";
      echo "<td>
              <a href=\"?acao=editar&id={$cliente['id']}\">Editar</a>
              <a href=\"?acao=excluir&id={$cliente['id']}\">Excluir</a>
            </td>";
      echo "</tr>";
    }
    ?>
  </tbody>
</table>

<!--
Observações:
- É necessário substituir "localhost", "usuario", "senha" e "banco" pelas informações corretas para conexão com o banco de dados;
- O código acima não inclui validações de campos obrigatórios, formatos de dados, entre outros. É importante implementar essas validações para garantir a integridade dos dados e a segurança da aplicação;
- A função mysqli_query() está sendo usada diretamente com os dados do formulário. Isso é perigoso e pode deixar a aplicação vulnerável a ataques de SQL injection. É importante sanitizar e validar os dados antes de usá-los em consultas SQL. Existem funções específicas do PHP para isso, como mysqli_real_escape_string() e filter_input(); 
- O código acima poderia ser organizado de forma mais modular e orientada a objetos, mas foi implementado de forma mais simples e direta para fins didáticos.