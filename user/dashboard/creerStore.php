<?php
    if(!isset($_COOKIE['user_id'])){
        header('location: ../signin.php');
    } else {
        require_once('../connect.php');
        $data = $connexion->prepare('SELECT * FROM users WHERE session_ID = ?');
        $data->execute(array($_COOKIE['user_id']));
        $userdata = $data->fetch();
    }
    if(isset($_POST['store_name'])){
        $name = $_POST['store_name'];
        $description = $_POST['store_description'];
        $logo_url = '';
        if (isset($_FILES['store_logo'])) {
            $logo_url = "images/".$_FILES['store_logo']['name'];
            move_uploaded_file($_FILES['store_logo']['tmp_name'], $logo_url);
        }
        $domain = strtolower(str_replace(' ', '-', $name));
        if(empty($name)) {
            header('location: ?error=empty_name');
            exit();
        }
        $check = $connexion->prepare('SELECT * FROM stores WHERE domain = ?');
        $check->execute(array($domain));
        if($check->rowCount() > 0) {
            header('location: ?error=domain_exists');
            exit();
        }
        $req = $connexion->prepare('INSERT INTO stores(user_id, name, description, logo_url, domain) VALUES(?,?,?,?,?)');
        $req->execute(array($userdata['id'],$name, $description, $logo_url, $domain));
        $store_id = $connexion->lastInsertId();
        $req2 = $connexion->prepare('INSERT INTO store_infos(store_id) VALUES(?)');
        $req2->execute(array($store_id));
        $store_folder = "../../stores/".$domain;
        if (!file_exists($store_folder)) {
            mkdir($store_folder, 0777, true);
            $template_dir = "../../template";
            $files = scandir($template_dir);
            foreach($files as $file) {
                if($file != "." && $file != "..") {
                    copy($template_dir . "/" . $file, $store_folder . "/" . $file);
                }
            }
        }
        header('location: costumize.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>ForsaStore - Dashboard</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../src/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- <link rel="stylesheet" href="https://web.archive.org/web/20240801001458cs_/https://cdn.shopify.com/shopifycloud/brochure-iii/production/assets/home-CcMpCWNw.css" /> -->
    <script>
        function replaceSpaces(){
            document.getElementById('store_domain').value = document.getElementById('store_domain').value.replace(/\s+/g, '-').toLowerCase();
            document.getElementById('store_domain').value = document.getElementById('store_domain').value.replace('--', '-').toLowerCase();
        }
    </script>
</head>

<body class="min-h-screen h-screen max-h-screen  flex flex-col bg-black">
    <div class="bg-black h-auto w-full py-3 px-4 flex justify-between">
        <div class="flex items-center flex-shrink-0">
            <span class="italic font-bold text-white text-2xl">ForsaStore</span>
        </div>
        <div>
            <div class="cursor-pointer">
                <span class="rounded">
                    <i class="fa-regular fa-bell text-white text-lg cursor-pointer hover:text-gray-300 transition-colors"></i>
                </span>
                <span onclick="window.location.href='../deconnect.php'" class="ml-2 rounded cursor-pointer hover:opacity-80">
                    <span class="bg-blue-400 p-1 ml-1 rounded text-white font-medium text-sm"><?=$userdata['first_name'][0]?><?=$userdata['last_name'][0]?></span> 
                    <span class="font-medium text-white ml-1 text-sm"><?=$userdata['first_name']?> <?=$userdata['last_name']?></span> 
                </span>
            </div>
        </div>
    </div>
    <div class="flex flex-1 overflow-hidden rounded-t-lg ">
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex flex-col h-0 flex-1 bg-white border-r border-gray-200">
                    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                        <nav class="flex-1 px-2 space-y-1">
                            <a href="index.php" class="bg-gray-100 text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-home mr-3 text-gray-500"></i>
                                Dashboard
                            </a>
                            <a href="#" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-shopping-cart mr-3 text-gray-400"></i>
                                Commandes
                            </a>
                            <a href="#" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-box mr-3 text-gray-400"></i>
                                Produits
                            </a>
                            <a href="#" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-users mr-3 text-gray-400"></i>
                                Clients
                            </a>
                            <a href="#" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-chart-line mr-3 text-gray-400"></i>
                                Analyses
                            </a>
                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex border-gray-200 p-2">
                        <div class="flex items-center">
                            <a href="#" class="text-gray-600 w-full hover:bg-gray-50 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-cog mr-3 text-gray-400"></i>
                                Paramètres
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <main class="h-screen overflow-auto p-8 pb-16 w-full bg-gray-100">
        <form action="" class="w-[75%] p-4 mx-auto mt-4  rounded-lg border border-1 border-slate-400 border-opacity-50 shadow-sm bg-white" method="POST" enctype="multipart/form-data">
        <div class="space-y-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Créer votre boutique</h2>
                <p class="mt-1 text-sm text-gray-500">Remplissez les informations ci-dessous pour créer votre boutique en ligne.</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="store_name" class="block text-sm font-medium text-gray-700">Nom de la boutique</label>
                    <input type="text" name="store_name" id="store_name" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex: Ma Boutique" value="<?= (isset($_GET['store_name']))?$_GET['store_name']:''?>">
                </div>

                <div>
                    <label for="store_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="store_description" id="store_description" rows="3"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Décrivez votre boutique en quelques mots..."></textarea>
                </div>

                <div>
                    <label for="store_domain" class="block text-sm font-medium text-gray-700">Domaine personnalisé</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 py-2 border border-r-0 border-gray-300 bg-gray-50 text-gray-500 rounded-l-md">
                            forsastore.com/store/
                        </span>
                        <input type="text" name="store_domain" id="store_domain"
                            class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="votre-boutique" oninput="replaceSpaces()">
                        
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Logo de la boutique</label>
                    <div class="mt-1 flex items-center">
                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                        <input type="file" name="store_logo" id="store_logo" accept="image/*" class="ml-5 py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Créer la boutique
                </button>
            </div>
        </div>
        </form>
        </main>
    </div>
</body>

</html>
