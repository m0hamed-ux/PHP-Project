<?php
    if(!isset($_COOKIE['user_id'])){
        header('location: ../signin.php');
    } else {
        require_once('../connect.php');
        $data = $connexion->prepare('SELECT * FROM users WHERE session_ID = ?');
        $data->execute(array($_COOKIE['user_id']));
        $userdata = $data->fetch();
        $stores = $connexion->prepare('SELECT * FROM stores WHERE user_id = ?');
        $stores->execute(array($userdata['id']));
        if($stores->rowCount() <= 0) {
            header('location: creerStore.php');
            exit();
        }
        $mystore = $stores->fetch();
        $storeInfo = $connexion->prepare('SELECT * FROM store_infos WHERE store_id = ?');
        $storeInfo->execute(array($mystore['id']));
        $storeInfo = $storeInfo->fetch();
    };
    if(isset($_POST['save_changes'])) {
        $heading = $_POST['heading'];
        $subheading = $_POST['sub-heading'];
        $theme = $_POST['theme'];
        $banner = $storeInfo['banner'];
        if (isset($_FILES['banner']['name']) && !empty($_FILES['banner']['name']) && in_array($_FILES['banner']['type'], array('image/jpeg', 'image/png', 'image/jpg', 'image/gif'))) {
            $banner = "images/".$_FILES['banner']['name'];
            move_uploaded_file($_FILES['banner']['tmp_name'], $banner);
        }
        $update = $connexion->prepare('UPDATE store_infos SET heading_text = ?, sub_heading_text = ?, theme = ?, banner = ? WHERE store_id = ?');
        $update->execute(array($heading,$subheading,$theme,$banner,$mystore['id']));
        header('location: costumize.php');
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
    <style>
        *{
            box-sizing: border-box;
        }
        :root{
            --theme-bg: #ffffff;
            --theme-text: #000000;
            --theme-secondary-text: #4b5563;
            --theme-secondary-hover : #111827;
            --theme-primary: #000000;
            --theme-outline: #ffffff;
            --theme-btn-text: #ffffff;
            --theme-card-bg: #ffffff;
            --theme-btn-hover: #333333;
        }
    </style>
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
                <div class="flex flex-col h-0 flex-1 bg-[#ebebeb] border-r border-gray-200">
                    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                        <nav class="flex-1 px-2 space-y-1">
                            <a href="index.php" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
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
                            <?php 
                                if($stores->rowCount() > 0){
                            ?>
                                <h1 class="text-gray-500 w-full font-[550]">Boutiques</h1>
                                <a href="costumize.php" class="bg-gray-100 text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-store mr-3 text-gray-900"></i>
                                    <?=$mystore['name']?>
                                </a>
                            <?php } else {?>
                                <h1 class="text-gray-500 w-full font-[550]">Boutiques</h1>
                                <a href="creerStore.php" class="bg-gray-100 text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-store mr-3 text-gray-900"></i>
                                    Ajouter une boutique
                                </a>
                            <?php }?>
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
        <main class="h-screen overflow-hidden w-full bg-gray-100 flex">
            <section class="flex-1 h-screen overflow-auto pb-12">
                <div class="max-w-4xl mx-auto bg-[--theme-bg] text-[--theme-text] shadow-lg">
                    <header class="border-b border-[--theme-outline] p-4 flex items-center gap-6">
                        <div class="flex items-center space-x-4">
                            <h1 class="text-xl font-semibold"><img class="w-5 h-5 inline-block mr-1" src="<?=$mystore['logo_url']?>" alt=""><?=(isset($mystore['name']))?$mystore['name']:'My store'?></h1>
                        </div>
                        <nav class="flex space-x-6">
                            <a href="#" class="text-[--theme-secondary-text] hover:text-[--theme-secondary-hover]">Accueil</a>
                            <a href="#" class="text-[--theme-secondary-text] hover:text-[--theme-secondary-hover]">Boutique</a>
                            <a href="#" class="text-[--theme-secondary-text] hover:text-[--theme-secondary-hover]">À propos</a>
                            <a href="#" class="text-[--theme-secondary-text] hover:text-[--theme-secondary-hover]">Contact</a>
                        </nav>
                        <div class="flex space-x-6 ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                            </svg>
                            
                        </div>
                    </header>
                    
                    <div id="banner-img" class="relative h-[500px] bg-cover bg-center" 
                         style="background-image: url('<?=$storeInfo['banner']?>')">
                        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                        <div class="relative h-full flex flex-col items-center justify-center text-white text-center px-4">
                            <h2 class="text-4xl font-bold mb-4" id="heading-text"><?=$storeInfo['heading_text']?></h2>
                            <p class="text-xl mb-8" id="sub-heading-text"><?=$storeInfo['sub_heading_text']?></p>
                            <button class="bg-white text-gray-900 px-8 py-3 rounded-md font-medium hover:bg-gray-100 transition-colors">
                                Acheter Maintenant
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <h2 class="text-2xl font-semibold mb-6">Featured Products</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Product Card 1 -->
                            <div class="bg-[--theme-card-bg] rounded-lg shadow-md overflow-hidden">
                                <div class="h-48 bg-gray-200">
                                    <svg class="placeholder-svg" preserveAspectRatio="xMidYMid slice" width="100%" viewBox="0 0 449 448" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_662_1337)"><path d="M448.04 0H.04v448h448V0Z" fill="#F2F2F2"></path><path d="m354.57 158.19-33.31-35.87a59.971 59.971 0 0 0-32.2-18.01l-20.99-4.2c-2.72-.49-5.45-.93-8.17-1.33l-.01.01v-.01c-1.29-.21-2.58-.31-3.88-.29-1.3.01-2.6.14-3.88.38l-7.25 1.36-7.08 1.33c-4.54.85-9.13 1.28-13.72 1.27-4.59 0-9.19-.42-13.72-1.27l-7.08-1.33-7.25-1.36c-1.28-.24-2.58-.37-3.88-.38-1.3-.02-2.6.08-3.88.29v.01l-.01-.01c-2.73.4-5.46.83-8.17 1.33l-20.99 4.2a59.971 59.971 0 0 0-32.2 18.01l-33.31 35.87c-3.03 3.26-2.81 8.37.48 11.36l32.37 29.43c3.16 2.87 8.02 2.76 11.04-.26l9.48-9.48c1.89-1.89 5.12-.55 5.12 2.12v136.76c0 4.42 3.58 8 8 8h128c4.42 0 8-3.58 8-8V191.36c0-2.67 3.23-4.01 5.12-2.12l9.48 9.48a7.994 7.994 0 0 0 11.04.26l32.37-29.43c3.29-2.99 3.51-8.1.48-11.36Zm-130.5-26.08h-.34.7H224.07Z" fill="#6C7278"></path><path d="m252.07 98.87-14.35 2.69a74.08 74.08 0 0 1-27.37 0L196 98.87c-2.56-.48-5.17-.51-7.74-.09 1.36 18.63 16.85 33.32 35.78 33.32s34.41-14.69 35.78-33.32c-2.57-.42-5.18-.39-7.74.09h-.01Z" fill="#5B6167"></path><path d="m196.02 109.55 14.34 2.7c9.04 1.7 18.31 1.7 27.35 0l14.34-2.7c1.78-.33 3.58-.44 5.38-.33 1.27-3.27 2.09-6.77 2.35-10.43-2.56-.42-5.18-.39-7.73.09l-14.34 2.7c-9.04 1.7-18.31 1.7-27.35 0l-14.34-2.7c-2.55-.48-5.17-.51-7.73-.09.27 3.66 1.08 7.16 2.35 10.43 1.8-.1 3.61 0 5.38.33Z" fill="#6C7278"></path><path d="M232.42 112.11h-16.76a1.62 1.62 0 0 0-1.62 1.62v7.76c0 .895.725 1.62 1.62 1.62h16.76a1.62 1.62 0 0 0 1.62-1.62v-7.76a1.62 1.62 0 0 0-1.62-1.62Z" fill="#fff"></path><path d="M160.04 155.95v-51.88l-.95.19a60.02 60.02 0 0 0-32.2 18l-31.06 33.45 44.22 40.37 5.74-5.74a48.64 48.64 0 0 0 14.25-34.39ZM321.19 122.27a59.984 59.984 0 0 0-32.2-18l-.95-.19v51.88c0 12.9 5.12 25.27 14.25 34.39l5.79 5.76 44.2-40.36-31.09-33.48Z" fill="#818990"></path><path d="M174.04 226.11c0 2.82.24 5.59.69 8.29.16.98 1 1.71 1.99 1.71h94.65c.99 0 1.83-.73 1.99-1.71.45-2.7.69-5.47.69-8.29v-.02c0-1.1-.91-1.98-2.01-1.98h-95.98c-1.1 0-2.01.88-2.01 1.98v.02h-.01ZM270.5 216.11c1.31 0 2.28-1.24 1.95-2.52-5.56-21.56-25.13-37.48-48.42-37.48-23.29 0-42.86 15.93-48.42 37.48a2.02 2.02 0 0 0 1.95 2.52H270.5ZM178.58 246.95c.53 1.15 1.1 2.29 1.71 3.39.61 1.1 1.73 1.77 2.97 1.77h81.55c1.24 0 2.37-.69 2.97-1.77.6-1.08 1.18-2.24 1.71-3.39.61-1.33-.38-2.84-1.84-2.84h-87.22c-1.46 0-2.45 1.51-1.84 2.84h-.01ZM197.57 264.11c-1.99 0-2.78 2.59-1.12 3.69a49.713 49.713 0 0 0 27.59 8.31c10.2 0 19.68-3.06 27.59-8.31 1.66-1.1.87-3.69-1.12-3.69h-52.94Z" fill="#EB836F"></path><path d="m95.85 155.74-2.23 2.4c-3.03 3.26-2.81 8.37.48 11.36l32.37 29.43c3.16 2.87 8.02 2.76 11.04-.26l2.56-2.56-44.22-40.37ZM185.2 96.07c1.65-.29 3.18.86 3.45 2.52 2.73 17.09 17.53 30.16 35.39 30.16s32.66-13.06 35.39-30.16c.26-1.66 1.79-2.81 3.45-2.52l5.93 1.04c1.59.28 2.68 1.78 2.43 3.38-3.64 22.79-23.38 40.21-47.2 40.21-23.82 0-43.56-17.42-47.2-40.21-.25-1.6.84-3.1 2.43-3.38l5.93-1.04Z" fill="#42474C"></path><path d="M293.9 195.51a74.154 74.154 0 0 0-10.11 51.02l.04.27c.53 3.19 1.18 6.58 1.84 10.38 1.52 8.8 2.26 17.72 2.26 26.65V295c0 14-9.37 26.26-22.87 29.95a89.888 89.888 0 0 1-42.54 1.17l-15.36-3.29a90.172 90.172 0 0 0-38.42.15l-16.73 3.73v1.41c0 4.42 3.58 8 8 8h128c4.42 0 8-3.58 8-8v-136l-2.1 3.4-.01-.01Z" fill="#818990"></path><path d="m354.57 158.19-33.31-35.87a59.971 59.971 0 0 0-32.2-18.01l-17.92-3.58c-.57 3.35-1.49 6.59-2.72 9.67l12.12 2.42a59.971 59.971 0 0 1 32.2 18.01l33.31 35.87c2.32 2.49 2.73 6.07 1.32 8.95l6.71-6.1c3.29-2.99 3.51-8.1.48-11.36h.01Z" fill="#9FA5AB" opacity=".4"></path><path d="m352.29 155.74 2.23 2.4c3.03 3.26 2.81 8.37-.48 11.36l-32.37 29.43c-3.16 2.87-8.02 2.76-11.04-.26l-2.56-2.56 44.22-40.37Z" fill="#42474C"></path></g><defs><clipPath id="clip0_662_1337"><path fill="#fff" d="M.04 0h448v448H.04z"></path></clipPath></defs></svg>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-[--theme-text]">Nom du Produit</h3>
                                    <p class="mt-1 text-sm text-[--theme-secondary-text]">99,99 MAD</p>
                                    <button class="mt-4 w-full bg-[--theme-primary] text-[--theme-btn-text] hover:bg-[--theme-btn-hover] py-2 px-4 rounded-md transition-colors">
                                        Ajouter au Panier
                                    </button>
                                </div>
                            </div>

                            <!-- Product Card 2 -->
                            <div class="bg-[--theme-card-bg] rounded-lg shadow-md overflow-hidden">
                                <div class="h-48 bg-gray-200">
                                    <svg class="placeholder-svg" preserveAspectRatio="xMidYMid slice" width="100%" viewBox="0 0 449 448" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_894_1503)"><path d="M448.04 0H.04v448h448V0Z" fill="#F2F2F2"></path><path d="m354.57 158.19-33.31-35.87a59.971 59.971 0 0 0-32.2-18.01l-20.99-4.2c-2.72-.49-5.45-.93-8.17-1.33l-.01.01v-.01c-1.29-.21-2.58-.31-3.88-.29-1.3.01-2.6.14-3.88.38l-7.25 1.36-7.08 1.33c-4.54.85-9.13 1.28-13.72 1.27-4.59 0-9.19-.42-13.72-1.27l-7.08-1.33-7.25-1.36c-1.28-.24-2.58-.37-3.88-.38-1.3-.02-2.6.08-3.88.29v.01l-.01-.01c-2.73.4-5.46.83-8.17 1.33l-20.99 4.2a59.971 59.971 0 0 0-32.2 18.01l-33.31 35.87c-3.03 3.26-2.81 8.37.48 11.36l32.37 29.43c3.16 2.87 8.02 2.76 11.04-.26l9.48-9.48c1.89-1.89 5.12-.55 5.12 2.12v136.76c0 4.42 3.58 8 8 8h128c4.42 0 8-3.58 8-8V191.36c0-2.67 3.23-4.01 5.12-2.12l9.48 9.48a7.994 7.994 0 0 0 11.04.26l32.37-29.43c3.29-2.99 3.51-8.1.48-11.36Zm-130.5-26.08h-.34.7H224.07Z" fill="#1F8A84"></path><path d="m252.071 98.87-14.35 2.69a74.08 74.08 0 0 1-27.37 0l-14.35-2.69c-2.56-.48-5.17-.51-7.74-.09 1.36 18.63 16.85 33.32 35.78 33.32s34.41-14.69 35.78-33.32c-2.57-.42-5.18-.39-7.74.09h-.01Z" fill="#187F80"></path><path d="m196.02 109.55 14.34 2.7c9.04 1.7 18.31 1.7 27.35 0l14.34-2.7c1.78-.33 3.58-.44 5.38-.33 1.27-3.27 2.09-6.77 2.35-10.43-2.56-.42-5.18-.39-7.73.09l-14.34 2.7c-9.04 1.7-18.31 1.7-27.35 0l-14.34-2.7c-2.55-.48-5.17-.51-7.73-.09.27 3.66 1.08 7.16 2.35 10.43 1.8-.1 3.61 0 5.38.33Z" fill="#1F8A84"></path><path d="M232.42 112.11h-16.76a1.62 1.62 0 0 0-1.62 1.62v7.76c0 .895.725 1.62 1.62 1.62h16.76a1.62 1.62 0 0 0 1.62-1.62v-7.76a1.62 1.62 0 0 0-1.62-1.62Z" fill="#fff"></path><path d="M185.2 96.07c1.65-.29 3.18.86 3.45 2.52 2.73 17.09 17.53 30.16 35.39 30.16s32.66-13.06 35.39-30.16c.26-1.66 1.79-2.81 3.45-2.52l5.93 1.04c1.59.28 2.68 1.78 2.43 3.38-3.64 22.79-23.38 40.21-47.2 40.21-23.82 0-43.56-17.42-47.2-40.21-.25-1.6.84-3.1 2.43-3.38l5.93-1.04ZM95.85 155.74l-2.23 2.4c-3.03 3.26-2.81 8.37.48 11.36l32.371 29.43c3.16 2.87 8.02 2.76 11.04-.26l2.56-2.56-44.22-40.37ZM352.29 155.74l2.23 2.4c3.03 3.26 2.81 8.37-.48 11.36l-32.37 29.43c-3.16 2.87-8.02 2.76-11.04-.26l-2.56-2.56 44.22-40.37Z" fill="#59B1AB"></path><path d="m267.02 218.12-10.37 4.15a12.378 12.378 0 0 1-9.23 0l-10.37-4.15a7.985 7.985 0 0 1-5.02-7.41v-35.6c0-1.66 1.34-3 3-3h34c1.66 0 3 1.34 3 3v35.6c0 3.26-1.99 6.2-5.02 7.41h.01Z" fill="#3A9C97"></path><path d="M235.04 172h34c1.66 0 3 1.34 3 3v9h-40v-9c0-1.66 1.34-3 3-3Z" fill="#59B1AB"></path><path d="M288 284.11H152.04v28h130.8a30.944 30.944 0 0 0 5.16-17.12v-10.88ZM152.04 264.11v8h135.53c-.2-2.67-.46-5.34-.79-8H152.04ZM283.86 246.53c-.13-.77-.22-1.54-.33-2.3H152.04v8h132.82c-.34-1.89-.67-3.69-.95-5.42l-.04-.27-.01-.01Z" fill="#106770"></path><path opacity=".5" d="M293.97 195.51a74.12 74.12 0 0 0-10.44 48.71h12.51v8h-11.18c.29 1.58.59 3.22.89 4.95.4 2.3.74 4.62 1.03 6.94h9.26v8h-8.47c.29 3.9.44 7.8.44 11.71v.29h8.04v28h-13.2a30.999 30.999 0 0 1-17.71 12.83 89.888 89.888 0 0 1-42.54 1.17l-15.36-3.29a90.172 90.172 0 0 0-38.42.15l-16.73 3.73v1.41c0 4.42 3.58 8 8 8h128c4.42 0 8-3.58 8-8v-136l-2.1 3.4h-.02Z" fill="#59B1AB"></path><path d="M296.04 284.11H288v10.88c0 6.26-1.88 12.16-5.16 17.12h13.2v-28ZM296.04 264.11h-9.26c.33 2.66.59 5.33.79 8h8.47v-8ZM296.04 244.22h-12.51c.1.77.2 1.54.33 2.3l.04.27c.29 1.74.61 3.54.95 5.42h11.18v-8l.01.01Z" fill="#59B1AB"></path><path d="M296.04 284.11H288v10.88c0 6.26-1.88 12.16-5.16 17.12h13.2v-28ZM296.04 264.11h-9.26c.33 2.66.59 5.33.79 8h8.47v-8ZM296.04 244.22h-12.51c.1.77.2 1.54.33 2.3l.04.27c.29 1.74.61 3.54.95 5.42h11.18v-8l.01.01Z" fill="#1F8A84"></path><path d="m354.57 158.19-33.31-35.87a59.971 59.971 0 0 0-32.2-18.01l-17.92-3.58c-.57 3.35-1.49 6.59-2.72 9.67l12.12 2.42a59.971 59.971 0 0 1 32.2 18.01l33.31 35.87c2.32 2.49 2.73 6.07 1.32 8.95l6.71-6.1c3.29-2.99 3.51-8.1.48-11.36h.01Z" fill="#59B1AB" opacity=".5"></path></g><defs><clipPath id="clip0_894_1503"><path fill="#fff" d="M.04 0h448v448H.04z"></path></clipPath></defs></svg>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-[--theme-text]">Nom du Produit</h3>
                                    <p class="mt-1 text-sm text-[--theme-secondary-text]">149,99 MAD</p>
                                    <button class="mt-4 w-full bg-[--theme-primary] text-[--theme-btn-text] hover:bg-[--theme-btn-hover] py-2 px-4 rounded-md transition-colors">
                                        Ajouter au Panier
                                    </button>
                                </div>
                            </div>

                            <!-- Product Card 3 -->
                            <div class="bg-[--theme-card-bg] rounded-lg shadow-md overflow-hidden">
                                <div class="h-48 bg-gray-200">
                                    <svg class="placeholder-svg" preserveAspectRatio="xMidYMid slice" width="100%" viewBox="0 0 448 448" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_894_1529)"><path d="M448 0H0v448h448V0Z" fill="#F2F2F2"></path><path d="m354.54 158.19-33.31-35.87a59.971 59.971 0 0 0-32.2-18.01l-20.99-4.2c-2.72-.49-5.45-.93-8.17-1.33l-.01.01v-.01c-1.29-.21-2.58-.31-3.88-.29-1.3.01-2.6.14-3.88.38l-7.25 1.36-7.08 1.33c-4.54.85-9.13 1.28-13.72 1.27-4.59 0-9.19-.42-13.72-1.27l-7.08-1.33-7.25-1.36c-1.28-.24-2.58-.37-3.88-.38-1.3-.02-2.6.08-3.88.29v.01l-.01-.01c-2.73.4-5.46.83-8.17 1.33l-20.99 4.2a59.971 59.971 0 0 0-32.2 18.01l-33.31 35.87c-3.03 3.26-2.81 8.37.48 11.36l32.37 29.43c3.16 2.87 8.02 2.76 11.04-.26l9.48-9.48c1.89-1.89 5.12-.55 5.12 2.12v136.76c0 4.42 3.58 8 8 8h128c4.42 0 8-3.58 8-8V191.36c0-2.67 3.23-4.01 5.12-2.12l9.48 9.48a7.994 7.994 0 0 0 11.04.26l32.37-29.43c3.29-2.99 3.51-8.1.48-11.36Zm-130.5-26.08h-.34.7H224.04Z" fill="#DD6A5A"></path><path d="m252.03 98.87-14.35 2.69a74.08 74.08 0 0 1-27.37 0l-14.35-2.69c-2.56-.48-5.17-.51-7.74-.09 1.36 18.63 16.85 33.32 35.78 33.32s34.41-14.69 35.78-33.32c-2.57-.42-5.18-.39-7.74.09h-.01Z" fill="#C03D37"></path><path d="m195.99 109.55 14.34 2.7c9.04 1.7 18.31 1.7 27.35 0l14.34-2.7c1.78-.33 3.58-.44 5.38-.33 1.27-3.27 2.09-6.77 2.35-10.43-2.56-.42-5.18-.39-7.73.09l-14.34 2.7c-9.04 1.7-18.31 1.7-27.35 0l-14.34-2.7c-2.55-.48-5.17-.51-7.73-.09.27 3.66 1.08 7.16 2.35 10.43 1.8-.1 3.61 0 5.38.33Z" fill="#CC5747"></path><path d="M232.38 112.11h-16.76a1.62 1.62 0 0 0-1.62 1.62v7.76c0 .895.725 1.62 1.62 1.62h16.76a1.62 1.62 0 0 0 1.62-1.62v-7.76a1.62 1.62 0 0 0-1.62-1.62Z" fill="#fff"></path><path d="M185.16 95.82c1.65-.29 3.18.86 3.45 2.52 2.73 17.09 17.53 30.16 35.39 30.16s32.66-13.06 35.39-30.16c.26-1.66 1.79-2.81 3.45-2.52l5.93 1.04c1.59.28 2.68 1.78 2.43 3.38-3.64 22.79-23.38 40.21-47.2 40.21-23.82 0-43.56-17.42-47.2-40.21-.25-1.6.84-3.1 2.43-3.38l5.93-1.04ZM95.82 155.74l-2.23 2.4c-3.03 3.26-2.81 8.37.48 11.36l32.37 29.43c3.16 2.87 8.02 2.76 11.04-.26l2.56-2.56-44.22-40.37Z" fill="#E8AF57"></path><path d="m354.541 158.19-33.31-35.87a59.971 59.971 0 0 0-32.2-18.01l-17.92-3.58c-.57 3.35-1.49 6.59-2.72 9.67l12.12 2.42a59.971 59.971 0 0 1 32.2 18.01l33.31 35.87c2.32 2.49 2.73 6.07 1.32 8.95l6.71-6.1c3.29-2.99 3.51-8.1.48-11.36h.01ZM293.9 195.51a74.154 74.154 0 0 0-10.11 51.02l.04.27c.53 3.19 1.18 6.58 1.84 10.38 1.52 8.8 2.26 17.72 2.26 26.65V295c0 14-9.37 26.26-22.87 29.95a89.888 89.888 0 0 1-42.54 1.17l-15.36-3.29a90.172 90.172 0 0 0-38.42.15l-16.73 3.73v1.41c0 4.42 3.58 8 8 8h128c4.42 0 8-3.58 8-8v-136l-2.1 3.4-.01-.01Z" fill="#E87E69"></path><path d="m352.26 155.74 2.23 2.4c3.03 3.26 2.81 8.37-.48 11.36l-32.37 29.43c-3.16 2.87-8.02 2.76-11.04-.26l-2.56-2.56 44.22-40.37Z" fill="#E8AF57"></path></g><defs><clipPath id="clip0_894_1529"><path fill="#fff" d="M0 0h448v448H0z"></path></clipPath></defs></svg>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-[--theme-text]">Nom du Produit</h3>
                                    <p class="mt-1 text-sm text-[--theme-secondary-text]">199,99 MAD</p>
                                    <button class="mt-4 w-full bg-[--theme-primary] text-[--theme-btn-text] hover:bg-[--theme-btn-hover] py-2 px-4 rounded-md transition-colors">
                                        Ajouter au Panier
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
            </section>
            <form class="w-[30%] bg-white border-l overflow-auto pb-12 h-screen " action="" method="POST" enctype="multipart/form-data">
                <h1 class="font-medium border-b p-3">Page d'accueil</h1>
                <ul class="p-3">
                    <li class="font-medium text-sm ">
                        section héro
                        <ul class="py-2">
                            <li class="text-gray-600">
                                <i class="fa-solid fa-angle-right mr-2"></i><i class="fa-regular fa-image mr-1"></i></i> Image
                                <input type="file" name="banner" id="banner" class="hidden">
                                <label for="banner" class="bg-gray-200 px-2 py-1 block w-min ml-4 mt-1 rounded">Sélectionner</label>
                            </li>
                            <li class="text-gray-600 mt-2">
                                <i class="fa-solid fa-angle-right mr-2"></i><i class="fa-solid fa-heading mr-1"></i></i> Titre
                                <input type="text" name="heading" id="heading" 
                                    class="px-2 py-1 block w-[calc(100%-16px)] ml-4 mt-1 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Entrez le texte du titre" value="<?=$storeInfo['heading_text']?>">
                            </li>
                            <li class="text-gray-600 mt-2">
                                <i class="fa-solid fa-angle-right mr-2"></i><i class="fa-solid fa-heading mr-1"></i></i> Sous-titre
                                <input type="text" name="sub-heading" id="sub-heading" 
                                    class="px-2 py-1 block w-[calc(100%-16px)] ml-4 mt-1 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Entrez le texte du sous-titre" value="<?=$storeInfo['sub_heading_text']?>">
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="p-3">
                    <li class="font-medium text-sm " id="themes">
                        Thème
                        <p class="text-gray-600 font-normal">Les schémas de couleurs peuvent être appliqués aux sections de votre boutique en ligne.</p>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <label for="theme-1" class="w-full has-[input:checked]:border-blue-500 has-[input:checked]:border-2 inline-block border shadow rounded-md p-2 text-center">
                                <input type="radio" name="theme" id="theme-1" value="theme-1" class="hidden" <?=($storeInfo['theme'] == 'theme-1')?'checked':''?>>
                                <h1 class="font-medium text-sm">Aa</h1>
                                <div>
                                    <span class="w-5 h-2 inline-block bg-black rounded"></span>
                                    <span class="w-5 h-2 inline-block rounded border border-black"></span>
                                </div>
                            </label>
                            <label for="theme-2" class="w-full bg-black has-[input:checked]:border-blue-500 has-[input:checked]:border-2 inline-block border shadow rounded-md p-2 text-center">
                                <input type="radio" name="theme" id="theme-2" value="theme-2" class="hidden" <?=($storeInfo['theme'] == 'theme-2')?'checked':''?>>
                                <h1 class="font-medium text-white text-sm">Aa</h1>
                                <div>
                                    <span class="w-5 h-2 inline-block bg-white rounded"></span>
                                    <span class="w-5 h-2 inline-block rounded border border-white"></span>
                                </div>
                            </label>
                            <label for="theme-4" class="w-full bg-[#fef3c7] text-[#92400e] has-[input:checked]:border-blue-500 has-[input:checked]:border-2 inline-block border shadow rounded-md p-2 text-center">
                                <input type="radio" name="theme" id="theme-4" value="theme-4" class="hidden" <?=($storeInfo['theme'] == 'theme-4')?'checked':''?>>
                                <h1 class="font-medium text-sm">Aa</h1>
                                <div>
                                    <span class="w-5 h-2 inline-block bg-black rounded"></span>
                                    <span class="w-5 h-2 inline-block rounded border border-black"></span>
                                </div>
                            </label>
                            <label for="theme-3" class="w-full bg-blue-500 text-white has-[input:checked]:border-blue-500 has-[input:checked]:border-2 inline-block border shadow rounded-md p-2 text-center">
                                <input type="radio" name="theme" id="theme-3" value="theme-3" class="hidden" <?=($storeInfo['theme'] == 'theme-3')?'checked':''?>>
                                <h1 class="font-medium text-sm">Aa</h1>
                                <div>
                                    <span class="w-5 h-2 inline-block bg-white rounded"></span>
                                    <span class="w-5 h-2 inline-block rounded border border-white"></span>
                                </div>
                            </label>
                        </div>
                    </li>
                </ul>
                <ul class="p-3">
                    <li class="font-medium text-sm" id="store-info">
                        Informations de la boutique
                        <p class="text-gray-600 font-normal">Les informations de contact de votre boutique.</p>
                        <ul class="mt-2 space-y-3">
                            <li class="text-gray-600">
                                <i class="fa-solid fa-location-dot mr-2"></i> Adresse
                                <textarea name="store_address" id="store_address" rows="2"
                                    class="px-2 py-1 block w-[calc(100%-16px)] ml-4 mt-1 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Entrez l'adresse de votre boutique..."><?=$storeInfo['address']?></textarea>
                            </li>
                            <li class="text-gray-600">
                                <i class="fa-solid fa-phone mr-2"></i> Téléphone
                                <input type="tel" name="store_phone" id="store_phone"
                                    class="px-2 py-1 block w-[calc(100%-16px)] ml-4 mt-1 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="+212 6XX-XXXXXX" value="<?=$storeInfo['phone']?>">
                            </li>
                            <li class="text-gray-600">
                                <i class="fa-solid fa-envelope mr-2"></i> Email
                                <input type="email" name="store_email" id="store_email"
                                    class="px-2 py-1 block w-[calc(100%-16px)] ml-4 mt-1 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="contact@votre-boutique.com" value="<?=$storeInfo['email']?>">
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="flex justify-start p-4 border-t border-gray-200">
                    <input name="save_changes" value="Enregistrer" type="submit" class="bg-black text-white px-6 py-2 rounded-md hover:bg-gray-600 transition-colors">
                </div>
            </form>
        </main>
        
    </div>
<script>
    const fileInput = document.getElementById('banner');
    const bannerImg = document.getElementById('banner-img');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                bannerImg.style.backgroundImage = `url(${e.target.result})`;
            }
            reader.readAsDataURL(file);
        }
    });
    const headingInput = document.getElementById('heading');
    const subHeadingInput = document.getElementById('sub-heading');
    const headingText = document.getElementById('heading-text');
    const subHeadingText = document.getElementById('sub-heading-text');

    headingInput.addEventListener('input', function(e) {
        headingText.textContent = e.target.value;
    });

    subHeadingInput.addEventListener('input', function(e) {
        subHeadingText.textContent = e.target.value;
    });
    const themeInputs = document.querySelectorAll('input[name="theme"]');
    const previewSection = document.getElementById('preview-section');

    function applyTheme(selectedTheme) {
        const root = document.documentElement;
        if (selectedTheme === 'theme-1') {
            root.style.setProperty('--theme-bg', '#ffffff');
            root.style.setProperty('--theme-text', '#000000');
            root.style.setProperty('--theme-secondary-text', '#4b5563');
            root.style.setProperty('--theme-primary', '#000000');
            root.style.setProperty('--theme-outline', '#ffffff');
            root.style.setProperty('--theme-secondary-hover', '#111827');
            root.style.setProperty('--theme-btn-text', '#ffffff');
            root.style.setProperty('--theme-card-bg', '#ffffff');
            root.style.setProperty('--theme-btn-hover', '#333333');
        } else if (selectedTheme === 'theme-2') {
            root.style.setProperty('--theme-bg', '#1a1a1a');
            root.style.setProperty('--theme-text', '#ffffff');
            root.style.setProperty('--theme-secondary-text', '#9ca3af');
            root.style.setProperty('--theme-primary', '#ffffff');
            root.style.setProperty('--theme-outline', '#333333');
            root.style.setProperty('--theme-secondary-hover', '#d1d5db');
            root.style.setProperty('--theme-btn-text', '#000000');
            root.style.setProperty('--theme-card-bg', '#2d2d2d');
            root.style.setProperty('--theme-btn-hover', '#e5e5e5');
        } else if (selectedTheme === 'theme-3') {
            root.style.setProperty('--theme-bg', '#1e40af');
            root.style.setProperty('--theme-text', '#ffffff');
            root.style.setProperty('--theme-secondary-text', '#e5e7eb');
            root.style.setProperty('--theme-primary', '#ffffff');
            root.style.setProperty('--theme-outline', '#3b82f6');
            root.style.setProperty('--theme-secondary-hover', '#ffffff');
            root.style.setProperty('--theme-btn-text', '#1e40af');
            root.style.setProperty('--theme-card-bg', '#2563eb');
            root.style.setProperty('--theme-btn-hover', '#ffffff');
        } else if (selectedTheme === 'theme-4') {
            root.style.setProperty('--theme-bg', '#fef3c7');
            root.style.setProperty('--theme-text', '#92400e');
            root.style.setProperty('--theme-secondary-text', '#78350f');
            root.style.setProperty('--theme-primary', '#92400e');
            root.style.setProperty('--theme-outline', '#fbbf24');
            root.style.setProperty('--theme-secondary-hover', '#92400e');
            root.style.setProperty('--theme-btn-text', '#fef3c7');
            root.style.setProperty('--theme-card-bg', '#fcd34d');
            root.style.setProperty('--theme-btn-hover', '#924d23');
        }
    }

    const checkedTheme = document.querySelector('input[name="theme"]:checked');
    if (checkedTheme) {
        applyTheme(checkedTheme.value);
    }

    themeInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            applyTheme(e.target.value);
        });
    });
</script>
</body>

</html>
