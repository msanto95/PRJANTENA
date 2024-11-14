<?php

session_start();
require 'Login.php';
require 'Conexao.php'; 
;

$login = new Login();
$pdo = Database::conexao();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if ($login->login($email, $senha)) {
        header("Location: vwAntena.php");
    } else {
        $_SESSION['error'] = "Erro no login. Tente novamente."; 
        header("Location: index.php");
        exit();        
    }
}

?>
