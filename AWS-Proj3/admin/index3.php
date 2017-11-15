<?php
include "openconn.php";
global $conn;
?>
<html>
<title>B Auction ADMIN</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="css/theme.css">
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
        	<div class="navbar-header">
        		<a class="navbar-brand" href="http://appserver.di.fc.ul.pt/~asw47139/admin">B Auction ADMIN</a>
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
        				<button id='profile' type='button' onclick="location.href='perfil.php'" class="btn btn-default navbar-btn" href="search.php">My Profile</button>
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
    				Welcome To B Auction ADMIN!
    			</h1>
    			<small class="tag-line">The Market place to everything!</small>
    		</div>
    	</div>
    </div>
    <div class="row">
    	<div class="col-xs-12 col-sm-8">
    		<div class="row list-group">
    			<?php
    				$query = "select id_product, name, description, valor, start_date, end_date, keywords, fotos from products order by id_product DESC limit 5";
    				$result = mysqli_query($conn, $query) or die("Error ". $query);
    				$num_rows = mysqli_num_rows($result);
    				if ($num_rows == 0){
    					echo "<h1>No Products were found :'(</h1>";
    				}
    				else{
    					while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
                            if ($row[7] == NULL){
    						  echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><a href='item.php?item_id=".$row[0]."'><div class='col-xs-12 col-xs-4'><img src='artigos/default.jpg' class='img-thumbnail' alt='".$row[1]."''></div><h4>".$row[1]."<span class='badge pull-right badge-default'>".$row[3]. " <span class='glyphicon glyphicon-bitcoin'></span></h4><p class='descript'><small class='description'>".$row[2]."</small></p></a></div></div>";
                            }
                            else{
                                echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><a href='item.php?item_id=".$row[0]."'><div class='col-xs-12 col-xs-4'><img src='".$row[7]."' class='img-thumbnail' alt='".$row[1]."''></div><h4>".$row[1]."<span class='badge pull-right badge-default'>".$row[3]. " <span class='glyphicon glyphicon-bitcoin'></span></h4><p class='descript'><small class='description'>".$row[2]."</small></p></a></div></div>";
                            }
    					}
    				}
    				mysqli_close($conn);
    			?>
    		</div>
    	</div>
    </div>


<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>

