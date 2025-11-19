<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Criar Conta - Loja Indie</title>
  <link rel="stylesheet" href="style-login.css">
</head>
<body>

<div class="container">

  <!-- LADO ESQUERDO -->
  <div class="form-section">
    <h2>Criar Conta</h2>
    <p>Junte-se à comunidade e descubra novos jogos incríveis!</p>

    <form action="cadastro_action.php" method="POST">
      <input type="text" name="nome" placeholder="Nome completo" required>
      <input type="email" name="email" placeholder="E-mail" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Cadastrar</button>
    </form>

    <div class="link">
      Já possui conta? <a href="login.php">Entre aqui</a>
    </div>
  </div>

  <!-- LADO DIREITO -->
  <div class="image-section">
    <img src="imagens/cadastro-ilustracao.jpg" alt="Cadastro imagem">
  </div>

</div>

</body>
</html>
