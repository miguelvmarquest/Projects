<?php
		include "openconn.php";
		include "basecheck.php";
		$today = date("Y-m-d");

		$prodId = $_GET['prodId'];
		

	if(isset($_GET["submit"])){
		$target_file = $target_path.basename($_FILES["foto"]["name"]);
		$type = $_FILES["foto"]["type"];
		$prodName = htmlspecialchars($_GET["ProdName"]);
		$description = htmlspecialchars($_GET['description']);
		$baseValue = intval($_GET['baseValue']);
		
		$sDAte = $_GET["StartingDate"];
		$eDAte = $_GET["EndingDate"];
		
		$keyWords = $_GET["keyWords"];
	
	
	
	
		if (! $_GET["ProdName"]){
			$errProdName = "You did not insert a Product Name.";
		}
		if(! $_GET["description"]){
			$errDescription = "You did not insert a description.";
		}
		if(! $_GET["baseValue"]){
			$errBaseValue = "You did not insert a Base Value.";
		}
		
		if (! $_GET["StartingDate"]){
			$errsDate = "Date not valid";
		}
		if (! $_GET["EndingDate"]){
			$erreDate = "Date not valid";
		}
		
		$check = getimagesize($_FILES["foto"]["tmp_name"]);
		if ($_FILES["foto"]["name"]){
		    if($check == false) {
		        $errFoto = "File is not an image";
		        $uploadOk = 0;
		    }
		    if ($_FILES["foto"]["size"] > 500000) {
		    	$errFoto = "The file is too big.";
		    	$uploadOk = 0;	
			}
	
			if($type != "image/jpg" && $type != "image/png" && $type != "image/jpeg" && $type != "image/gif" ) {
			    $errFoto = "Only .jpg, .png, .jpeg, .gif alowed.";
			    $uploadOk = 0;
			}
		}
		
	}



	$sql = "SELECT id_product,id_user, name, description, valor, start_date, end_date, keywords, fotos FROM products where id_product= '$prodId'";
		
	$result = mysqli_query($conn, $sql) or die("Error".$sql);
		
	$keyarray=array();
	$row = mysqli_fetch_array($result, MYSQLI_NUM);
	for ($i=0; $i<8;++$i){
		array_push($keyarray,$row[$i]);
	}
	print_r($keyarray);
	mysqli_close($conn);


		
		if($keyarray[2]!=$prodName){
			$sql= "UPDATE products SET name='$prodName' WHERE id_product= '$prodId'";
		}
		if($keyarray[3]!=$description){
			$sql= "UPDATE products SET description='$description' WHERE id_product='$prodId'";
		} 
		if($keyarray[4]!=$baseValue){
			$sql= "UPDATE products SET valor='$baseValue' WHERE id_product='$prodId'";
		} 
		if($keyarray[5]!=$sDate){
			$sql= "UPDATE products SET start_date='$sDate' WHERE id_product='$prodId'"; 
		} 
		if($keyarray[6]!=$eDate){
			$sql= "UPDATE products SET end_date='$eDate' WHERE id_product='$prodId'";
		} 
		if($keyarray[7]!=$keyWords){
			$sql= "UPDATE products SET keywords='$keyWords' WHERE id_product='$prodId'";
		}

		
		
		

?>

<html>
 <head>
 	<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
 	<link rel="stylesheet" href="home.css">
  <title>Edit Product</title>
 </head>
 <body> <!--- background="../wooden.jpg"--->
 	<nav class="navbar navbar-default navbar-fixed-top">
  		<div class="container">
  			<!-- <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link">Mark Otto</a></p>  para quando o log in estiver feito-->
  			<div class="bauction"><a href="../index.php"><h3 class="bauction">B Auction</h3></a></div>

		<a role="button" href="login.html" class="login btn btn-default navbar-right-btn">Log in</a>
  		</div>
	</nav>
 <div class="form-container">
 		<div class="success col-sm-10">
			<?php echo $success;?>
		</div>
		<br>
<form class="form-horizontal" role="form" method="GET" action="formedit1.php" enctype="multipart/form-data">
	<div class="form-group">
		<label for="prodName" class="col-sm-2 control-label">Product Name</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="username" name="ProdName" value="<?php echo $keyarray[2];?>" placeholder="Product Name">
			<?php echo "<p class='text-danger'>$errProdName</p>";?>
		</div>
	</div>
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Description</label>

		<div class="col-sm-10 row-sm-4">
			<input type="text" class="form-control" id="nome" name="description" value="<?php echo $keyarray[3];?>" placeholder="Description">
			<?php echo "<p class='text-danger'>$errDescription</p>";?>
		</div>
		
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Base Value</label>
		<div class="col-sm-4">
			<input type="number" class="form-control" id="email" name="baseValue" value="<?php echo $keyarray[4];?>" placeholder="0.0">€
			<?php echo "<p class='text-danger'>$errBaseValue</p>";?>
		</div>
	</div>
	
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Starting Date</label>
		<div class="col-sm-2">
			<input type="date" class="form-control" id="email" name="StartingDate" value="<?php echo $keyarray[5];?>" placeholder="YYYY-MM-DD">
			<?php echo "<p class='text-danger'>$errsDate</p>";?>
		</div>
	</div>

	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Ending Date</label>
		<div class="col-sm-2">
			<input type="date" class="form-control" id="email" name="EndingDate" value="<?php echo $keyarray[6];?>" placeholder="YYYY-MM-DD">
			<?php echo "<p class='text-danger'>$erreDate</p>";?>
		</div>
	</div>
	
	
	
	
	<div class="form-group">
		    <label for="foto" class="col-sm-2 control-label">Product picture</label>
		    <div class="col-sm-2">
    			<input id="foto" name="foto" type="file" class="file">
    			<?php echo "<p class='text-danger'>$errFoto</p>";?>
    		</div>
	</div>
	<div class="form-group">
		<label for="keyWords" class="col-sm-2 control-label">Key words</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="username" name="keyWords" placeholder="Key words separated by ';'" value="<?php echo $keyarray[7];?>">
		</div>
	</div>
	<div class="form-group">

		<div class="col-sm-10 col-sm-offset-2">
			<input id="submit" name="submit" type="submit" value="Edit" class="btn btn-primary">
		</div>
	</div>
</form>
</div>


 </body>
