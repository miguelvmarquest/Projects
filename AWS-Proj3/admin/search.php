<?php 
    function after($this, $inthat){
        if (!is_bool(strpos($inthat, $this)))
            return substr($inthat, strpos($inthat,$this)+strlen($this));
    };
    if (after('proj2/',$_SERVER['REQUEST_URI'])=='search.php'){
        $tipo='name';
    }else{
        $tipo = $_GET["tipo"];
    }
?>

<?php
include "openconn.php";
global $conn;

?>
<html>
<title>B Auction ADMIN</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="css/theme.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
	var path = window.location.href;
	path = path.split('/');
	var ger = path[5];
	if (ger === "search.php?tipo=date"){
		$("#dia").css('display', 'block');
	}
	else{
		$("#dia").css('display', 'none');
	}
    $("#datissima").click(function(){
    	window.location.href='search.php?tipo=date';
    });
});
</script>   
    
<style>
.search {
    width: 100%;
    margin-top: 30px;
    padding: 0 10px 0 10px;
    font-weight: 700;
    font-size: 30px;
}
.search::-ms-clear{
    display: none;
}

</style>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
        	<div class="navbar-header">
        		<a class="navbar-brand" href="http://appserver.di.fc.ul.pt/~asw47139/admin/">B Auction ADMIN</a>
                
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
                            <?php $use=$_SERVER['REMOTE_USER']; echo "<form class='navbar-form navbar-right' action='index.php' method='post'><div style='margin-top:10px;'class='form-group'>Welcome $use</div></form>";?>
                    </li>
        			
        		</ul>
        	</div>
        </div>
    </nav>
    <div id="banner" class="page-header">
    	<div class="row">
    		<div style='margin-bottom:2.5%' class="col-sm-6">
    			<h1  class="page-header-title">
    				Let's search!
    			</h1>
    		</div>
		<div class="container">
			<div class="">
				<div class="row">
					 <div class="col-md-11">
						<form action="<?php echo after('proj2/',$_SERVER['REQUEST_URI']);?>" method="post">
						<div class="col-md-9">
							<input type="text" class="form-control" name='search' value="" placeholder="Search">
						</div>
				        <input name="go" class="btn btn-primary" type="submit" value='go'>
                        <div class="">
                            <div class="col-md-9">
                              <!-- Nav tabs -->
                                    <div class="well">
                                          <ul  class="nav nav-pills nav-stacked" role="tablist">
                                            <li onclick="window.location.href='search.php?tipo=name'" style='display:inline-block' role="presentation" class="<?php if ($tipo=="name"){echo 'active';}?> col-md-1.5"  ><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Name</a></li>

                                            <li  onclick="window.location.href='search.php?tipo=description'" style='display:inline-block' class="<?php if ($tipo=="description"){echo 'active';}?> col-md-1.5" role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Description</a></li>

                                            <li  onclick="window.location.href='search.php?tipo=keywords'" style='display:inline-block'  class="<?php if ($tipo=="keywords"){echo 'active';}?> col-md-1.5" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Keywords</a></li>

                                            <li style='display:inline-block' class="<?php if ($tipo=="date"){echo 'active';}?> col-md-1.5" role="presentation"><a id='datissima' href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Date</a></li>
                                          </ul>      
                                    </div>
                            </div>
			             </div>
						</form>
					</div>
                        <div id='dia' class="row">
                            <div class="col-xs-4 col-md-4">
                                <label>Day</label>
                                <select name="day" class="form-control">
                                    <?php for($i=1; $i<=31; $i++){
                                            echo "<option value='".sprintf("%02d", $i)."'>$i</option>";
                                        };	
                                        ?>
                                </select>
                            </div>
                            <div class="col-xs-4 col-md-4" style='margin-bottom:5%;'>
                                <label>Month</label>
                                <select name="month" class="form-control">
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            <div class="col-xs-4 col-md-4">
                                <label>Year</label>
                                <select name="year" class="form-control">
                                    <?php for($i=2016; $i>=1940; $i--){
                                            echo "<option value='$i'>$i</option>";
                                        };	
                                        ?>
                                </select>
                            </div>
                            <?php echo "<p class='text-danger'>$errData</p>";?>
                        </div>
				</div>
			    
			</div>
		</div>
		<?php
			$search = $_POST["search"];
			$s = htmlspecialchars($search);
            $pesquisaArray=explode(" ",$s);
			if (!isset($s) || trim($s) == ''){
				    $query = "select id_product, name, description, valor, start_date, end_date, keywords,fotos from products limit 5";
    				$result = mysqli_query($conn, $query) or die("Error ". $query);
    				$num_rows = mysqli_num_rows($result);
    				if ($num_rows == 0){
    					echo "<h1>No Products were found :'(</h1>";
    				}
    				else{
    					while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
    						if ($row[7] == NULL){
    						  echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><a href='item.php?item_id=".$row[0]."'><div class='col-xs-12 col-xs-4'><img src='../proj2/artigos/default.jpg' class='img-thumbnail' alt='".$row[1]."''></div><h4>".$row[1]."<span class='badge pull-right badge-default'>".$row[3]. " <span class='glyphicon glyphicon-bitcoin'></span></h4><p class='descript'><small class='description'>".$row[2]."</small></p></a></div></div>";
                            }
                            else{
                                echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><a href='item.php?item_id=".$row[0]."'><div class='col-xs-12 col-xs-4'><img src='../proj2/".$row[7]."' class='img-thumbnail' alt='".$row[1]."''></div><h4>".$row[1]."<span class='badge pull-right badge-default'>".$row[3]. " <span class='glyphicon glyphicon-bitcoin'></span></h4><p class='descript'><small class='description'>".$row[2]."</small></p></a></div></div>";
                            }
    					}
                    }
			}
            else{
                $sql = "SELECT id_product,$tipo FROM products";
                $result = mysqli_query($conn, $sql) or die("Error".$sql);

                $keyarray=array();

                while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                    if($cenas='description'){
                        $row[1]=str_replace(" ",";",$row[1]);
                    }
                    array_push($keyarray, array($row[0],explode(";",$row[1])));
                }
                $productsarray=array();
                for ($i=0; $i < count($keyarray); ++$i){
                    for ($j=0; $j < count($pesquisaArray); ++$j){
                        if(in_array($pesquisaArray[$j], $keyarray[$i][1]) && !in_array($keyarray[$i][0], $productsarray)){

                            array_push($productsarray, $keyarray[$i][0]);
                       }
                    }		
                }
                
                if (!count($productsarray)==0){
                    $sql = "SELECT id_product, name, description, valor, start_date, end_date, keywords,fotos FROM products WHERE id_product='$productsarray[0]'";
                    for ($l=1; $l<count($productsarray);++$l){
                        $sql = $sql . " OR id_product='$productsarray[$l]'";
                    }
                    $result = mysqli_query($conn, $sql) or die("Error".$sql);
                    while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
    						if ($row[7] == NULL){
    						  echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><a href='item.php?item_id=".$row[0]."'><div class='col-xs-12 col-xs-4'><img src='../proj2/artigos/default.jpg' class='img-thumbnail' alt='".$row[1]."''></div><h4>".$row[1]."<span class='badge pull-right badge-default'>".$row[3]. " <span class='glyphicon glyphicon-bitcoin'></span></h4><p class='descript'><small class='description'>".$row[2]."</small></p></a></div></div>";
                            }
                            else{
                                echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><a href='item.php?item_id=".$row[0]."'><div class='col-xs-12 col-xs-4'><img src='../proj2/".$row[7]."' class='img-thumbnail' alt='".$row[1]."''></div><h4>".$row[1]."<span class='badge pull-right badge-default'>".$row[3]. " <span class='glyphicon glyphicon-bitcoin'></span></h4><p class='descript'><small class='description'>".$row[2]."</small></p></a></div></div>";
                            }
    				}
                
                }else{
                    echo "<h1>No Products were found :'(</h1>";
                }
            }
            mysqli_close($conn);
        ?>
        

    	</div>
    </div>
    <div class="row">
    	<div class="col-xs-12 col-sm-8">
    		<div class="row list-group">
<!--
    			<?php
    				$query = "select id_product, name, description, valor, start_date, end_date, keywords from products limit 5";
    				$result = mysqli_query($conn, $query) or die("Error ". $query);
    				$num_rows = mysqli_num_rows($result);
    				if ($num_rows == 0){
    					echo "<h1>No Products were found :'(</h1>";
    				}
    				else{
    					while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
    						echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><a href='../proj2/item.php?item_id=".$row[0]."'><h4>".$row[1]."<span class='badge pull-right badge-default'>".$row[3]. " <span class='glyphicon glyphicon-bitcoin'></span></h4><p class='descript'><small class='description'>".$row[2]."</small></p></a></div></div>";
    					}
    				}
    				mysqli_close($conn);
    			?>
-->
    		</div>
    	</div>
    </div>


<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>

