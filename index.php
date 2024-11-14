<?php 
    session_start(); 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>  
    <link rel="stylesheet" href="css/estilo.css"></link>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style> 
       .alert-message { 
                         position: fixed; 
                         top: 0; 
                         left: 0; 
                         width: 100%; 
                         margin-bottom: 0; 
                         text-align: center; 
                         z-index: 9999;
                      }    
 
       .flex-container { 
                         display: flex; 
                         justify-content: space-between; 
                         align-items: center; 
                       }      

      .form-check-label { 
                        margin-bottom: 0; 
                       }                       

    </style>
</head>
<body>
    <div id="login">
 
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">

                    <!-- Verificar se há uma mensagem de erro na sessão e exibi-la --> 
                    <?php if (isset($_SESSION['error'])) { 
                        echo '<div class="alert alert-danger alert-message" role="alert">' . $_SESSION['error'] . '</div>'; 
                        // Remover a mensagem da sessão após exibi-la
                         unset($_SESSION['error']); 
                         } 
                    ?>
                        <form id="login-form" class="form" action="processa_login.php" method="post">

                            <h4 class="text-center text-info">Login</h4>
                            <div class="form-group">
                                <label for="email" class="text-info">Email:</label><br>
                                <input type="text" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="senha" class="text-info">Senha:</label><br>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>

                            <div class="form-group flex-container"> 
                              <label for="remember-me" class="text-info"> 
                                <span>Lembrar senha</span> 
                                <span><input id="remember-me" name="remember-me" type="checkbox"></span> 
                                <span><a href="vwUsuario.php" class="text-info ml-3">Novo usuário</a></span> 
                              </label>
                            </div>                            

                            <div class="form-group">    
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Acessar">
                            </div>
       
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script> 
       setTimeout(function() { var alert = document.querySelector('.alert-message'); 
       if (alert) { alert.remove(); } }, 5000); 
    </script>
</body>
</html>