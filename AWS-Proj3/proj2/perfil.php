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
            $id_user=$row[0];
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $id_user;
            
        }
        else{
            echo "<script>alert('Incorrect Log In');</script>";
        }
    }
}
else{
    $id_user=$_SESSION["user_id"];
    $signin = "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group'>Ola ". $_SESSION["username"] . "</div><div style='margin-left:10px' class='form-group' id='logOut'><input class='btn btn-default' type='submit' name='logout' id='logout' value='Log Out'></div></form><form class='navbar-form navbar-right' action='form1.php' method='post'><div class='form-group' id='signIn'><input class='btn btn-default' type='submit' name='newProduct' id='newProduct' value='New Product'></div></form>";
    if (isset($_POST["logout"])){
        session_unset();
        header('Location: index.php');
    }
}
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
  <title>Profile</title>
 </head>
<body>
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
    <div class="row">
        <div class="col-xs-12">
            <div class='row list-group'>
            <?php
                include "openconn.php";
                global $conn;
                $query = "select user_id,nome,apelido,sexo,email,pais,data_nascimento,foto from utilizadores where user_id='$id_user'";
                $result = mysqli_query($conn, $query) or die("Error ". $query);
                $row = mysqli_fetch_array($result, MYSQLI_NUM);
                $num_row = mysqli_num_rows($result);

                if($row[7] == NULL){
                    $image = 'artigos/default.jpg';
                }
                else{
                    $imageRow = mysqli_fetch_array($result, MYSQLI_NUM);
                    $image = $row[7];
                }
                echo "
<br>
<div class='col-xs-12 list-group-item'>
	<div class='col-sm-4'>
		<img src=".$image." class='img-thumbnail' alt=".$row[1].">
	</div>
	<div class='col-sm-8'>
		<h2>".$row[1]." ".$row[2]." <a href='searchutilizadores.php?tipo=name&id_user=".$_SESSION['user_id']."'><button class='btn btn-default navbar-btn pull-right'>My Products</button></a></h2>
        
		<br>
		<h5 class='price'>Sex: ".$row[3]." <a href='auctionontherun.php'><button class='btn btn-default navbar-btn pull-right'>My Bids</button></a></h5>
		<br>
		<h5 class='price'>Email: ".$row[4]." <a href='auctionshistory.php'><button class='btn btn-default navbar-btn pull-right'>My Bids History</button></a></h5>
		<br>
		<h5 class='price'>País: ".$row[5]."</h5>
		<br>
		<h5 class='price'>Data de nascimento: ".$row[6]."</h5>
	</div>
</div>";
?>  
        </div>    
    </div>
    
    
</div>
</body>
</html>
