<?php
    if(!isset($_COOKIE['user_id'])){
        header('location: ../signin.php');
    } else {
        require_once('../connect.php');
        $data = $connexion->prepare('SELECT * FROM users WHERE session_ID = ?');
        $data->execute(array($_COOKIE['user_id']));
        $userdata = $data->fetch();
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- <link rel="stylesheet" href="https://web.archive.org/web/20240801001458cs_/https://cdn.shopify.com/shopifycloud/brochure-iii/production/assets/home-CcMpCWNw.css" /> -->
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
                            <a href="#" class="bg-gray-100 text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
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
        <?php 
            $stores = $connexion->prepare('SELECT * FROM stores WHERE user_id = ?');
            $stores->execute(array($userdata['id']));
            $mystore = $stores->fetch();
            if($stores->rowCount() > 0){
        ?>
        <main id="dashboard" class="h-screen p-8 w-full bg-gray-100">
            <?php print_r($mystore);?>
        </main>
        <?php }else{ ?>
        <main id="no-store-yet" class="h-screen p-8 w-full bg-gray-100">
            <div class="w-[75%] mx-auto">
                <h1 class="font-medium text-lg">Préparez-vous à vendre</h1>
                <p class="text-gray-600">Voici un guide pour commencer. À mesure que votre entreprise se développe, vous recevrez ici de nouveaux conseils et des informations pertinentes.</p>
            </div>
            <form action="creerStore.php" class="w-[75%] p-4 mx-auto mt-4  rounded-lg border border-1 border-slate-400 border-opacity-50 shadow-sm bg-white">
                <div class="flex p-1 rounded bg-gray-100 mb-1">
                    <div class="mr-2">
                        <i class="fas fa-check-circle text-gray-300 text-md"></i>
                    </div>
                    <div class="w-full">
                        <h1 class="font-medium text-gray-900 text-sm ">Nommez votre boutique</h1>
                        <p class="text-sm text-gray-500 mt-1">Choisissez un nom qui reflète votre marque et votre activité</p>
                        <input type="text" name="store_name" id="store_name" class="my-1 text-xs block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Entrez le nom de votre boutique" required>
                        <input type="submit" value="créer" class="px-3 py-1 text-white rounded text-sm  bg-black hover:bg-gray-700 cursor-pointer">
                    </div>
                </div>
                <div class="flex p-1">
                    <div class="mr-2">
                        <i class="fas fa-check-circle text-gray-300 text-md"></i>
                    </div>
                    <div>
                        <h1 class="font-medium text-gray-900 text-sm ">Personnalisez votre boutique</h1>
                        
                    </div>
                </div>
                <div class="flex p-1">
                    <div class="mr-2">
                        <i class="fas fa-check-circle text-gray-300 text-md"></i>
                    </div>
                    <div>
                        <h1 class="font-medium text-gray-900 text-sm ">Ajoutez vos produits</h1>
                        
                    </div>
                </div>
                <div class="flex p-1">
                    <div class="mr-2">
                        <i class="fas fa-check-circle text-gray-300 text-md"></i>
                    </div>
                    <div>
                        <h1 class="font-medium text-gray-900 text-sm ">Commencez à vendre</h1>
                    </div>
                </div>
            </form>
        </main>
        <?php };?>
    </div>
    

</body>

</html>
