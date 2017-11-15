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
    
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
        	<div class="navbar-header">
        		<a class="navbar-brand" href="http://appserver.di.fc.ul.pt/~asw47139/admin/">B Auction ADMIN</a>
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
                            <?php
                                $use=$_SERVER['REMOTE_USER'];        
                        
                                echo "<form class='navbar-form navbar-right' action='indexAdmin.php' method='post'><div class='form-group'>Welcome $use</div><a href='search.php?tipo=name'><div  style='margin-left:10px' class='form-group search' onclick='location.href='search.php''><button id='search' type='button' onclick='location.href='search.php'' class='btn btn-default navbar-btn' onclick='location.href='search.php''>Product Search</button></div></a></form>";
                            ?>
                    </li>
        			
        		</ul>
        	</div>
        </div>
    </nav>
    <div id="banner" class="page-header">
    	<div class="row">
    		<div style='margin-bottom:2.5%;' class="col-sm-6">
    			<h1 class="page-header-title">
    				Welcome To B Auction ADMIN!
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
                                            <li onclick="window.location.href='indexAdmin.php?tipo=username'" style='display:inline-block' role="presentation" class="<?php if ($tipo=="username"){echo 'active';}?> col-md-1.5"  ><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Username</a></li>

                                            <li  onclick="window.location.href='indexAdmin.php?tipo=country'" style='display:inline-block' class="<?php if ($tipo=="country"){echo 'active';}?> col-md-1.5" role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Country</a></li>

                                            <li  onclick="window.location.href='indexAdmin.php?tipo=county'" style='display:inline-block'  class="<?php if ($tipo=="county"){echo 'active';}?> col-md-1.5" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">District</a></li>

                                            <li  onclick="window.location.href='indexAdmin.php?tipo=name'" style='display:inline-block'  class="<?php if ($tipo=="name"){echo 'active';}?> col-md-1.5" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Name or Last Name</a></li>
                                            
                                            
                                            
                                        </ul>      
                                    </div>
                            </div>
			             </div>
                         <input style='margin-top:20px;' onclick="window.location.href='indexAdmin.php?tipo=username'" name="all" class="btn btn-primary" type="button" value='all'>
						</form>
					</div>
                        
				</div>
			    
			</div>
		</div>
		
        

    	</div>
    </div>
    
    <?php
			$search = $_POST["search"];
			$s = htmlspecialchars($search);
			include "openconn.php";
			if (!isset($s) || trim($s) == ''){
				$sql = "SELECT user_id,nick,pass,nome,apelido,sexo,email,pais,distrito,concelho,data_nascimento,foto,data_reg,active FROM utilizadores";
			}elseif($tipo=="username"){
				$sql = "SELECT user_id,nick,pass,nome,apelido,sexo,email,pais,distrito,concelho,data_nascimento,foto,data_reg,active FROM utilizadores WHERE nick='$s' OR email='$s' ";
			}elseif($tipo=="country"){
				$sql = "SELECT user_id,nick,pass,nome,apelido,sexo,email,pais,distrito,concelho,data_nascimento,foto,data_reg,active FROM utilizadores WHERE pais='$s' ";
			}elseif($tipo=="county"){
				$sql = "SELECT user_id,nick,pass,nome,apelido,sexo,email,pais,distrito,concelho,data_nascimento,foto,data_reg,active FROM utilizadores WHERE concelho='$s' ";
			}elseif($tipo=="nln"){
				$sql = "SELECT user_id,nick,pass,nome,apelido,sexo,email,pais,distrito,concelho,data_nascimento,foto,data_reg,active FROM utilizadores WHERE nome='$s' OR apelido='$s' ";
			}else{
				$sql = "SELECT user_id,nick,pass,nome,apelido,sexo,email,pais,distrito,concelho,data_nascimento,foto,data_reg,active FROM utilizadores WHERE nick='$s' OR email='$s' ";}
			
			$result = mysqli_query($conn, $sql) or die("Error".$sql);
			
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				$html[] = "<tr><td>".implode("</td><td>", $row)."</td></tr>";
			}
			$html = "<div class='span7'><div class='widget stacked widget-table action-table'><div class='widget-header'><i class='icon-th-list'></i></div><div class='widget-content'><table class='table table-striped table-bordered'><thead><tr><th>User_id</th><th>Nick</th><th>Password</th><th>Name</th><th>Last Name</th><th>Sex</th><th>Email</th><th>Country</th><th>District</th><th>Council</th><th>Birth Date</th><th>Profile Picture</th><th>Sign up day</th><th>Active</th></tr></thead><tbody>". implode("\n", $html) ."</tbody></table></div></div></div>";
			echo $html;
			mysqli_close($conn);
    ?>
    
    

<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>

