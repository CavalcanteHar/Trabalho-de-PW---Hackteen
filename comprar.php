<?php
// comprar.php (simplificado, corrigido)
include 'config.php';
session_start();

// exige login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// aceita apenas POST com id_jogo
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_jogo'])) {
    header("Location: loja.php");
    exit;
}

$id_jogo = (int) $_POST['id_jogo'];

// buscar jogo pelo id
$stmt = $conn->prepare("SELECT id, nome, descricao, preco, imagem FROM jogos WHERE id = ?");
$stmt->bind_param("i", $id_jogo);
$stmt->execute();
$result = $stmt->get_result();
$jogo = $result->fetch_assoc();
$stmt->close();

if (!$jogo) {
    echo "<script>alert('Jogo não encontrado.'); window.location.href='loja.php';</script>";
    exit;
}

$usuario_nome = $_SESSION['usuario_nome'] ?? 'Usuário';

$jogo_json = json_encode([
    'id' => (int)$jogo['id'],
    'nome' => $jogo['nome'],
    'preco' => number_format($jogo['preco'], 2, ',', '.'),
    'preco_raw' => (float)$jogo['preco'],
    'descricao' => $jogo['descricao'],
    'imagem' => $jogo['imagem']
]);
$usuario_json = json_encode([
    'id' => (int)$_SESSION['usuario_id'],
    'nome' => $usuario_nome
]);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <title>Pagamento — <?php echo htmlspecialchars($jogo['nome']); ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    .compra-container { display: grid; grid-template-columns: 1fr 420px; gap: 24px; align-items: start; max-width:1200px; margin:40px auto; padding:0 20px; }
    .card { background: #0f1620; padding: 20px; border-radius: 12px; border: 1px solid rgba(102,192,244,0.08); box-shadow: 0 6px 18px rgba(0,0,0,0.5); }
    .sec-title { color: #66c0f4; margin-bottom: 12px; font-size: 18px; }
    .product-row { display:flex; gap:16px; align-items:center; }
    .product-row img { width:96px; height:96px; object-fit:cover; border-radius:8px; border:2px solid #66c0f4;}
    .muted { color:#aab7c7; font-size:14px; }
    .price { color:#c7e9ff; font-weight:700; font-size:20px; }

    label { display:block; font-size:13px; margin-bottom:6px; color:#cfefff; }
    input, select { width:100%; padding:10px; border-radius:8px; border:none; background:#213546; color:#e6f7ff; outline: none; margin-bottom:12px; }
    .pay-button { width:100%; padding:12px; border-radius:10px; border:none; background: linear-gradient(90deg,#66c0f4,#417a9b); color:#061022; font-weight:700; cursor:pointer; }
    .payment-methods { display:flex; gap:12px; margin-bottom:12px; }
    .pm { padding:10px 12px; background:#17232c; border-radius:8px; cursor:pointer; border:1px solid transparent; color:#cfefff; }
    .pm.active { border-color:#66c0f4; box-shadow:0 6px 18px rgba(34,87,122,0.12); transform:translateY(-2px); }
    .modal { position:fixed; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.6); z-index:1000; }
    .modal-card { width:460px; background:#0f1620; padding:26px; border-radius:12px; border:1px solid rgba(102,192,244,0.08); text-align:center; }
    @media (max-width:980px){ .compra-container { grid-template-columns: 1fr; } .image-preview{ display:none; } }
  </style>
</head>
<body>

<div class="header" style="max-width:1200px;margin:20px auto;padding:0 20px;">
  <h2 style="color:#66c0f4;">Pagamento</h2>
  <a href="loja.php" class="button-sair" style="float:right;padding:8px 12px;background:#213546;color:#e6f7ff;border-radius:6px;text-decoration:none;">Voltar</a>
</div>

<div class="compra-container">

  <!-- Esquerda: formulário de pagamento -->
  <div class="card">
    <div class="sec-title">Escolha a forma de pagamento</div>

    <div class="payment-methods" id="pm-list">
      <div class="pm active" data-method="card">Cartão de Crédito</div>
      <div class="pm" data-method="pix">Pix</div>
      <div class="pm" data-method="boleto">Boleto</div>
    </div>

    <form id="paymentForm" onsubmit="return false;">
      <div id="cardFields">
        <label>Número do cartão</label>
        <input id="cardNumber" type="text" placeholder="0000 0000 0000 0000" maxlength="23" autocomplete="cc-number">
        <div style="display:flex;gap:10px;">
          <div style="flex:1;">
            <label>Validade</label>
            <input id="cardExpiry" type="text" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp">
          </div>
          <div style="width:120px;">
            <label>CVV</label>
            <input id="cardCvv" type="password" placeholder="123" maxlength="4" autocomplete="cc-csc">
          </div>
        </div>
        <label>Nome no cartão</label>
        <input id="cardName" type="text" placeholder="Nome como no cartão" autocomplete="cc-name">
      </div>

      <div id="pixFields" style="display:none;">
        <p class="muted small">Ao confirmar, será exibido um código Pix (simulado).</p>
        <label>Chave Pix (opcional)</label>
        <input id="pixKey" type="text" placeholder="E-mail, CPF ou chave aleatória (opcional)">
      </div>

      <div id="boletoFields" style="display:none;">
        <p class="muted small">Ao confirmar, será gerado um boleto (simulado).</p>
        <label>CPF do pagador</label>
        <input id="cpfBoleto" type="text" placeholder="000.000.000-00" maxlength="14">
      </div>

      <div style="margin-top:6px;">
        <label>Parcelas (Cartão)</label>
        <select id="parcelas">
          <option value="1">À vista (1x)</option>
          <option value="2">2x sem juros</option>
          <option value="3">3x sem juros</option>
          <option value="6">6x (juros)</option>
        </select>
      </div>

      <div style="margin-top:16px;">
        <button class="pay-button" id="btnPay" onclick="processPayment()">Confirmar pagamento</button>
      </div>
    </form>
  </div>

  <!-- Direita: resumo do pedido -->
  <aside class="card">
    <div class="sec-title">Resumo do Pedido</div>

    <div class="product-row">
      <img src="imagens/<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="">
      <div style="margin-left:10px;">
        <div style="font-weight:700; color:#e7fbff;"><?php echo htmlspecialchars($jogo['nome']); ?></div>
        <div class="muted" style="max-width:220px;"><?php echo htmlspecialchars($jogo['descricao']); ?></div>
      </div>
    </div>

    <hr style="margin:14px 0; border-color: rgba(102,192,244,0.06);" />

    <div style="display:flex; justify-content:space-between; align-items:center;">
      <div class="muted">Comprador</div>
      <div class="muted"><?php echo htmlspecialchars($usuario_nome); ?></div>
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px;">
      <div class="muted">Subtotal</div>
      <div class="price">R$ <?php echo number_format($jogo['preco'],2,',','.'); ?></div>
    </div>

    <div id="parcelInfo" style="margin-top:8px; color:#9fb8cc;">À vista</div>

  </aside>
</div>

<!-- Modal -->
<div id="modal" class="modal" style="display:none;">
  <div class="modal-card">
    <h3 style="color:#66c0f4;">Pagamento confirmado</h3>
    <p id="modalText" style="margin:14px 0; color:#cfefff;"></p>
    <div style="display:flex; gap:10px; justify-content:center; margin-top:16px;">
      <button class="close-btn" onclick="closeModal()" style="background:#2a475e;color:#e6f7ff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer;">Ir para a loja</button>
    </div>
  </div>
</div>

<script>
  const jogo = <?php echo $jogo_json; ?>;
  const usuario = <?php echo $usuario_json; ?>;

  const pmList = document.getElementById('pm-list');
  const pmButtons = pmList.querySelectorAll('.pm');
  const cardFields = document.getElementById('cardFields');
  const pixFields = document.getElementById('pixFields');
  const boletoFields = document.getElementById('boletoFields');
  const parcelas = document.getElementById('parcelas');
  const parcelInfo = document.getElementById('parcelInfo');
  const modal = document.getElementById('modal');
  const modalText = document.getElementById('modalText');

  pmButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      pmButtons.forEach(x => x.classList.remove('active'));
      btn.classList.add('active');
      const method = btn.dataset.method;
      cardFields.style.display = method === 'card' ? 'block' : 'none';
      pixFields.style.display = method === 'pix' ? 'block' : 'none';
      boletoFields.style.display = method === 'boleto' ? 'block' : 'none';
      updateParcelInfo();
    });
  });

  parcelas.addEventListener('change', updateParcelInfo);

  function updateParcelInfo() {
    const p = parseInt(parcelas.value, 10);
    const preco = jogo.preco_raw;
    if (p === 1) {
      parcelInfo.textContent = `À vista — Total: R$ ${formatBR(preco.toFixed(2))}`;
    } else if (p <= 3) {
      parcelInfo.textContent = `${p}x sem juros — ${p}x de R$ ${formatBR((preco/p).toFixed(2))}`;
    } else {
      const total = preco * 1.10;
      const parcela = total / p;
      parcelInfo.textContent = `${p}x — Total com juros: R$ ${formatBR(total.toFixed(2))} — ${p}x de R$ ${formatBR(parcela.toFixed(2))}`;
    }
  }

  updateParcelInfo();

  // Formata inputs visualmente (mas sem validar conteúdo)
  document.getElementById('cardNumber').addEventListener('input', (e) => {
    let v = e.target.value.replace(/\D/g, '').slice(0,16);
    v = v.replace(/(\d{4})(?=\d)/g, '$1 ');
    e.target.value = v;
  });
  document.getElementById('cardExpiry').addEventListener('input', (e) => {
    let v = e.target.value.replace(/\D/g, '').slice(0,4);
    if (v.length >= 3) v = v.replace(/(\d{2})(\d{1,2})/, '$1/$2');
    e.target.value = v;
  });

  function processPayment() {
    const method = document.querySelector('.pm.active').dataset.method;

    // Validação mínima: campos essenciais não vazios (sem checagens reais)
    if (method === 'card') {
      const num = document.getElementById('cardNumber').value.trim();
      const name = document.getElementById('cardName').value.trim();
      if (!num || !name) { alert('Preencha o número do cartão e o nome.'); return; }
    } else if (method === 'boleto') {
      const cpf = document.getElementById('cpfBoleto').value.trim();
      if (!cpf) { alert('Preencha o CPF do pagador.'); return; }
    }

    const orderId = 'PED' + Date.now().toString().slice(-8) + Math.floor(Math.random()*90+10);
    const parcelasVal = parseInt(parcelas.value, 10);
    let total = jogo.preco_raw;
    let summary = '';

    if (parcelasVal === 1) {
      summary = `Pagamento à vista. Total: R$ ${formatBR(total.toFixed(2))}`;
    } else if (parcelasVal <= 3) {
      summary = `${parcelasVal}x sem juros — ${parcelasVal}x de R$ ${formatBR((total/parcelasVal).toFixed(2))}`;
    } else {
      total = total * 1.10;
      summary = `${parcelasVal}x — Total com juros: R$ ${formatBR(total.toFixed(2))}`;
    }

    let methodText = '';
    if (method === 'card') {
      methodText = `Pagamento por Cartão.<br>${summary}`;
    } else if (method === 'pix') {
      const pixCode = '00020126' + Math.floor(Math.random()*1e12).toString().padStart(12,'0');
      methodText = `Pix. Código: <code style="background:#071722;padding:6px;border-radius:6px;color:#66c0f4;">${pixCode}</code><br>${summary}`;
    } else {
      const linha = Math.floor(Math.random()*1e44).toString().padStart(44,'0');
      methodText = `Boleto (simulado). Linha: <code style="background:#071722;padding:6px;border-radius:6px;color:#66c0f4;">${linha}</code><br>${summary}`;
    }

    modalText.innerHTML = `<strong>Pedido #${orderId}</strong><br><small class="muted">Jogo: ${escapeHtml(jogo.nome)}</small><hr style="margin:8px 0;border-color:rgba(102,192,244,0.06);" />${methodText}<p style="color:#9fb8cc;margin-top:10px;"></p>`;
    modal.style.display = 'flex';
  }

  function closeModal(){
    modal.style.display = 'none';
    window.location.href = 'loja.php';
  }

  function formatBR(value) {
    return value.toString().replace('.', ',');
  }

  function escapeHtml(text) {
    return String(text).replace(/[&<>"]/g, function (m) {
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'})[m];
    });
  }
</script>

</body>
</html>
