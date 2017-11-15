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
        			<li class="search">
        				<button id='search' type='button' onclick="location.href='search.php'" class="btn btn-default navbar-btn" href="search.php">Search</button>
        			</li>
        			<li class="signin">
                            <?php $use=$_SERVER['REMOTE_USER'];  echo "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group'>Welcome $use</div><a href='search.php?tipo=name'><div  style='margin-left:10px' class='form-group search' onclick='location.href='search.php''><button id='search' type='button' onclick='location.href='search.php'' class='btn btn-default navbar-btn' onclick='location.href='search.php''>Product Search</button></div></a></form>";?>
        			</li>
        		</ul>
        	</div>
        </div>
    </nav>
    <a style="display:<?php if(count($_SESSION) == 0 || verificaUser($_SESSION["user_id"],$_GET["item_id"]) || $_GET["adminios"]=="adminmaquina"){echo 'none';}?>" href="formedit1.php?prodId=<?php echo $_GET["item_id"];?>" class="button"><button style='margin-top:3%'>Edit
    </button></a>
    <div class="row">
        <div class="col-xs-12">
            <div class='row list-group'>
            <?php
                include "openconn.php";
                global $conn;
                $item_id = $_GET["item_id"];
                $query = "select id_user, name, description, valor, start_date, end_date, keywords, fotos from products where id_product = $item_id";
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
                echo "<br><div class='col-xs-12 list-group-item'><div class='col-sm-4'><img src=".$image." class='img-thumbnail' alt=".$row[1]."></div><div class='col-sm-8'><h2>".$row[1]."</h2><br><h4 class='price'>Price: ".$row[3]." <span class='glyphicon glyphicon-bitcoin'></span></h4><br><p class='description'>Start Date: ".$row[4]."</p><p class='description'>End Date: ".$row[5]."</p><p class='description'>Keywords: ".$row[6]."</p><p class='description'>Description: ".$row[2]."</p></div></div>";
            ?>  
        </div>    
    </div>
    <?php
        if ($_SESSION["username"]){
            $item_id = $_GET["item_id"];
            echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><form method='post' action='item.php?item_id=$item_id'><div class='form-group'><label for='contact'>Contact the Seller</label><textarea name='message' id='contact' class='form-control'rows='5'></textarea></div><div class='form-group'><input class='btn btn-default' type='submit' name='contact' id='contact' value='Send'></form></div></div></div>";
            if ($_POST["contact"]){
                $mensagem = $_POST["message"];
                $send = $_SESSION["user_id"];
                $insertQuery = "insert into mensagens (id_sender, id_produto, mensagem) values('$send', '$item_id', '$mensagem')";
                $res = mysqli_query($conn, $insertQuery);
                if(!$res){
                    die("Erro de insercao na base de dados: ".$query);
                }
                mysqli_close($conn);
                header('Location: item.php?item_id='.$_GET["item_id"]);
            }
        }
        else{
           echo "<div class='row list-group'><div class='col-xs-12 list-group-item'><div class='alert alert-warning'>You need to be signed in to comment</div></div></div>";
        }
    ?>
    <div class="row list-group">
        <div class="col-xs-12 list-group-item">
            <div class="row">
                <div class="col-xs-12">
                    <h4>Q&A</h4>
                    <?php
                        include "openconn.php";
                        global $conn;
                        $item_id = $_GET["item_id"];
                        $query = "select id_sender, mensagem, id_mensagem from mensagens where (id_produto = $item_id) order by id_mensagem DESC limit 10 ";
                        $result = mysqli_query($conn, $query) or die("Error ". $query);
                        $num_rows = mysqli_num_rows($result);
                        if ($num_rows === 0){
                            echo "<div class='row'><div class='mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2'><div class='alert alert-warning' role='alert'>No questions... or awnsers...</div></div></div>";
                        }else{
                            while($row = mysqli_fetch_array($result, MYSQLI_NUM)){
                                $nick = "select nick from utilizadores where user_id = '$row[0]'";
                                 $nickResult = mysqli_query($conn, $nick) or die("Error ". $query);
                                $a = mysqli_fetch_array($nickResult, MYSQLI_NUM);
                                echo "<div class='row'><div class='col-xs-12'><div class='well well-sm'><h4>$a[0]</h4><p>$row[1]</p></div></div></div>";
                            }
                        }
                    ?>
               </div>
            </div>
        </div>
    </div>
</div>



<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>
