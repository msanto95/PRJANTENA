<!DOCTYPE html>
<html lang="pt-BR">
<head>  
    <link rel="stylesheet" href="css/usuario.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style> 
        .alert-message { 
            margin-bottom: 20px;
        } 
    </style>
</head>
<body>
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                   <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="processa_cadastro.php" method="post">
                            <h4 class="text-center text-info">Novo Usuário</h4>
                            <div class="form-group">
                                <label for="nome" class="text-info">Nome:</label><br>
                                <input type="text" name="nome" id="nome" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="text-info">Email:</label><br>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="senha" class="text-info">Senha:</label><br>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmar-senha" class="text-info">Confirmar Senha:</label><br>
                                <input type="password" name="confirmar-senha" id="confirmar-senha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Cadastrar">
                                <a href="index.php" class="btn btn-secondary btn-md btn-back">Voltar</a> 
                            </div>
                
                            <!-- Exibir mensagem de erro se o parâmetro error estiver na URL -->
                            <?php 
                            if (isset($_GET['error']) && $_GET['error'] == 1) { 
                                echo '<div class="alert alert-danger" role="alert">Erro no cadastro. Tente novamente.</div>'; 
                            } 
                            ?>                                                                                                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
