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
global $conn;

$sql = "select * from products where end_date < CURDATE()";
$result = $conn->query($sql);
while ($row = mysqli_fetch_assoc($result)){
	$id_product = $row["id_product"];
	$sql = "select * from notifications where id_product = $id_product";
	$s = $conn->query($sql);
	if ($s->num_rows <= 0) {
		$ss = "select * from licitations where id_product = $id_product order by valor desc";
		$a = $conn->query($ss);
		while ($t = mysqli_fetch_assoc()){
			var_dump($t);
			echo $t["id_user"];
		}
	}
}
?>

<?php
function verificaUser($user,$product){
    include "openconn.php";
    $query = "select id_user from products where id_product=$product";
    $result = mysqli_query($conn, $query) or die("Error ". $query);
    $row = mysqli_fetch_array($result, MYSQLI_NUM);
    $num_rows = mysqli_num_rows($result);
    mysqli_close($conn);
    if ($num_rows == 0 || !($user==$row[0])){
        return true;
    }
    return false;
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
  <title>Bid Sequence</title>
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
 <div id="banner" class="page-header">
    	<div class="row">
    		<div class="col-sm-6">
    			<h1 class="page-header-title">
    				Bidding Sequence
    			</h1>
    		</div>
    	</div>
    </div>
    <div class="row" style="margin-top: 2%;">
        <div class="col-xs-12">
            <?php
                include "openconn.php";
                global $conn;
                $item_id = $_GET["item_id"];
                $query = "select * from licitations where id_product = $item_id;";
                $result = mysqli_query($conn, $query) or die("Error ". $query);
                $num_rows = mysqli_num_rows($result);
                if ($num_rows == 0){
                    echo "<div class='row'><div class='mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2'><div class='alert alert-warning' role='alert'>No bidding has occured</div></div></div>";
                }
                else{
                    while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
                      $u = $row[1];
                      $s = "select nick from utilizadores where user_id = $u";
                      $result = mysqli_query($conn, $s) or die("Error ". $query);
                      $use = mysqli_fetch_assoc($result);
                      $nick = $use["nick"];
                      echo "<div class='row'><div class='col-xs-12'><div class='well well-sm'><strong>User: </strong>$nick; <strong>Value: </strong>$row[3]</div></div></div>";
                    }
		                mysqli.close($conn);
                }

            ?>
        </div>
    </div>
</body>
</html>
