<?php

$cart = [];
if(isset($_COOKIE['cart'])){
    $cart = json_decode($_COOKIE['cart'], true);
}
?>



<div class="offcanvas offcanvas-end" id="offcanvasCart">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Cart <i class="bi bi-cart3"></i></h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
<?php for($i = 0; $i < count($cart); $i++){?>

    <div class="col">
      <div class="card m-2">
	<div class="card-body">
	  <h5 class="card-title"> <?=ucfirst(getKeyById("item", $cart[$i]['id'], $inventory));?></h5>
	  <h6 class="card-subtitle text-body-secondary m-1"> Amount: <?=$cart[$i]['amount']; ?></h6>
	  <h6 class="card-subtitle text-body-secondary m-1"> Price per item: <?= getKeyById("price", $cart[$i]['id'], $inventory) . " BGN"; ?></h6>
	</div>
      </div>
    </div>
<?php }

if(isset($_COOKIE['cart'])){?>
<?php
    $total = 0;
    foreach($cart as $key_cart_item => $cart_item){
	$total += getKeyById("price", $cart_item['id'], $inventory) * $cart_item['amount'];
    }

    echo "<h5 class='m-3'> Total: $total BGN</h4>";

    ?>
    <form action="index.php?page=shop" method="POST">
      <button type="submit" name="cart_buy_btn" class="btn btn-outline-info m-2">Buy</button>
      <button type="submit" name="cart_empty_btn" class="btn btn-outline-secondary m-2">Empty Cart</button>
    </form>
<?php } ?>
  </div>
</div>
