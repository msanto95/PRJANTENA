<?php
require 'Conexao.php';
require 'Usuario.php';

$pdo = Database::conexao();
$usuario = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar-senha'];

    // Valida os dados do formulário
    $validacao = $usuario->validarDados($nome, $email, $senha, $confirmar_senha);
    if ($validacao === true) {
        // Se os dados forem válidos, cadastra o usuário
        $mensagem = $usuario->cadastrarUsuario($nome, $email, $senha);
        if (strpos($mensagem, 'sucesso') !== false) {
            header('Location: index.php?success=1');
            exit();
        } else {
            header('Location: usuario.php?error=1');
            exit();
        }
    } else {
        // Se os dados não forem válidos, redireciona de volta com erro
        header('Location: usuario.php?error=1');
        exit();
    }
}
?>
