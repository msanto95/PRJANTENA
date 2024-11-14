<?php
session_start();
require 'Conexao.php';
$pdo = Database::conexao();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM tb_antena WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$antena = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $uf = $_POST['uf'];
    $altura = $_POST['altura'];
    $data_implantacao = $_POST['data_implantacao'];

    $update_stmt = $pdo->prepare("UPDATE tb_antena SET ds_nome = :descricao, nu_latitude = :latitude, nu_longitude = :longitude, ds_uf = :uf, nu_altura = :altura, dt_implantacao = :data_implantacao WHERE id = :id");
    $update_stmt->bindParam(':descricao', $descricao);
    $update_stmt->bindParam(':latitude', $latitude);
    $update_stmt->bindParam(':longitude', $longitude);
    $update_stmt->bindParam(':uf', $uf);
    $update_stmt->bindParam(':altura', $altura);
    $update_stmt->bindParam(':data_implantacao', $data_implantacao);
    $update_stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Registro atualizado com sucesso!";
        header("Location: vwAntena.php");
        exit();
    } else {
        $_SESSION['error'] = "Erro ao atualizar o registro!";
    }
}

function getEstados() {
     $api_url = "https://servicodados.ibge.gov.br/api/v1/localidades/estados"; 
     $estados = file_get_contents($api_url); 
     return json_decode($estados, true); 
    } $estados = getEstados();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Antena</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <style>
    .navbar-brand img { 
            height: 50px;
        }
    </style>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><img src="img/antena.png" alt="Logo Antena"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="vwAntena.php">Voltar</a> 
                    </li>
                </ul>
            </div>
    </nav>
    <div class="container mt-4">
        <h4 class="text-left text-info">Alterar Antena</h4>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-success alert-message">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        <form action="alterar_antena.php?id=<?= $id ?>" method="post">
            <div class="form-group">
                <label for="id">Código:</label>
                <input type="text" class="form-control" id="id" name="id" value="<?= str_pad($antena['id'], 6, '0', STR_PAD_LEFT) ?>" disabled>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" name="descricao" id="descricao" class="form-control descricao" value="<?= $antena['ds_nome'] ?>" required>
            </div>
            
            <div class="form-group row no-gutters">
                <div class="col-12 col-md-3 pr-1">
                    <label for="latitude">Latitude:</label>
                    <input type="number" step="1" name="latitude" id="latitude" class="form-control latitude" min="-180" max="180" value="<?= $antena['nu_latitude'] ?>" required>
                </div>                          
                <div class="col-12 col-md-3 pl-1">
                    <label for="longitude">Longitude:</label>
                    <input type="number" step="1" name="longitude" id="longitude" class="form-control longitude" min="-180" max="180" value="<?= $antena['nu_longitude'] ?>" required>
                </div>
            </div>
            
            <div class="form-group row no-gutters">
                <div class="col-12 col-md-3 pl-1">
                    <label for="uf">UF:</label> 
                    <select class="form-control" id="uf" name="uf" required> 
                        <option value="">Selecione a UF</option> 
                        <?php foreach ($estados as $estado) : ?> 
                            <option value="<?= $estado['sigla'] ?>" <?= $estado['sigla'] == $antena['ds_uf'] ? 'selected' : '' ?>><?= $estado['sigla'] ?></option> 
                            <?php endforeach; ?> 
                        </select> 
                </div>
                <div class="col-12 col-md-3 pl-1">
                    <label for="altura">Altura:</label>
                    <input type="text" class="form-control" id="altura" name="altura" value="<?= $antena['nu_altura'] ?>" required>
                </div>
                <div class="col-12 col-md-3 pl-1">
                    <label for="data_implantacao">Data de Implantação:</label>
                    <input type="date" class="form-control" id="data_implantacao" name="data_implantacao" value="<?= $antena['dt_implantacao'] ?>" required>
                </div>                
            </div>


            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>

    <script>
    setTimeout(function() {
        var alert = document.querySelector('.alert-message');
        if (alert) {
            alert.remove();
        }
    }, 5000);
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>
