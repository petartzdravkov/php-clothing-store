<?php

session_start();
require_once('functions.php');

isset($_SESSION['uname']) ? $isLogged = true : $isLogged = false;

//dark-light mode
if(!isset($_COOKIE['dark-light'])){
    setcookie("dark-light", "dark");
    header("Location: index.php?page=shop");
}


//crete initial files if missing
initializeFiles();

//get page
$page = '';
$page .= htmlentities($_GET["page"]);



require('views/header.php');



if($page === "login"){
    showLogin();
}elseif($page === "register"){
    showRegister();
}elseif($page === "logout"){
    logout();
}elseif($page === "shop"){
    showShop();
}elseif($page === "profile"){
    showProfile();
}else{
    echo "<h1 class='m-auto'>Page Not Found</h1>";
}






require('views/footer.php');
