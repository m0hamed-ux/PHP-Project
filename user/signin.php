<?php
    if(isset($_COOKIE['user_id'])){
        header('location: dashboard/');
        exit();
    };
    require_once('connect.php');
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
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>ForsaStore - Connexion</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../src/css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://web.archive.org/web/20240801001458cs_/https://cdn.shopify.com/shopifycloud/brochure-iii/production/assets/home-CcMpCWNw.css" />
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600">
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white">Bienvenue</h2>
                <p class="mt-2 text-blue-100">Connectez-vous à votre compte ForsaStore</p>
            </div>

            <form class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-lg" action="" method="POST">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="vous@exemple.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <?php if(isset($_GET['error'])){?>
                        <!-- <div class="p-3 bg-red-200 rounded border border-1 border-red-500">
                            email ou mot de passe incorrect
                        </div> -->
                        <script>
                            Toastify({
                                text: "email ou mot de passe incorrect",
                                duration: 3000,
                                close: true,
                                gravity: "bottom",
                                position: "center",
                                backgroundColor: "red",
                            }).showToast();
                        </script>
                    <?php }?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Se souvenir de moi</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Mot de passe oublié ?</a>
                        </div>
                    </div>
                </div>

                <div>
                    <input type="submit" value="Se connecter" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                </div>
                <p id="line" class="text-center m-0 text-sm text-slate-400">Ou</p>
                <div class="text-center">
                    <p class="text-sm mt-0 text-gray-600">
                        Pas encore de compte ?
                        <a href="signup.php" class="font-bold hover:underline text-blue-600 hover:text-blue-500">S'inscrire</a>
                    </p>
                </div>
            </form>
        </div>
    </main>
</body>

</html>