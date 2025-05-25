<?php
    if(isset($_COOKIE['user_id'])){
        header('location: dashboard/');
    };
    require_once('connect.php');
    if(isset($_POST['first-name'])){
        $firstName = $_POST['first-name'];
        $lastName = $_POST['last-name'];
        $email = $_POST['email'];
        $checkEmail = $connexion->prepare('SELECT email FROM users WHERE email = ?');
        $checkEmail->execute(array($email));
        if($checkEmail->rowCount() > 0) {
            header('location: ?error=1');
            exit();
        }
        $password = $_POST['password'];
        session_start();
        $_SESSION['user_id'] = uniqid();
        setcookie('user_id', $_SESSION['user_id'], time() + (86400 * 30), "/");
        $create = $connexion->prepare('INSERT INTO users(first_name, last_name, email, password, session_id) VALUES(?,?,?,?,?)');
        $create->execute(array($firstName, $lastName, $email, $password, $_SESSION['user_id']));
        header('location: dashboard/');
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>ForsaStore - Inscription</title>
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
                <h2 class="text-3xl font-bold text-white">Créez votre compte</h2>
                <p class="mt-2 text-blue-100">Rejoignez des milliers d'entrepreneurs qui font confiance à ForsaStore</p>
            </div>

            <form class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-lg" action="" method="POST">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first-name" class="block text-sm font-medium text-gray-700">Prénom</label>
                            <div class="mt-1">
                                <input id="first-name" name="first-name" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Jean">
                            </div>
                        </div>
                        <div>
                            <label for="last-name" class="block text-sm font-medium text-gray-700">Nom</label>
                            <div class="mt-1">
                                <input id="last-name" name="last-name" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Dupont">
                            </div>
                        </div>
                    </div>

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
                            <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <label for="password-confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                        <div class="mt-1">
                            <input id="password-confirmation" name="password-confirmation" type="password" autocomplete="new-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Créer un compte
                    </button>
                </div>
                <p id="line" class="text-center m-0 text-sm text-slate-400">Ou</p>
                <div class="text-center">
                    <p class="text-sm mt-0 text-gray-600">
                        Vous avez déjà un compte ?
                        <a href="signin.php" class="font-bold hover:underline text-blue-600 hover:text-blue-500">Se connecter</a>
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        En vous inscrivant, vous acceptez nos
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Conditions d'utilisation</a> et notre
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Politique de confidentialité</a>
                    </p>
                </div>
            </form>
        </div>
    </main>
    <?php if(isset($_GET['error'])){?>
        <!-- <div class="p-3 bg-red-200 rounded border border-1 border-red-500">
            email ou mot de passe incorrect
        </div> -->
        <script>
            Toastify({
                text: "email deja exist",
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "center",
                backgroundColor: "red",
            }).showToast();
        </script>
    <?php }?>
    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password-confirmation').value;        
        if (password !== passwordConfirmation) {
            e.preventDefault();
            Toastify({
                text: "Les mots de passe ne correspondent pas",
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "center",
                backgroundColor: "red",
            }).showToast();
        }
        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!passwordPattern.test(password)) {
            e.preventDefault();
            Toastify({
                text: "Le mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre",
                duration: 3000,
                close: true,
                gravity: "bottom", 
                position: "center",
                backgroundColor: "red",
            }).showToast();
        }
        const email = document.getElementById('email').value;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            e.preventDefault();
            Toastify({
                text: "Veuillez entrer une adresse email valide",
                duration: 3000,
                close: true,
                gravity: "bottom",
                position: "center",
                backgroundColor: "red",
            }).showToast();
        }
        });
    </script>
</body>

</html>