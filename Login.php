<?php  

    class Login {
        
        public function login($username, $password) {

            $pdo = Database::conexao();
            $stmt = $pdo->prepare('SELECT * FROM tb_usuario WHERE ds_email = :email');        
            $stmt->execute(['email' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);        
    
            if ($password == $user['ds_senha']) {
                $_SESSION['user_id'] = $user['id'];
                return true;
            } else {
                return false;
            }
        }
    
        public function logout() {
            session_start();
            session_destroy();
        }
    
        public function isLoggedIn() {
            session_start();
            return isset($_SESSION['user_id']);
        }
    }

?>