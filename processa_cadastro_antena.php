<?php
require 'Conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST['descricao'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $altura = $_POST['altura'];
    $uf = $_POST['uf'];
    $data_implantacao = $_POST['data_implantacao'];
    $foto = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];

    if (strlen($descricao) < 10 || strlen($descricao) > 100) {
         session_start(); $_SESSION['error'] = "A descrição deve ter entre 10 e 100 caracteres.";
         header("Location: vwcadAntena.php"); exit(); 
    }    

    try {
        $pdo = Database::conexao(); 
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_antena WHERE ds_nome = :descricao"); 
        $stmt->bindParam(':descricao', $descricao); 
        $stmt->execute(); 
        if ($stmt->fetchColumn() > 0) { 
            session_start(); 
            $_SESSION['error'] = "A descrição já existe."; 
            header("Location: vwcadAntena.php"); exit(); 
        }

        move_uploaded_file($foto_tmp, "uploads/" . $foto);
    
        $stmt = $pdo->prepare("INSERT INTO tb_antena (ds_nome, nu_latitude, nu_longitude, ds_uf, nu_altura, dt_implantacao, im_foto) VALUES (:descricao, :latitude, :longitude, :uf, :altura, :data_implantacao, :foto)");
      ///  $stmt = $pdo->prepare("INSERT INTO tb_antena (ds_nome, nu_latitude, nu_longitude, ds_uf, nu_altura, dt_implantacao) VALUES (:descricao, :latitude, :longitude, :uf, :altura, :data_implantacao)");
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':uf', $uf);
        $stmt->bindParam(':altura', $altura);
        $stmt->bindParam(':data_implantacao', $data_implantacao);
        $stmt->bindParam(':foto', $foto);
        
        if ($stmt->execute()) {
            session_start();
            $_SESSION['success'] = "Registro inserido com sucesso!";
            header("Location: vwAntena.php");
            exit();
        } else {
            session_start();
            $_SESSION['error'] = "Erro ao inserir o registro!";
            header("Location: vwcadAntena.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
