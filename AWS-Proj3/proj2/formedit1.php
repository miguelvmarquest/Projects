<?php
session_save_path('/tmp');
include "openconn.php";
global $conn;
session_start();
if (count($_SESSION) == 0){
    $signin = "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group' id='getUser'><input type='text' name='username' placeholder='Username or Email' required id='username'></div><div class='form-group' id='getPass'><input type='password' name='password' placeholder='Password' required id='passsword'></div>
    <div style='margin-left:5px' class='form-group' id='logIn'><input class='btn btn-default' type='submit' name='submit' id='submit' value='Log In'></div></form><form class='navbar-form navbar-right' action='signin.php' method='post'><div class='form-group' id='signIn'><input class='btn btn-default' type='submit' name='signin' id='signin' value='Sign Up'></div><form>";
    if (isset($_POST["submit"])){
        $username = htmlspecialchars($_POST["username"]);
        $password = md5($_POST["password"]);
        $query = "select user_id from utilizadores where (nick = '$username' or email='$username') and pass = '$password'";
        $result = mysqli_query($conn, $query) or die("Error ". $query);
        $num_row = mysqli_num_rows($result);
        mysqli_close($conn);
        if ($num_row > 0){
            $row = mysqli_fetch_array($result, MYSQLI_NUM);
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $row[0];
            header('Location: index.php?item_id="'.$_GET["item_id"].'"');
        }
        else{
            echo "<script>alert('Incorrect Log In');</script>";
        }
    }
}
else{
    $signin = "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group'>Ola ". $_SESSION["username"] . "</div><div style='margin-left:10px' class='form-group' id='logOut'><input class='btn btn-default' type='submit' name='logout' id='logout' value='Log Out'></div></form><form class='navbar-form navbar-right' action='form1.php' method='post'><div class='form-group' id='signIn'><input class='btn btn-default' type='submit' name='newProduct' id='newProduct' value='New Product'></div></form>";
    if (isset($_POST["logout"])){
        session_unset();
        header('Location: index.php');
    }
}

?>
 
<?php
    include "openconn.php";
    include "basecheck.php";
    $prodId = $_GET['prodId'];
	$sql = "SELECT id_product,id_user, name, description, valor, start_date, end_date, keywords, fotos FROM products where id_product= '$prodId'";
		
	$result = mysqli_query($conn, $sql) or die("Error".$sql);
		
	$keyarray=array();
	$row = mysqli_fetch_array($result, MYSQLI_NUM);
	for ($i=0; $i<8;++$i){
		array_push($keyarray,$row[$i]);
	}

	mysqli_close($conn);
?>


<html>
 <head>
 	   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/theme.css">
    <link href="bootstrap-notify-master/css/bootstrap-notify.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
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
  <title>Edit Product</title>
 </head>
 <body> <!--- background="../wooden.jpg" -->
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
 <div class="form-container">
 		<div class="success col-sm-10">
			<?php echo $success;?>
		</div>
		<br>
<form class="form-horizontal" role="form" method="GET" action="formedit1.php" enctype="multipart/form-data">
    <div style='display:none'class="form-group">

		<div class="col-sm-10">
			<input type="text" class="form-control" id="username" name="prodId" value="<?php echo $prodId;?>" placeholder="">
			<?php echo "<p class='text-danger'>$errProdName</p>";?>
		</div>
	</div>
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
		<label for="email" class="col-sm-2 control-label">Base Value (<span class='glyphicon glyphicon-bitcoin'></span>)</label>
		<div class="col-sm-4">
			<input type="number" class="form-control" id="email" name="baseValue" value="<?php echo $keyarray[4];?>" placeholder="0.0" <?php if (strtotime(date('d-m-Y', strtotime($keyarray[5]))) < strtotime(date('d-m-Y')) ){echo 'disabled';};?>>
			<?php echo "<p class='text-danger'>$errBaseValue</p>";?>
		</div>
	</div>
	
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Starting Date</label>
		<div class="col-sm-2">
			<input type="date" class="form-control" id="email" name="StartingDate" value="<?php echo $keyarray[5];?>" placeholder="YYYY-MM-DD" <?php if (strtotime(date('d-m-Y', strtotime($keyarray[5]))) < strtotime(date('d-m-Y')) ){echo 'disabled';};?>>
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
<?php
		
	$today = date("Y-m-d");
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

        $conta=0;

        include "openconn.php";
		if(!($keyarray[2]==$prodName || !isset($prodName) || trim($prodName) == '' )){
            ++$conta;
			$sql= "UPDATE products SET name='$prodName' WHERE id_product= $prodId";
            $res = mysqli_query($conn, $sql); 
		if(!$res){
			die("Error, insert query failed:".$sql);
		}
        
        }
		if(!($keyarray[3]==$description || !isset($description) || trim($description) == '' )){
            ++$conta;
			$sql= "UPDATE products SET description='$description' WHERE id_product=$prodId";
             $res = mysqli_query($conn, $sql); 
            if(!$res){ 
                die("Error, insert query failed:".$sql);
            }
      
		} 
		if(!($keyarray[4]==$baseValue || !isset($baseValue) || trim($baseValue) == '' )){
            ++$conta;
			$sql= "UPDATE products SET valor='$baseValue' WHERE id_product=$prodId";
             $res = mysqli_query($conn, $sql); 
            if(!$res){
                die("Error, insert query failed:".$sql);
            }
        
		} 
		if(!($keyarray[5]==$sDate || !isset($sDate) || trim($sDate) == '' )){
            ++$conta;
			$sql= "UPDATE products SET start_date='$sDate' WHERE id_product=$prodId";
             $res = mysqli_query($conn, $sql); 
		if(!$res) {
			die("Error, insert query failed:".$sql);
		}
        
		} 
		if(!($keyarray[6]==$eDate || !isset($eDate) || trim($eDate) == '' )){
            ++$conta;
			$sql= "UPDATE products SET end_date='$eDate' WHERE id_product=$prodId";
             $res = mysqli_query($conn, $sql); 
		if(!$res) {
			die("Error, insert query failed:".$sql);
            
		}
        
		} 
		if(!($keyarray[7]==$keyWords || !isset($keyWords) || trim($keyWords) == '' )){
            ++$conta;
			$sql= "UPDATE products SET keywords='$keyWords' WHERE id_product=$prodId";
             $res = mysqli_query($conn, $sql); 
            if(!$res){
                die("Error, insert query failed:".$sql);
            }
            mysqli_close($conn); 
		}
        else{mysqli_close($conn);}
        if ($conta>0){
		echo "<style>.form-horizontal{display:none}</style>";
        echo "<h3><p class='success text-success'>Product Uploaded with success.
			</p></h3>";
        echo "<script>$(document).ready(function(){
				setTimeout(function(){window.location=\"index.php\";}, 2500);
			})</script>";
        }
		

?>

 </body>
</html>