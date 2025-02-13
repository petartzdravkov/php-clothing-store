<?php

if(isset($_POST['dark-light-btn'])){
    if(htmlentities($_COOKIE['dark-light'] == "dark")){
	setcookie("dark-light", "light");
    }else{
	setcookie("dark-light", "dark");
    }
    
    header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    die;
}




?>






<!doctype html>
<html lang="en" data-bs-theme="<?=htmlentities($_COOKIE['dark-light']);?>">
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pesho's Shirts Shop</title>
	<!-- bootrstrap css -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<!-- bootstrap icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    </head>
    <body class="d-flex flex-column min-vh-100">
	<nav class="navbar navbar-expand-md">
	    <div class="container">
		<a href="/Non-bookstack/shirts_shop/index.php?page=login" class="navbar-brand"> TeniskaBG </a>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
		    <span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navmenu">
		    <ul class="navbar-nav ms-auto">

			<form method="POST" action="<?= htmlentities($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);?>">
			<li class="nav-item">
			    <button type="submit" name="dark-light-btn" class="nav-link">
				<?php
				if(htmlentities($_COOKIE['dark-light']) == "dark"){ ?>
			      <i class="bi bi-lightbulb"></i>
			      <?php
				}else{ ?>
			      <i class="bi bi-lightbulb-fill"></i>
			      <?php
				}
				?>
			    </a>
			</li>
			    </button>
		      </form>

			
			<?php if(!isset($_SESSION['uname'])){?>
			    <li class="nav-item">
				<a href="/Non-bookstack/shirts_shop/index.php?page=login" class="nav-link <?= $page==='login' ? 'active' : ''?>"> Login </a>
			    </li>
			    <li class="nav-item">
				<a href="/Non-bookstack/shirts_shop/index.php?page=register" class="nav-link <?= $page==='register' ? 'active' : ''?>"> Register </a>
			    </li>
			<?php } ?>
			<?php if(isset($_SESSION['uname'])){
			    if($page === 'shop'){?>
			    <li class="nav-item">
				<a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasCart"><i class="bi bi-cart3"></i></a>
			    </li>
			<?php }?>
			<li class="nav-item">
			    <a href="/Non-bookstack/shirts_shop/index.php?page=profile" class="nav-link <?= $page==='profile' ? 'active' : ''?>"> <i class="bi bi-person-lines-fill"></i> </a>
			</li>
			<li class="nav-item">
			    <a href="/Non-bookstack/shirts_shop/index.php?page=logout" class="nav-link"> <i class="bi bi-box-arrow-left"></i> </a>
			</li>
<?php } ?>
<li class="nav-item">
    <a href="/Non-bookstack/shirts_shop/index.php?page=shop" class="nav-link <?= $page==='shop' ? 'active' : ''?>"> Shop </a>
</li>


		    </ul>
		</div>
	    </div>
	</nav>
