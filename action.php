<?php
session_start();
$ip_add = getenv("REMOTE_ADDR");
include "db.php";

if (isset($_POST["category"])) {
	$category_query = "SELECT * FROM categories";
	$run_query = mysqli_query($con, $category_query) or die(mysqli_error($con));
	echo "
		<div class='nav nav-pills nav-stacked'>
			<li class='active'><a href='#'><h4>Categories</h4></a></li>
	";
	if (mysqli_num_rows($run_query) > 0) {
		while ($row = mysqli_fetch_array($run_query)) {
			$cid = $row["cat_id"];
			$cat_name = $row["cat_title"];
			echo "
					<li><a href='#' class='category' cid='$cid'>$cat_name</a></li>
			";
		}
		echo "</div>";
	}
}
if (isset($_POST["brand"])) {
	$brand_query = "SELECT * FROM brands";
	$run_query = mysqli_query($con, $brand_query);
	echo "
		<div class='nav nav-pills nav-stacked'>
			<li class='active'><a href='#'><h4>Brands</h4></a></li>
	";
	if (mysqli_num_rows($run_query) > 0) {
		while ($row = mysqli_fetch_array($run_query)) {
			$bid = $row["brand_id"];
			$brand_name = $row["brand_title"];
			echo "
					<li><a href='#' class='selectBrand' bid='$bid'>$brand_name</a></li>
			";
		}
		echo "</div>";
	}
}
if (isset($_POST["page"])) {
	$sql = "SELECT * FROM products";
	$run_query = mysqli_query($con, $sql);
	$count = mysqli_num_rows($run_query);
	$pageno = ceil($count / 9);
	for ($i = 1; $i <= $pageno; $i++) {
		echo "
			<li><a href='#' page='$i' id='page'>$i</a></li>
		";
	}
}
if (isset($_POST["getProduct"])) {
	$limit = 9;
	// task : adding next page
	if (isset($_POST["setPage"])) {
		$pageno = $_POST["pageNumber"];
		$start = ($pageno * $limit) - $limit;
	} else {
		$start = 0;
	}
	$product_query = "SELECT * FROM products LIMIT $start,$limit";
	$run_query = mysqli_query($con, $product_query);
	if (mysqli_num_rows($run_query) > 0) {
		while ($row = mysqli_fetch_array($run_query)) {
			$pro_id    = $row['product_id'];
			$pro_cat   = $row['product_cat'];
			$pro_brand = $row['product_brand'];
			$pro_title = $row['product_title'];
			$pro_price = $row['product_price'];
			$pro_image = $row['product_image'];
			echo "
				<div class='col-md-4'>
							<div class='panel panel-info'>
								<div class='panel-heading'>$pro_title</div>
								<div class='panel-body'>
									<img src='product_images/$pro_image' style='width:160px; height:250px;'/>
								</div>
								<div class='panel-heading'>" . CURRENCY . " $pro_price.00
									<form action='' method ='get' style='float:right; width:auto;'>
										<select name= 'filterr'>  
											<option value='all' selected>ALL</option> 
											<option value='buy'>Buy</option>
											<option value='rent'>Rent</option> 
											<option value='exchange'>Exchange</option>";
			$f_id = $_GET['filterr'];
			echo "
										</select>
										<input type='Submit' pid='$pro_id' fid='$f_id' style='float:right; width:100px;' id='product'  class='btn btn-danger btn-xs' value='Add To Cart' name='Add To Cart'>
									</form>
								</div>
							</div>
						</div>	
			";
		}
	}
}
if (isset($_POST["get_seleted_Category"]) || isset($_POST["selectBrand"]) || isset($_POST["search"])) {
	if (isset($_POST["get_seleted_Category"])) {
		$id = $_POST["cat_id"];
		$sql = "SELECT * FROM products WHERE product_cat = '$id'";
	} else if (isset($_POST["selectBrand"])) {
		$id = $_POST["brand_id"];
		$sql = "SELECT * FROM products WHERE product_brand = '$id'";
	} else {
		$keyword = $_POST["keyword"];
		$sql = "SELECT * FROM products WHERE product_keywords LIKE '%$keyword%'";
	}

	$run_query = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($run_query)) {
		$pro_id    = $row['product_id'];
		$pro_cat   = $row['product_cat'];
		$pro_brand = $row['product_brand'];
		$pro_title = $row['product_title'];
		$pro_price = $row['product_price'];
		$pro_image = $row['product_image'];
		echo "
				<div class='col-md-4'>
							<div class='panel panel-info'>
								<div class='panel-heading'>$pro_title</div>
								<div class='panel-body'>
									<img src='product_images/$pro_image' style='width:160px; height:250px;'/>
								</div>
								<div class='panel-heading'>$.$pro_price.00
								<form action='' method ='get' style='float:right; width:auto;'>
								<select name= 'filterr'>  
									<option value='all' selected>ALL</option> 
									<option value='buy'>Buy</option>
									<option value='rent'>Rent</option> 
									<option value='exchange'>Exchange</option>";
		$f_id = $_GET['filterr'];
		echo "
								</select>
								<input type='Submit' pid='$pro_id' fid='$f_id' style='float:right; width:100px;' id='product'  class='btn btn-danger btn-xs' value='Add To Cart' name='Add To Cart'>
							</form>								</div>
							</div>
						</div>	
			";
	}
}



if (isset($_POST["addToCart"])) {


	$p_id = $_POST["proId"];
	// $f_id = $_GET["filterr"];

	if (isset($_SESSION["uid"])) {

		$user_id = $_SESSION["uid"];

		$sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND user_id = '$user_id' ";
		$run_query = mysqli_query($con, $sql);
		$count = mysqli_num_rows($run_query); 
		if ($count > 0) {
			echo "
				<div class='alert alert-warning'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is already added into the cart Continue Shopping..!</b> 
				</div>
			"; //not in video
		} else {
			$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','$user_id','1')";
			if (mysqli_query($con, $sql)) {
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is Added..!</b>
					</div>
				";
			}
		}
	} else {
		$sql = "SELECT id FROM cart WHERE ip_add = '$ip_add' AND p_id = '$p_id' AND user_id = -1";
		$query = mysqli_query($con, $sql);
		if (mysqli_num_rows($query) > 0) {
			echo "
					<div class='alert alert-warning'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is already added into the cart Continue Shopping..!</b>
					</div>";
			exit();
		}
		$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','-1','1')";
		if (mysqli_query($con, $sql)) {
			echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Your product is Added Successfully..!</b>
					</div>
				";
			exit();
		}
	}
}

//Count User cart item
if (isset($_POST["count_item"])) {
	//When user is logged in then we will count number of item in cart by using user session id
	if (isset($_SESSION["uid"])) {
		$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE user_id = $_SESSION[uid]";
	} else {
		//When user is not logged in then we will count number of item in cart by using users unique ip address
		$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE ip_add = '$ip_add' AND user_id < 0";
	}

	$query = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($query);
	echo $row["count_item"];
	exit();
}
//Count User cart item

//Get Cart Item From Database to Dropdown menu
if (isset($_POST["Common"])) {

	if (isset($_SESSION["uid"])) {
		//When user is logged in this query will execute
		$sql = "SELECT a.product_id,a.product_title,a.product_price,a.exchange,a.product_image,b.id,b.qty,b.total_duration FROM products a,cart b WHERE a.product_id=b.p_id AND b.user_id='$_SESSION[uid]'";
	} else {
		//When user is not logged in this query will execute
		$sql = "SELECT a.product_id,a.product_title,a.product_price,a.exchange,a.product_image,b.id,b.qty,b.total_duration FROM products a,cart b WHERE a.product_id=b.p_id AND b.ip_add='$ip_add' AND b.user_id < 0";
	}
	$query = mysqli_query($con, $sql);
	if (isset($_POST["getCartItem"])) {
		//display cart item in dropdown menu
		if (mysqli_num_rows($query) > 0) {
			$n = 0;
			while ($row = mysqli_fetch_array($query)) {
				$n++;
				$product_id = $row["product_id"];
				$product_title = $row["product_title"];
				$product_price = $row["product_price"];
				$product_image = $row["product_image"];
				$cart_item_id = $row["id"];
				$qty = $row["qty"];
				echo '
					<div class="row">
						<div class="col-md-3">' . $n . '</div>
						<div class="col-md-3"><img class="img-responsive" src="product_images/' . $product_image . '" /></div>
						<div class="col-md-3">' . $product_title . '</div>
						<div class="col-md-3">' . CURRENCY . '' . $product_price . '</div>
					</div>';
			}
?>
			<a style="float:right;" href="cart.php" class="btn btn-warning">Edit&nbsp;&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
<?php
			exit();
		}
	}
	if (isset($_POST["checkOutDetails"])) {
		if (mysqli_num_rows($query) > 0) {
			//display user cart item with "Ready to checkout" button if user is not login
			echo "<form method='post' action='login_form.php'>";
			$n = 0;
			// echo $query;
			while ($row = mysqli_fetch_array($query)) {
				$n++;
				$product_id = $row["product_id"];
				$product_title = $row["product_title"];
				$product_price = $row["product_price"];
				$product_exchange = $row["exchange"];
				$product_image = $row["product_image"];
				$cart_item_id = $row["id"];
				$qty = $row["qty"];
				$total_duration = $row["total_duration"];
				if (strtolower($product_exchange) == "available") {
					$ans = msg_my();
				} else {
					$ans = "";
				}
				echo
				'<div class="row">
								<div class="col-md-2">
									<div class="btn-group">
										<a href="#" remove_id="' . $product_id . '" class="btn btn-danger remove"><span class="glyphicon glyphicon-trash"></span></a>
										<a href="#" update_id="' . $product_id . '" class="btn btn-primary update"><span class="glyphicon glyphicon-ok-sign"></span></a>
									</div>
								</div>
								<input type="hidden" name="product_id[]" value="' . $product_id . '"/>
								<input type="hidden" name="" value="' . $cart_item_id . '"/>
								<div class="col-md-1"><img class="img-responsive" src="product_images/' . $product_image . '"></div>
								<div class="col-md-1">' . $product_title . '</div>
								<div class="col-md-2"><input type="date" class="form-control start_date" value=" " name="start_date"></div>
								<div class="col-md-2"><input type="date" class="form-control end_date" value=" " name="end_date" ></div>
								<div class="col-md-1"><input type="text" class="form-control qty" value="' . $qty . '" ></div>
								<div class="col-md-1"><input type="text" class="form-control price" value="' . $product_price . '" readonly="readonly"></div>
								<div class="col-md-1 text-center">' . $product_exchange . '' . $ans . '</div>
								<div class="col-md-1"><input type="hidden" class="form-control total" value="' . $product_price . '" readonly="readonly"></div><br>
								<div class="col-md-1"><input type="hidden" class="form-control total_duration" value="' . $total_duration . '" readonly="readonly"></div><br>
							</div>';
			}

			echo '<div class="row">
							<br><div class="col-md-8"></div>
							<div class="col-md-2">
							<b class="net_total" style="font-size:20px; margin-top:200px;"></b></div>';



			if (!isset($_SESSION["uid"])) {
				echo '<div class="row">
							<br><div class="col-md-8"></div>
							<div class="col-md-2"><input type="submit" style="float:right;" name="login_user_with_product" class="btn btn-info btn-lg " value="Ready to Checkout" >
							</form></div>';
			} else if (isset($_SESSION["uid"])) {
				//Paypal checkout form

				echo '
						</form>
						<form action="" method="POST">
							<input type="hidden" name="cmd" value="_cart">
							<input type="hidden" name="business" value="shoppingcart@khanstore.com">
							<input type="hidden" name="upload" value="1">';

				$x = 0;
				$sql = "SELECT a.product_id,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.user_id='$_SESSION[uid]'";
				$query = mysqli_query($con, $sql);
				while ($row = mysqli_fetch_array($query)) {
					$x++;
					echo
					'<input type="hidden" name="item_name_' . $x . '" value="' . $row["product_title"] . '">
								  	 <input type="hidden" name="item_number_' . $x . '" value="' . $x . '">
								     <input type="hidden" name="amount_' . $x . '" value="' . $row["product_price"] . '">
								     <input type="hidden" name="quantity_' . $x . '" value="' . $row["qty"] . '">';
				}

				echo
				'<input type="hidden" name="return" value="http://localhost/project1/payment_success.php"/>
					                <input type="hidden" name="notify_url" value="http://localhost/KhanStore/payment_success.php">
									<input type="hidden" name="cancel_return" value="http://localhost/KhanStore/cancel.php"/>
									<input type="hidden" name="currency_code" value="USD"/>
									<input type="hidden" name="custom" value="' . $_SESSION["uid"] . '"/>
									<input style="float:right;margin-right:80px;" type="image" name="submit" id = "paymentInit"
									src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/blue-rect-paypalcheckout-60px.png" alt="PayPal Checkout"
									alt="PayPal - The safer, easier way to pay online">
								</form>';
			}
		}
	}
}

//Remove Item From cart
if (isset($_POST["removeItemFromCart"])) {
	$remove_id = $_POST["rid"];
	if (isset($_SESSION["uid"])) {
		$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND user_id = '$_SESSION[uid]'";
	} else {
		$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND ip_add = '$ip_add'";
	}
	if (mysqli_query($con, $sql)) {
		echo "<div class='alert alert-danger'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is removed from cart</b>
				</div>";
		exit();
	}
}


//Update Item From cart
if (isset($_POST["updateCartItem"])) {
	$update_id = $_POST["update_id"];
	$qty = $_POST["qty"];
	$start_date = $_POST["start_date"];
	$end_date = $_POST["end_date"];
	$start = strtotime($start_date);
	$end = strtotime($end_date);
	$days_between = ceil(abs($end - $start) / 86400);

	if ($start_date == "") {
		echo "<div class='alert alert-danger'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Start date is missing</b>
					</div>";
	} else if ($end_date == "") {
		echo "<div class='alert alert-danger'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>End date is missing </b>
					</div>";
	} else {
		if (isset($_SESSION["uid"])) {
			$sql = "UPDATE cart SET qty='$qty',start_date='$start_date', end_date='$end_date',total_duration='$days_between' WHERE p_id = '$update_id' AND user_id = '$_SESSION[uid]'";
		} else {
			$sql = "UPDATE cart SET qty='$qty',start_date='$start', end_date='$end',total_duration='$days_between' WHERE p_id = '$update_id' AND ip_add = '$ip_add'";
		}
		if (mysqli_query($con, $sql)) {
			echo "<div class='alert alert-info'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is updated</b>
					</div>";
			exit();
		}
	}
}

function msg_my()
{
	echo "<a href='#' class='text-info' style='float:right;' class='btn' data-target='#exchange_req' data-toggle='modal'>Send Exchange Request</a>";
}
?>

<div class="modal" id="exchange_req">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> &times;</button>
				<h3 class="modal-title">Exchange Offer</h3>
			</div>
			<div class="modal-body">
				<form action="cust_exchange_request.php" method="POST">
					<div class="form-group">
						<label>Your Product</label>
						<?php
						echo '<select class="form-control " name="product_name">';

						$user_id = $_SESSION['uid'];
						$cust_product = "SELECT * FROM products where user_id='$user_id'";
						$query = mysqli_query($con, $cust_product);
						if (mysqli_num_rows($query) > 0) {
							while ($row = mysqli_fetch_assoc($query)) {
								echo "<option value=" . $row['product_title'] . ">" . $row['product_title'] . "</option>";
							}
						}

						?>
						</select>
					</div>
					<button type="submit" class="btn btn-primary" name="req_exchange">Submit</button>
				</form>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>