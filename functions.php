<?php

function initializeFiles(){
    //users
    if(!file_exists("storage/users.json")){
        $admin_profile = [["uname" => "admin", "pass" => password_hash('admin', PASSWORD_BCRYPT), "fname" => "Admin", "gender" => "male", "orders" => [[[]]]]];
        file_put_contents("storage/users.json", json_encode($admin_profile));
    }

    //inventory
    if(!file_exists("storage/inventory.json")){
        $inventory = [
	    ["id" => 1, "amount" => 20, "item" => "hat", "price" => 25, "gender" => "male"],
            ["id" => 2, "amount" => 30, "item" => "shirt", "price" => 35, "gender" => "female"],
            ["id" => 3, "amount" => 14, "item" => "polo", "price" => 30, "gender" => "male"],
            ["id" => 4, "amount" => 37, "item" => "jeans", "price" => 80, "gender" => "female"],
            ["id" => 5, "amount" => 3, "item" => "shoes", "price" => 220, "gender" => "female"],
            ["id" => 6, "amount" => 20, "item" => "glasses", "price" => 105, "gender" => "male"],
            ["id" => 7, "amount" => 34, "item" => "t-shirt", "price" => 30, "gender" => "female"],
            ["id" => 8, "amount" => 2, "item" => "bracelet", "price" => 50, "gender" => "male"],
            ["id" => 9, "amount" => 7, "item" => "sweatshirt", "price" => 80, "gender" => "female"],
            ["id" => 10, "amount" => 43, "item" => "sandals", "price" => 125, "gender" => "female"] 
        ];
        file_put_contents("storage/inventory.json", json_encode($inventory));
    }
}

function showRegister(){

    //if session is active redirect to shop
    if(isset($_SESSION['uname'])){
        header('Location: /Non-bookstack/shirts_shop/index.php?page=shop');
        die();
    }

    $users = json_decode(file_get_contents("storage/users.json"), true);
    if(isset($_POST["register_btn"])){
        // get user input
        $uname = htmlentities(trim($_POST['uname']));
        $pass = htmlentities(trim($_POST['pass']));
        $fname = htmlentities(trim($_POST['fname']));
        $gender = htmlentities(trim($_POST['gender']));

        //checks
        if(empty($uname) || empty($pass) || empty($fname) || empty($gender)){
            $error = "All fields are required.";
        } else{
            //if uname is taken - error, else add to json and redirect to login page
            $isUnameTaken = false;
            foreach($users as $user){
                if($user["uname"] === $uname){
                    $isUnameTaken = true;
                    break;
                }
            }
            if($isUnameTaken){
                $error = "Username already exists";
            }else{
                $users[] = ["uname" => $uname, "pass" => password_hash($pass, PASSWORD_BCRYPT), "fname" => $fname, "gender" => $gender, "orders" => [[[]]]];
                file_put_contents("storage/users.json", json_encode($users));
                header("Location: /Non-bookstack/shirts_shop/index.php?page=login&reg=1");
            }
        }
    }
    require('views/register.php');
}

function showLogin(){

    //if session is active redirect to shop
    if(isset($_SESSION['uname'])){
        header('Location: /Non-bookstack/shirts_shop/index.php?page=shop');
        die();
    }

    if(isset($_GET["reg"])){
        echo "<h3 class='text-center'> Successful Registration! </h3>";
    }


    $users = json_decode(file_get_contents("storage/users.json"), true);
    if(isset($_POST["login_btn"])){
        // get user input
        $uname = htmlentities(trim($_POST['uname']));
        $pass = htmlentities(trim($_POST['pass']));

        //checks
        if(empty($uname) || empty($pass)){
            $error = "All fields are required";
        } else{
            foreach($users as $user){
                if($user['uname'] === $uname && $user['pass'] == password_verify($pass, $user['pass'])){
                    $_SESSION['uname'] = $user['uname'];
                    $_SESSION['fname'] = $user['fname'];
                    $_SESSION['gender'] = $user['gender'];
                    header("Location: index.php?page=shop");
                    die();
                }
            }
            $error = "Invalid username or password";            
        }

    }
    require('views/login.php');
    
}

function showShop(){
    $inventory = json_decode(file_get_contents("storage/inventory.json"), true);
    $users = json_decode(file_get_contents("storage/users.json"), true);

    //buying logic from shop page
    $error = []; //error array to know which item exactly had an error
    $success_msg = "";
    $cart = [];
    for($i=1; $i<= count($inventory); $i++){
        if(isset($_POST[$i."_ordered"])){

            if(!isset($_SESSION["uname"])){ //backend check if logged in to buy
                $error[$i] = "Please log in first, bratinio.";
                break;
            } else{
            
                $amount_ordered = htmlentities(trim($_POST[$i."_ordered"]));

                if($amount_ordered <= 0){ //check for positive numbers
                    $error[$i] = "Please enter a value bigger than 0.";
                    break;
                }else{
                    //buying
                    if($inventory[$i-1]["amount"] - $amount_ordered < 0){
                        $error[$i] = "Not enough items in stock, sorry.";
                        break;
                    }else{
                        //if Buy button is pressed
                        if(isset($_POST["buy_btn"])){
                            $inventory[$i-1]["amount"] -= $amount_ordered;
                            foreach($users as $key_user => $user){
                                if($user["uname"] === $_SESSION["uname"]){
                                    $users[$key_user]["orders"][] = [["id" => $i, "amount" => $amount_ordered, "date" => date('j/m/Y'), "time" => date('H:i:s'), "price" => $inventory[$i-1]["price"]]];
                                    $success_msg = '
<div class="toast fade show m-auto position-relative border border-success">
<div class="toast-body text-center text-success">
Congratulations! You order was successful!
<button type="button" class="btn-close position-absolute top-50 end-0 px-2 translate-middle-y" data-bs-dismiss="toast"></button>
</div>
</div>';
                                    break;
                                }
                            }
                        } elseif(isset($_POST["cart_btn"])){ //if To Cart button is pressed

			    if(!isset($_COOKIE['cart'])){
				$cart = [];
			    }else{
				$cart = json_decode($_COOKIE['cart'], true);
			    }

			    $cart[] = ["id" => $i, "amount" => $amount_ordered];			    
                            setcookie("cart", json_encode($cart));			 
                            
                            //redirect to get cookie
                            header("Location: index.php?page=shop&addcart=1");

                        }
                    }
                }
            }
        }
    }

    //buying logic from cart page
    if(isset($_POST['cart_buy_btn'])){
	$cart = json_decode($_COOKIE['cart'], true);

	//buy - remove amount bought from the inventory
	foreach($cart as $k_cart_item => $cart_item){
	    foreach($inventory as $key_item => $item){
		if($cart_item['id'] == $item['id']){
		    $inventory[$key_item]['amount'] -= $cart_item['amount'];
		    $cart[$k_cart_item]["date"] = date('j/m/Y');
		    $cart[$k_cart_item]["time"] = date('H:i:s');
		    $cart[$k_cart_item]["price"] = $item['price'];
		    break;
		}
	    }
	}

	//buy - add order to users
	foreach($users as $k_user => $user){
	    if($user['uname'] == $_SESSION['uname']){
		$users[$k_user]['orders'][] = $cart;
	    }
	}
	
	//empty cart and redirect for message and to update cookies
        setcookie("cart");
	header("Location: index.php?page=shop&bought=1");
    }
    if(isset($_GET['bought'])){
    	$success_msg = '
<div class="toast fade show m-auto position-relative border border-success">
<div class="toast-body text-center text-success">
Congratulations! You order was successful!
<button type="button" class="btn-close position-absolute top-50 end-0 px-2 translate-middle-y" data-bs-dismiss="toast"></button>
</div>
</div>';
    }

    //empty cart
    if(isset($_POST["cart_empty_btn"])){
	setcookie('cart');
	header("Location: index.php?page=shop&emptycard=1");
    }
    if(isset($_GET['emptycard'])){
    	$success_msg = '
<div class="toast fade show m-auto position-relative border border-success">
<div class="toast-body text-center text-success">
Emptied cart.
<button type="button" class="btn-close position-absolute top-50 end-0 px-2 translate-middle-y" data-bs-dismiss="toast"></button>
</div>
</div>
';
    }

    
    file_put_contents("storage/inventory.json", json_encode($inventory));
    file_put_contents("storage/users.json", json_encode($users));

    //get cart offcanvas
    require('views/cart.php');


    if(isset($_GET['addcart'])){
    	$success_msg = '
<div class="toast fade show m-auto position-relative border border-success">
<div class="toast-body text-center text-success">
Added to cart.
<button type="button" class="btn-close position-absolute top-50 end-0 px-2 translate-middle-y" data-bs-dismiss="toast"></button>
</div>
</div>
';
    }
    
    echo "<h2 class='mx-auto'> Shop </h2>";
    echo $success_msg;
    //display all items
    $disabled = (isset($_SESSION["uname"])) ? "" : "disabled"; //disable inputs if not logged in
    echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 m-0">';
    $id=1;
    //check for errors on individual cards
    foreach($inventory as $items){
        $error_solo = "";
        if(isset($error[$id])){
            $error_solo = $error[$id];
        }

        //only display items that are for the same gender
        if(isset($_SESSION["uname"])){
            if($items["gender"] == $_SESSION["gender"]){ //if logged in print only things corresponding to gender
                echo '
<div class="col">
<div class="card m-3">
<p class="m-2 text-center text-danger">' . $error_solo . '</p>
<div class="card-body">
<h5 class="card-title">' . ucfirst($items["item"]) . '</h5>';
		if($items["amount"] <= 0){
		    echo '<h6 class="card-subtitle mb-2 text-danger"> Sold out</h6>';
		} else{
		    echo '<h6 class="card-subtitle mb-2 text-body-secondary"> Amount left: ' . $items["amount"] . '</h6>';
		}
		echo '<h6 class="card-subtitle mb-2 text-body-secondary"> Price: ' . $items["price"] . ' BGN</h6>
<form method="POST" action="index.php?page=shop">
<input type="number" name="' . $id . '_ordered" placeholder="1" min="1" max="' . $items["amount"]  . '" class="my-3 w-25"' . $disabled  . '>
<input type="submit" name="buy_btn" value="Buy"  class="w-25 btn btn-outline-info p-1 mx-3"' . $disabled  . '>
<input type="submit" name="cart_btn" value="To Cart"  class="w-25 btn btn-outline-secondary p-1"' . $disabled  . '>
</form>
</div>
</div>
</div>';
            }
        }else{ //if not logged print everything
            echo '
<div class="col">
<div class="card m-3">
<div class="card-body">
<h5 class="card-title">' . ucfirst($items["item"]) . '</h5>';
	    if($items["amount"] <= 0){
		echo '<h6 class="card-subtitle mb-2 text-danger"> Sold out</h6>';
	    } else{
		echo '<h6 class="card-subtitle mb-2 text-body-secondary"> Amount left: ' . $items["amount"] . '</h6>';
	    }
            echo '<h6 class="card-subtitle mb-2 text-body-secondary"> Price: ' . $items["price"] . ' BGN</h6>
<form method="POST" action="index.php?page=shop">
<input type="number" name="' . $id . '_ordered" placeholder="1" min="1" max="' . $items["amount"]  . '" class="my-3 w-25"' . $disabled  . '>
<input type="submit" name="buy_btn" value="Buy"  class="w-25"' . $disabled  . '>
<input type="submit" name="cart_btn" value="To Cart"  class="w-25"' . $disabled  . '>
</form>
</div>
</div>
</div>';
        }
        $id++;
    }
    echo '</div>';
}

function logout(){
    session_destroy();
    header('Location: index.php?page=login');
    die();
}

function showProfile(){

    //redirect to login if no active session
    if(!isset($_SESSION["uname"])){
        header("Location: index.php?page=login");
        die();
    }
    
    $inventory = json_decode(file_get_contents("storage/inventory.json"), true);
    $users = json_decode(file_get_contents("storage/users.json"), true);
    $uname = $_SESSION['uname'];
    $fname = $_SESSION['fname'];
    $gender = $_SESSION['gender'];
    $current_user_index = "";

    //find index of current user in $users
    foreach($users as $k_user => $user){
        if($user['uname'] === $uname){
            $current_user_index = $k_user;
            break;
        }
    }

    //if apply changes has been pressed
    if(isset($_POST["submit_replace"])){
        if(!empty($_POST['fname_replace'])){
            foreach($users as $key_user => $user){
                if($user["uname"] === $uname){
                    $users[$key_user]["fname"] = htmlentities(trim($_POST["fname_replace"]));
                    $_SESSION['fname'] = htmlentities(trim($_POST["fname_replace"]));
                    $fname = $_SESSION['fname'];
                    file_put_contents("storage/users.json", json_encode($users));
                }
            }
        }

        if(!empty($_POST['gender_replace'])){
            foreach($users as $key_user => $user){
                if($user["uname"] === $uname){
                    $users[$key_user]["gender"] = htmlentities(trim($_POST["gender_replace"]));
                    $_SESSION['gender'] = htmlentities(trim($_POST["gender_replace"]));
                    $gender = $_SESSION['gender'];
                    file_put_contents("storage/users.json", json_encode($users));
		    break;
                }
            }
        }


	//profile pic
	if(!empty($file_tmp) && is_uploaded_file($file_tmp)){
	    $uploaded_file = "";
	    $file_tmp = $_FILES['profile_pic']['tmp_name'];
	    $upload_dir = "uploads/";
	    $file_name = $_FILES['profile_pic']['name'];
	    $new_file_name = $uname . "_" . time() . "_" . $file_name;
	    if(move_uploaded_file($file_tmp, $upload_dir . $new_file_name)){
		$uploaded_file = "Successful upload!";
		foreach($users as $key_user => $user){
		    if($user["uname"] === $uname){
			$users = json_decode(file_get_contents("storage/users.json"), true);
			if(isset($users[$key_user]["profile_pic"])){
			    unlink($users[$key_user]["profile_pic"]);
			}
			$users[$key_user]["profile_pic"] = $upload_dir . $new_file_name;
			file_put_contents("storage/users.json", json_encode($users));
			break;
                    }
		}
	    }
	}
    }


    //if delete account has been pressed
    if(isset($_POST["delete_account_confirm"])){
	unset($users[$current_user_index]);
	file_put_contents("storage/users.json", json_encode($users));
	session_destroy();
	header("Location: index.php?page=shop");
	die();
    }


    
    require('views/profile.php');
}


function getKeyById($key, $id, $inventory){
    
    foreach($inventory as $item){
	if($item["id"] == $id){
	    $found_key = $item[$key];
	    return $found_key;
	}
    }

    return false;
}

?>
