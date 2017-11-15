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
<title>B Auction</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="css/theme.css">
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
        			<li class="signin">
                            <?php $use=$_SERVER['REMOTE_USER'];  echo "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group'>Welcome $use</div><a href='search.php?tipo=name'><div  style='margin-left:10px' class='form-group search' onclick='location.href='search.php''><button id='search' type='button' onclick='location.href='search.php'' class='btn btn-default navbar-btn' onclick='location.href='search.php''>Product Search</button></div></a></form>";?>
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
                        echo "<div class='row'><div class='col-xs-12'><div class='well well-sm'><strong>User: </strong>$row[1] ; <strong>Value: </strong>$row[3]</div></div></div>";
                    }
		    mysqli.close($conn);
                }

            ?>
        </div>
    </div>
</body>
</html>
