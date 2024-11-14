<?php
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function validarDados($nome, $email, $senha, $confirmar_senha) {
        if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
            return "Todos os campos são obrigatórios.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Email inválido.";
        }

        if ($senha !== $confirmar_senha) {
            return "As senhas não coincidem.";
        }

        return true;
    }

    public function cadastrarUsuario($nome, $email, $senha) {
        
        //$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('INSERT INTO tb_usuario (ds_nome, ds_email, ds_senha) VALUES (:nome, :email, :senha)');
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);

        if ($stmt->execute()) {
            return "Usuário cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar o usuário.";
        }
    }
}
?>
