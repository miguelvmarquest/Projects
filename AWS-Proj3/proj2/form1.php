<?php
session_save_path('/tmp');
include "openconn.php";
global $conn;
session_start();
if (count($_SESSION) == 0){
	$signin = "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group' id='getUser'><input type='text' name='username' placeholder='Username or Email' required id='username'></div><div class='form-group' id='getPass'><input type='password' name='password' placeholder='Password' required id='passsword'></div>
	<div style='margin-left:5px'class='form-group' id='logIn'><input class='btn btn-default' type='submit' name='submit' id='submit' value='Log In'></div></form>";
	if (isset($_POST["submit"])){
		$username = htmlspecialchars($_POST["username"]);
		$password = md5($_POST["password"]);
		$query = "select nome from utilizadores where (nick = '$username' or email='$username') and pass = '$password'";
		$result = mysqli_query($conn, $query) or die("Error ". $query);
		$num_row = mysqli_num_rows($result);
		mysqli_close($conn);
		if ($num_row > 0){
			$_SESSION["username"] = $username;
			header('Location: index.php');
		}
		else{
			echo "<script>alert('Incorrect Log In');</script>";
		}
	}
}
else{
	$id_user = $_SESSION["user_id"];
    $signin = "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group'>Ola ". $_SESSION["username"] . "</div><div style='margin-left:10px' class='form-group' id='logOut'><input class='btn btn-default' type='submit' name='logout' id='logout' value='Log Out'></div></form><form class='navbar-form navbar-right' action='form1.php' method='post'><div class='form-group' id='signIn'><input class='btn btn-default' type='submit' name='newProduct' id='newProduct' value='New Product'></div></form>";
	if (isset($_POST["logout"])){
		session_unset();
		header('Location: index.php');
	}
}
?>


<html>
 <head>
 	<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
 	<link rel="stylesheet" href="css/theme.css">
 	<link rel="stylesheet" href="css/home.css">
	<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
	    <script src="notify.min.js" ></script>
    <script>
	function bidding(va) {
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (xhttp.readyState == 4 && xhttp.status == 200) {
		var response = xhttp.responseText;
		if (response.length > 0){
			$.notify("You have been surpased in a auction", "warn");
		}
	    }
	  };
	  xhttp.open("GET", "big.php?user_id=" + va, true);
	  xhttp.send();   
	}
	window.onload = function () {
		setInterval(function() {
			var user = <?php echo $_SESSION['user_id'];?> ;
			bidding(user);
		}, 500);
	};
    </script>

  <title>Submit Product</title>
</head>
 <body>
 <?php
include "openconn.php";
include "basecheck.php";

$target_path = "artigos/";
$uploadOK = 1;
$today = date("Y-m-d");

if(isset($_POST["send"])){
	$prodName = htmlspecialchars($_POST["ProdName"]);
	$description = htmlspecialchars($_POST['description']);
	$baseValue = intval($_POST['baseValue']);
	$sDay = $_POST["day"];
	$sMonth = $_POST["month"];
	$sYear = $_POST["year"];
	$eDay = $_POST["day1"];
	$eMonth = $_POST["month1"];
	$eYear = $_POST["year1"];
	$sDate = strtotime($sDay."-".$sMonth."-".$sYear);
	$eDate = strtotime($eDay."-".$eMonth."-".$eYear);
	$sDate1 = ($sYear."-".$sMonth."-".$sDay);
	$eDate1 = ($eYear."-".$eMonth."-".$eDay);
	$keyWords = $_POST["keyWords"];
	
	
	
	
	if (! $_POST["ProdName"]){
		$errProdName = "You did not insert a Product Name.";
	}
	if(! $_POST["description"]){
		$errDescription = "You did not insert a description.";
	}
	if(! $_POST["baseValue"]){
		$errBaseValue = "You did not insert a Base Value.";
	}
	
	if ($sDate < strtotime(date('d-m-Y'))){
		$errsDate = "Date not valid";
	}
	if ($eDate < strtotime(date('d-m-Y')) || $eDate < $sDate){
		$erreEDate = "Date not valid";
	}
	if (! $_POST["keyWords"]){
		$errkeyWords = "You did not insert key words.";
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

		if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $_FILES["foto"]["type"]) ) {
		    $errFoto = "Apenas .jpg, .png, .jpeg, .gif são permitidos.";
		    $uploadOk = 0;
		}
	}
	if (!$errProdName && !$errDescription && !$errBaseValue && !$errsDate && !$erreDate && !$errkeyWords){
		preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $_FILES["foto"]['name'], $ext);
		$target_file = $target_path . md5(uniqid(time())) . "." . $ext[1];
		if (! empty($_FILES["foto"]["name"]) && move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
	        global $conn;
			$query = "insert into products (name, id_user, description, valor, start_date, end_date, keywords, fotos) values('$prodName', $id_user, '$description', '$baseValue', '$sDate1', '$eDate1', '$keyWords', '$target_file')";
			$res = mysqli_query($conn, $query);
			if(!$res){
				die("Error inserting into database: ".$query);
			}
			echo "<script>$(document).ready(function(){
				$('.form-unit').children().css('display', 'none');
				$('.form-unit').append('<div class=\"row\"><div class=\"mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2\"><div class=\"alert alert-success\" role=\"alert\">A new product has been put to auction!</div></div></div>');
				setTimeout(function(){window.location=\"index.php\";}, 5000);
			})</script>";
			mysqli_close($conn);
			
	    } else if (empty($_FILES["foto"]["name"])) {
	    	global $conn;
			$query = "insert into products (name, id_user, description, valor, start_date, end_date, keywords) values('$prodName', $id_user, '$description', '$baseValue', '$sDate1', '$eDate1', '$keyWords')";
			$res = mysqli_query($conn, $query);
			if(!$res){
				die("Error inserting data into database: ".$query);
			}
			echo "<script>$(document).ready(function(){
				$('.form-unit').children().css('display', 'none');
				$('.form-unit').append('<div class=\"row\"><div class=\"mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2\"><div class=\"alert alert-success\" role=\"alert\">A new product has been put to auction!</div></div></div>');
				setTimeout(function(){window.location=\"index.php\";}, 5000);
			})</script>";
			mysqli_close($conn);
			

		}else {
	        $errFoto ="A problem appeared while sending the file.";
	    }
	}

}
?>
	<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
        	<div class="navbar-header">
        		<a class="navbar-brand" href="http://appserver.di.fc.ul.pt/~asw47139/proj2/index.php">B Auction</a>
        		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main" aria-expanded="false">
		        	<span class="sr-only">Toggle navigation</span>
		        	<span class="icon-bar"></span>
		        	<span class="icon-bar"></span>
		        	<span class="icon-bar"></span>
		    	</button>
        	</div>
        	<div id="navbar-main" class="collapse navbar-collapse pull-right">
        		<ul class="nav navbar-nav">
 <li style="margin-right:15px"  class="profile">
        				<button style='display: <?php if (count($_SESSION) == 0){echo 'none';}?>;' id='profile' type='button' onclick="location.href='perfil.php'" class="btn btn-default navbar-btn">My Profile</button>
        			</li>
        			<li class="search">
        				<button id='search' type='button' onclick="location.href='search.php'" class="btn btn-default navbar-btn" href="search.php">Search</button>
        			</li>
        			<li class="signin">
        				<?php echo $signin?>
        			</li>
        		</ul>
        	</div>
        </div>
    </nav>
    <div class="page-header">
    	<div class="row">
    		<div class="col-lg-6">
    			<h1 class="sign-up-header">New Product</h1>
    			<small class="sign-up-text">Please fill the form to enter a new product for auction</small>
    		</div>
    	</div>
    </div>
    <div class="row form-unit">
    	<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    		<div class="row list-group">
    			<div class="col-xs-12 list-group-item ">
					<form class="form-horizontal" role="form" method="post" action="form1.php" enctype="multipart/form-data">
						<div style="margin-bottom: 2%" class="form-group">
						<label>Product Name</label>
						<div class="input-group">
    						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
    						<input type="text" class="form-control" name="ProdName" id='username' placeholder="Product Name"/>
    						<?php echo "<p class='text-danger'>$errProdName</p>";?>
    					</div>
					</div>
					<div style="margin-bottom: 2%" class="form-group">
						<label>Description</label>
						<div class="input-group">
    						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
    						<input type="text" class="form-control" name="description" id='nome' placeholder="Description"/>
    						<?php echo "<p class='text-danger'>$errDescription</p>";?>
    					</div>
					</div>
					<div style="margin-bottom: 2%" class="form-group">
						<label>Base Value</label>
						<div class="input-group">
    						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
    						<input type="number" class="form-control" name="baseValue" id='nome' placeholder="0,0"/>
    						<?php echo "<p class='text-danger'>$errBaseValue</p>";?>
    					</div>
					</div>
	<div style="margin-bottom: 2%" class="form-group">
							<label>Starting Date</label>
							<div class="row">
								<div class="col-xs-4 col-md-4">
									<label>Day</label>
									<select name="day" class="form-control">
										<?php for($i=1; $i<=31; $i++){
                                                $valueD='';
                                                if ($i==date('d')){
                                                    $valueD='selected';
                                                }
												echo "<option value='".sprintf("%02d", $i)."' $valueD>$i</option>";
											};	
											?>
									</select>
								</div>
								<div class="col-xs-4 col-md-4">
									<label>Month</label>
									<select name="month" class="form-control">
											<option value="01" <?php if (date('m')==01){echo 'selected';}?>>January</option>
											<option value="02" <?php if (date('m')==02){echo 'selected';}?>>February</option>
											<option value="03" <?php if (date('m')==03){echo 'selected';}?>>March</option>
											<option value="04" <?php if (date('m')==04){echo 'selected';}?>>April</option>
											<option value="05" <?php if (date('m')==05){echo 'selected';}?>>May</option>
											<option value="06" <?php if (date('m')==06){echo 'selected';}?>>June</option>
											<option value="07" <?php if (date('m')==07){echo 'selected';}?>>July</option>
											<option value="08" <?php if (date('m')==08){echo 'selected';}?>>August</option>
											<option value="09" <?php if (date('m')==09){echo 'selected';}?>>September</option>
											<option value="10" <?php if (date('m')==10){echo 'selected';}?>>October</option>
											<option value="11" <?php if (date('m')==11){echo 'selected';}?>>November</option>
											<option value="12" <?php if (date('m')==12){echo 'selected';}?>>December</option>
										</select>
									</div>
								<div class="col-xs-4 col-md-4">
									<label>Year</label>
									<select name="year" class="form-control">
										<?php for($i=date("Y");$i<=2050;$i++){
												echo "<option value='$i'>$i</option>";
											};	
											?>
									</select>
								</div>
								<?php echo "<p style='margin-left:20px;' class='text-danger'>$errsDate</p>";?>
							</div>
						</div>
	<div style="margin-bottom: 2%" class="form-group">
							<label>Ending Date</label>
							<div class="row">
								<div class="col-xs-4 col-md-4">
									<label>Day</label>
									<select name="day1" class="form-control">
										<?php for($i=1; $i<=31; $i++){
												$valueD='';
                                                if ($i==date('d')){
                                                    $valueD='selected';
                                                }
												echo "<option value='".sprintf("%02d", $i)."' $valueD>$i</option>";
											};	
											?>
									</select>
								</div>
								<div class="col-xs-4 col-md-4">
									<label>Month</label>
									<select name="month1" class="form-control">
											<option value="01" <?php if (date('m')==01){echo 'selected';}?>>January</option>
											<option value="02" <?php if (date('m')==02){echo 'selected';}?>>February</option>
											<option value="03" <?php if (date('m')==03){echo 'selected';}?>>March</option>
											<option value="04" <?php if (date('m')==04){echo 'selected';}?>>April</option>
											<option value="05" <?php if (date('m')==05){echo 'selected';}?>>May</option>
											<option value="06" <?php if (date('m')==06){echo 'selected';}?>>June</option>
											<option value="07" <?php if (date('m')==07){echo 'selected';}?>>July</option>
											<option value="08" <?php if (date('m')==08){echo 'selected';}?>>August</option>
											<option value="09" <?php if (date('m')==09){echo 'selected';}?>>September</option>
											<option value="10" <?php if (date('m')==10){echo 'selected';}?>>October</option>
											<option value="11" <?php if (date('m')==11){echo 'selected';}?>>November</option>
											<option value="12" <?php if (date('m')==12){echo 'selected';}?>>December</option>
										</select>
									</div>
								<div class="col-xs-4 col-md-4">
									<label>Year</label>
									<select name="year1" class="form-control">
										<?php for($i=date("Y");$i<=2050;$i++){
												echo "<option value='$i'>$i</option>";
											};	
											?>
									</select>
							</div>
						</div>
		<?php echo "<p style='margin-left:20px;' class='text-danger'>$erreEDate</p>";?>
	</div>
	
	<div style="margin-bottom: 2%"  class="form-group">
				    		<label>Product Picture</label>
				    		<div class="row">
							    <div class="col-sm-2">
					    			<input id="foto" name="foto" type="file" class="file">
					    			<?php echo "<p class='text-danger'>$errFoto</p>";?>
					    		</div>
				    		</div>
				    	</div>
	<div style="margin-bottom: 2%" class="form-group">
						<label>Keywords</label>
						<div class="input-group">
    						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
    						<input type="text" class="form-control" name="keyWords" id='username' placeholder="Keywords separated by ';'"/>
    						<?php echo "<p class='text-danger'>$errkeyWords</p>";?>
    					</div>
					</div>
	<div class="form-group">
				    		<div class="row">
							<div class="col-sm-6">
								<input id="submit" name="send" type="submit" value="Send" class="btn btn-primary">
							</div>
						</div>
						</div>
</form>
</div>
</div>
</div>
</div>
</div>
 </body>
</html>
