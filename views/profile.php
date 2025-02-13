
<div class="container">

    <h3 class="my-4"> Hello, <?=$fname;?>! </h3>
    <hr>
    <form method="POST" action="/Non-bookstack/shirts_shop/index.php?page=profile">
	<div class="my-3">
	    <p> Username: <?= $uname; ?> </p>
	</div>
	<hr>
	<div class="my-3">
	    <label for="fname_replace" class="me-2"> Change Full Name: </label>
	    <input type="text" name="fname_replace" id="fname_replace" placeholder="<?=$fname;?>">
	</div>
	<hr>
	<div class="my-3">
	    <label class="me-2"> Change Gender: </label>
	    <div class="form-check form-check-inline">
		<input class="form-check-input" type="radio" name="gender_replace" id="male" value="male" <?= $_SESSION['gender'] === "male" ? "checked" : "";?>>
		<label for="gender_replace" class="form-check-label"> Male </label>
	    </div>
	    <div class="form-check form-check-inline">
		<input  class="form-check-input" type="radio" name="gender_replace" id="female" value="female" <?= $_SESSION['gender'] === "female" ? "checked" : "";?>>
		<label for="gender_replace" class="form-check-label"> Female </label>
	    </div>
	</div>
	<hr>

	<button type="submit" class="btn btn-secondary" name="submit_replace"> Apply changes </button>
	<button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-danger" name="delete_acc_btn"> Delete Account </button>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content border border-danger">
		<div class="modal-header">
		    <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete account?</h1>
		    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
		    Are you sure you want to delete your account? This action cannot be undone.
		</div>
		<div class="modal-footer">
		    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		    <form method="POST" action="/Non-bookstack/shirts_shop/index.php?page=profile">
			<input type="submit" name="delete_account_confirm" class="btn btn-danger" value="Yes, delete my account">
		    </form>
		</div>
	    </div>
	</div>
    </div>



    <!-- Orders -->
    <?php if(count($users[$current_user_index]["orders"]) != 0){ ?>
	<h5 class="mt-5"> Orders </h5>
	<div class="accordion accordion-flush mb-5" id="orders_accordion">
	    <?php for($i=1; $i<=count($users[$current_user_index]["orders"]); $i++){ ?>
		<div class="accordion-item">
		    <h2 class="accordion-header">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?=$i?>">
			    Order #<?=$i?>
			</button>
		    </h2>
		    <div id="flush-collapse<?=$i?>" class="accordion-collapse collapse" data-bs-parent="#orders_accordion">
			<div class="accordion-body">
			    <table class="table table-hover">
				<tbody>
				    <tr>
					<th> Item - Amount</th>
					<td> <?php
					     for($j = 0; $j < count($users[$current_user_index]["orders"][$i-1]); $j++){
						 echo getKeyById("item", $users[$current_user_index]["orders"][$i-1][$j]['id'], $inventory) . " - ";
						 echo $users[$current_user_index]["orders"][$i-1][$j]['amount'];
						 if($j != count($users[$current_user_index]["orders"][$i-1]) - 1){
						     echo ", ";
						 }
					     }?>
					</td>
				    </tr>
				    <tr>
					<th> Date</th>
					<td> <?=$users[$current_user_index]["orders"][$i-1][0]["date"] . " at "  . $users[$current_user_index]["orders"][$i-1][0]["time"];?></td>
				    </tr>

				    <tr>
					<th> Price total </th>
					<td> <?php
					     $total = 0;
					     for($k = 0; $k < count($users[$current_user_index]["orders"][$i-1]); $k++){
						 $total += ($users[$current_user_index]["orders"][$i-1][$k]['amount'] * getKeyById("price", $users[$current_user_index]["orders"][$i-1][$k]['id'], $inventory));
					     }
					     echo $total . " BGN";
					     ?>
					</td>
				    </tr>
				</tbody>
			    </table>
			</div>
		    </div>
		</div>
	    <?php }?>
	</div>
    <?php } ?>

</div>



