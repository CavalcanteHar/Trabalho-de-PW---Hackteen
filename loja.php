<?php
// loja.php — Lista de jogos
include 'config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_nome = $_SESSION['usuario_nome'] ?? 'Usuário';

$sql = "SELECT * FROM jogos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <link rel="stylesheet" href="styleLoja.css">
<head>
<meta charset="UTF-8">
<title>Expance Launch</title>

</head>
<body>

<header>
  <h1>Expance Launch</h1>
  <div>
    <span class="usuario">Bem-vindo, <?php echo htmlspecialchars($usuario_nome); ?></span>
    <a href="logout.php">Sair</a>
  </div>
</header>

<div class="container">
  <h2 style="color:#a3dcff; margin-bottom:20px;">Jogos disponíveis</h2>

  <div class="grid-jogos">
    <?php if ($resultado && $resultado->num_rows > 0): ?>
      <?php while ($jogo = $resultado->fetch_assoc()): ?>
        <div class="jogo-card">
          <img src="imagens/<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="<?php echo htmlspecialchars($jogo['nome']); ?>">
          <div class="jogo-info">
            <h3><?php echo htmlspecialchars($jogo['nome']); ?></h3>
            <p><?php echo htmlspecialchars($jogo['descricao']); ?></p>
            <div class="preco">R$ <?php echo number_format($jogo['preco'], 2, ',', '.'); ?></div>
            <form action="comprar.php" method="post">
              <input type="hidden" name="id_jogo" value="<?php echo (int)$jogo['id']; ?>">
              <button type="submit" class="botao-comprar">Comprar</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="color:#9fb8cc;">Nenhum jogo disponível no momento.</p>
    <?php endif; ?>
  </div>
</div>

<footer>
  © <?php echo date('Y'); ?> Expance Launch — Todos os direitos reservados
</footer>

</body>
</html>
