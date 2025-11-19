<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Loja Indie</title>
  <link rel="stylesheet" href="style-login.css">
</head>
<body>

<div class="container">

  <!-- LADO ESQUERDO -->
  <div class="form-section">
    <h2>Bem-vindo de volta!</h2>
    <p>Entre para acessar sua conta e explorar novos jogos indie.</p>

    <form action="login_action.php" method="POST">
      <input type="email" name="email" placeholder="E-mail" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar</button>
    </form>

    <div class="link">
      Não tem uma conta? <a href="cadastro.php">Crie agora</a>
    </div>
  </div>

  <!-- LADO DIREITO -->
  <div class="image-section">
    <img src="imagens/login-ilustracao.jpg" alt="Login imagem">
  </div>

</div>

</body>
</html>
