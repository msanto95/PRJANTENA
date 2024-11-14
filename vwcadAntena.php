<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Implantação</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <style>
        .alert-message {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 9999;
        }
        .form-container {
            margin: 20px 0;
            font-size: 0.85em;
        }
        .card {
            width: 100%;
        }
        .descricao, .longitude, .uf, .data_implantacao {
            width: 100%;
        }
        .no-gutters {
            margin-right: 0;
            margin-left: 0;
        }
        .navbar-nav.ml-auto {
            margin-left: auto;
        }
        .navbar-brand img { 
            height: 50px;
        }
    </style>

<script> 
     async function carregarUFs() {
         const response = await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados');
         const estados = await response.json(); 
         const ufSelect = document.getElementById('uf'); 
         estados.forEach(estado => { 
            const option = document.createElement('option'); 
            option.value = estado.sigla; 
            option.textContent = estado.sigla; 
            ufSelect.appendChild(option);
         });
         } 
         document.addEventListener('DOMContentLoaded', carregarUFs); 
         function enviarFormulario(event) { 
            event.preventDefault(); 
            const formData = new FormData(document.getElementById('formularioAntena')); 
            fetch('processa_cadastro_antena.php', { method: 'POST', body: formData }) 
            .then(response => response.json()) 
            .then(data => { const alerta = document.getElementById('alerta'); 
            alerta.style.display = 'block'; 
            if (data.success) { 
                alerta.className = 'alert alert-success'; 
                alerta.textContent = data.message; 
                document.getElementById('formularioAntena').reset(); 
                } else { 
                    alerta.className = 'alert alert-danger'; 
                    alerta.textContent = data.message; 
                    } 
                    }) 
                    .catch(error => console.error('Erro:', error));
                     } 
</script>
    
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
        <div class="row">
            <div class="col-12 form-wrapper">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-left text-info">Incluir Item</h4></br>
                        <?php
                        session_start();
                        if (isset($_SESSION['error'])) {
                            echo '<div class="alert alert-danger alert-message" role="alert">' . $_SESSION['error'] . '</div>';
                            unset($_SESSION['error']);
                        }
                        if (isset($_SESSION['success'])) {
                            echo '<div class="alert alert-success alert-message" role="alert">' . $_SESSION['success'] . '</div>';
                            unset($_SESSION['success']);
                        }
                        ?>
                        <form action="processa_cadastro_antena.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="descricao">Descrição:</label>
                                <input type="text" name="descricao" id="descricao" class="form-control descricao" maxlength="100" required>
                            </div>
                            
                            <div class="form-group row no-gutters">
                                <div class="col-12 col-md-3 pr-1">
                                    <label for="latitude">Latitude:</label>
                                    <input type="number" step="1" name="latitude" id="latitude" class="form-control latitude" min="-180" max="180" required>
                                </div>                          
                                <div class="col-12 col-md-3 pl-1">
                                    <label for="longitude">Longitude:</label>
                                    <input type="number" step="1" name="longitude" id="longitude" class="form-control longitude" min="-180" max="180" required>
                                </div>
                            </div>
                            <div class="form-group row no-gutters">
                                <div class="col-12 col-md-3 pl-1"> 
                                    <label for="uf">UF:</label> 
                                    <select name="uf" id="uf" class="form-control uf" required> 
                                        <option value="">Selecione a UF</option> 
                                    </select>
                                </div>                                
                                <div class="col-12 col-md-3 pl-1">
                                    <label for="altura">Altura (em metros):</label>
                                    <input type="number" step="0.01" name="altura" id="altura" class="form-control altura" min="0.01" required>
                                </div>                                
                                <div class="col-12 col-md-3 pl-1">
                                    <label for="data_implantacao">Data da Implantação:</label>
                                    <input type="date" name="data_implantacao" id="data_implantacao" class="form-control data_implantacao" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="foto">Foto:</label>
                                <input type="file" name="foto" id="foto" accept="image/png, image/jpeg" class="form-control" required>
                            </div>
                            <div class="form-group d-flex justify-content-between">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Salvar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
