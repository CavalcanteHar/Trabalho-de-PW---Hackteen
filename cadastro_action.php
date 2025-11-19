<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // segurança

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Conta criada com sucesso!');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }

    $conn->close();
}
?>
