<?php
session_start();
require 'Conexao.php';
$pdo = Database::conexao();

$records_per_page = 8; 
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tb_antena WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registro excluído com sucesso!";
    } else {
        $_SESSION['error'] = "Erro ao excluir o registro!";
    }
    header("Location: vwAntena.php?page=" . $current_page);
    exit();
}

$top5_stmt = $pdo->query("SELECT ds_uf, COUNT(*) as total_antenas FROM tb_antena GROUP BY ds_uf ORDER BY total_antenas DESC LIMIT 5"); 
$top5_uf = $top5_stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM tb_antena ORDER BY id LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$implantacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_stmt = $pdo->query("SELECT COUNT(*) FROM tb_antena");
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Implantação</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCg8KI8X9M2Wwke0CmAVcoAAvm8Ko0_teU&callback=initMap" async defer></script>

    <style>
        .small-font { 
            font-size: 0.8em; 
        }
        .compact-rows { 
            line-height: 1; 
        }
        .navbar-brand img { 
            height: 50px;
        }
        .modal { display: none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; } .modal-content { background-color: #fff; margin: auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; } .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; } .close:hover, .close:focus { color: black; text-decoration:none; cursor: pointer; }       
    </style>
</head>
<body>

<div id="map"></div> 
<script> 
function initMap() {
     var map = new google.maps.Map(document.getElementById('map'), { zoom: 15, center: {lat: <?= $latitude ?>, lng: <?= $longitude ?>} });
      var marker = new google.maps.Marker({ position: {lat: <?= $latitude ?>, lng: <?= $longitude ?>}, map: map }); 
    }
</script>

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
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
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

        <h4 class="text-left text-info">Ranking (5 UF´s com mais quantidades)</h4> 
        <table class="table table-striped table-bordered small-font compact-rows"> 
            <thead class="thead-light"> 
                <tr> 
                    <th>UF</th> 
                    <th>Quantidade de Antenas</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php foreach ($top5_uf as $uf) : ?> 
                    <tr> 
                        <td>
                            <?= $uf['ds_uf'] ?>
                        </td> 
                        <td>
                            <?= $uf['total_antenas'] ?>
                        </td> 
                    </tr> 
                    <?php endforeach; ?> 
                </tbody> 
            </table>

        <h4 class="text-left text-info">Antenas</h4>
        <table class="table table-striped table-bordered small-font compact-rows"> 
            <thead class="thead-light">
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>UF</th>
                    <th>Altura</th>
                    <th>Data de Implantação</th>
                    <th>Foto</th>
                    <th>Mapa</th>
                    <th>Alterar</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implantacoes as $implantacao) : ?>
                <tr>
                    <td><?= str_pad($implantacao['id'], 6, '0', STR_PAD_LEFT) ?></td>
                    <td><?= $implantacao['ds_nome'] ?></td>
                    <td><?= $implantacao['nu_latitude'] ?></td>
                    <td><?= $implantacao['nu_longitude'] ?></td>
                    <td><?= $implantacao['ds_uf'] ?></td>
                    <td><?= $implantacao['nu_altura'] ?></td>
                    <td><?= $implantacao['dt_implantacao'] ?></td>
                    <td><a href="#" class="open-modal" data-img="img/fotos/<?= $implantacao['im_foto'] ?>"><img src="img/imagem.png" alt="Foto" width="20" height="20"></a></td>
                    <td><a href="https://www.google.com/maps?q=<?= $implantacao['nu_latitude'] ?>,<?= $implantacao['nu_longitude'] ?>" target="_blank"><img src="img/mapa.png" alt="Mapa" width="20" height="20"></a></td>
                    <td><a href="alterar_antena.php?id=<?= $implantacao['id'] ?>" class="btn btn-warning btn-sm">Alterar</a></td>
                    <td><a href="vwAntena.php?delete=<?= $implantacao['id'] ?>&page=<?= $current_page ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este item?');">Excluir</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($current_page == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="vwAntena.php?page=1">Primeira</a>
                </li>
                <li class="page-item <?= ($current_page == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="vwAntena.php?page=<?= $current_page - 1 ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                        <a class="page-link" href="vwAntena.php?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="vwAntena.php?page=<?= $current_page + 1 ?>">Próxima</a>
                </li>
                <li class="page-item <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="vwAntena.php?page=<?= $total_pages ?>">Última</a>
                </li>
            </ul>
        </nav>

        <div class="button-container">
            <a href="vwcadAntena.php" class="btn btn-primary">Incluir Item</a>
        </div>        
    </div></br>

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
