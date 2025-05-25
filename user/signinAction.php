<?php
    require_once('connect.php');
    print_r($_POST);
    if(isset($_POST['email'])){echo 'test';}else{ echo 'no';};
    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $check = $connexion->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
        $check->execute(array($email, $password));
        echo 'here';
        if($check->rowCount() > 0) {
            echo '1';
            $userdata = $check->fetch();
            session_start();
            $_SESSION['user_id'] = $userdata['session_ID'];
            setcookie('user_id', $userdata['session_ID'], time() + (86400 * 30), "/");
            header('location: dashboard/');
            exit();
        } else {
            header('location: ?error=1');
            exit();
        }
    }
?>