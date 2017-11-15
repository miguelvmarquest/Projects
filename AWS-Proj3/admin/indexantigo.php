<?php $tipo = $_POST["tipo"];?>
<html>
<title> BA Admin </title>
<html>
 <head>
    <script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="home.css">
  <title>Sign In</title>
 </head>
 <body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link">Mark Otto</a></p>  para quando o log in estiver feito-->
		<div style='float:left;' class="bauction" id="yas"><a href="../proj1/index.php"><h3 class="bauction">B Auction</h3></a></div>
		<div class="bauction" ><a href="index.php"><h3 class="bauction">B Auction ADMIN</h3></a></div>
		
	    <div class="logsign">
	   
	    </div>
        </div>
    </nav>

<body background="woo.jpg">
	<div class="admin">
		 User Infos: <button type="button" id="show">Off</button> 
		 <button type="button" id="hide">On</button>
		Sales Stats: <button type="button" id="show1">Off</button> 
		 <button type="button" id="hide1">On</button>
		User Feedback: <button type="button" id="show2">Off</button> 
		 <button type="button" id="hide2">On</button>
		
		
		</div>
		
		<!--- USERS INFOS--->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script>
		$(document).ready(function(){
 		   $("#show").click(function(){
  		      $(".table").hide();
  		  });
  		  $("#hide").click(function(){
  		      $(".table").show();
  		  });

		});

		</script>
		
		<div class="table">
			<h3 class="names">Users Info</h3><br>
			<div style="float:left">
			<form action="index.php" method="post">
				Search: <input type="text" name="search" value=''>
				<input type="submit" value='go'>
				Type: <input type="radio" name="tipo" value="username" onclick="javascript: submit()" <?php if ($tipo=="username") echo "checked";?> checked> Username  
				<input type="radio" name="tipo" value="country" onclick="javascript: submit()" <?php if ($tipo=="country") echo "checked";?>>Country
				<input type="radio" name="tipo" value="county" onclick="javascript: submit()" <?php if ($tipo=="county") echo "checked";?>> County
				<input type="radio" name="tipo" value="nln" onclick="javascript: submit()" <?php if ($tipo=="nln") echo "checked";?>> Name or Last name
			</form>
			</div>
			<form action="index.php" method="post">
				<input style="display:none" type="text" name="search" value=''>
				<input type="submit" value='all'>
			</form>
			
			<?php
			$search = $_POST["search"];
			$s = htmlspecialchars($search);
			include "openconn.php";
			if (!isset($s) || trim($s) == ''){
				$sql = "SELECT * FROM utilizadores";
			}elseif($tipo=="username"){
				$sql = "SELECT * FROM utilizadores WHERE nick='$s' OR email='$s' ";
			}elseif($tipo=="country"){
				$sql = "SELECT * FROM utilizadores WHERE pais='$s' ";
			}elseif($tipo=="county"){
				$sql = "SELECT * FROM utilizadores WHERE concelho='$s' ";
			}elseif($tipo=="nln"){
				$sql = "SELECT * FROM utilizadores WHERE nome='$s' OR apelido='$s' ";
			}else{
				$sql = "SELECT * FROM utilizadores WHERE nick='$s' OR email='$s' ";}
			
			$result = mysqli_query($conn, $sql) or die("Error".$sql);
			
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				$html[] = "<tr><td>".implode("</td><td>", $row)."</td></tr>";
			}
			$html = "<table style='width:40%;' id='t01'><tr><th>user_id</th><th>nick</th><th>pass</th><th>nome</th><th>apelido</th><th>sexo</th><th>email</th><th>pais</th><th>concelho</th><th>data de nascimento</th><th>distrito</th><th>foto</th><th>active</th><th>data de registo</th>". implode("\n", $html) ."</table>";
			echo $html;
			mysqli_close($conn);
			?>


			<!--<?php
			include "openconn.php";
			$sql = "SELECT * FROM utilizadores";
			$result = mysqli_query($conn, $sql) or die("Error".$sql);
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
				$html[] = "<tr><td>".implode("</td><td>", $row)."</td></tr>";
			}
			$html = "<table style='width:40%;' id='t01'><tr><th>user_id</th><th>nick</th><th>pass</th><th>nome</th><th>apelido</th><th>sexo</th><th>email</th><th>pais</th><th>concelho</th><th>data de nascimento</th><th>distrito</th><th>foto</th><th>active</th><th>data de registo</th>". implode("\n", $html) ."</table>";
			echo $html;
			mysqli_close($conn);
			?> -->
				

		</div>
		
		<!--- USERS INFO OVER--->

		<!--- SALES STATS--->
					<script>
		$(document).ready(function(){
 		   $("#show1").click(function(){
  		      $(".stats").hide();
  		  });
  		  $("#hide1").click(function(){
  		      $(".stats").show();
  		  });
		});
		</script>
		<div class="hidet" id="hidet">
			

		<div class="stats">
				<h3 class="names">Sales Stats</h3><br>
				<img class="stats"src="img/stats.jpg"><br><br><br>
				<img class="stats"src="img/views.jpg">
		</div>

		
		<!--- SALES STATS OVER--->

		<!--- USERS FEEDBACK--->
		<script>
		$(document).ready(function(){
 		   $("#show2").click(function(){
  		      $(".feedback").hide();
  		  });
  		  $("#hide2").click(function(){
  		      $(".feedback").show();
  		  });
		});
		</script>
		<div class="feedback">
			<h3 class="names">Users feedback</h3><br>
			<img class="feedback"src="img/feedback.jpg">
			
		</div>

		<!--- USERS FEEDBACK OVER--->
	

	<div class="categorias">
		<h2 class="cat">Categorias</h2>
		<a class="lik" href="animal"><img class="img2" src="organs.png">organs</a>
		<a class="lik"href="sport"><img class="img2" src="sport.png">sport</a>	
		<a class="lik"href="fashion"><img class="img2" src="fashion.png">fashion</a>
		<a class="lik"href="home"><img class="img2" src="home.png">home</a>
		<a class="lik"href="gadgets"><img class="img2" src="gadgets.png">gadgets</a>
		<a class="lik"href="gun"><img class="img2" src="gun.png">guns</a>
		<a class="lik"href="farming"><img class="img2" src="garden.png">farming</a>
		<a class="lik"href="cars"><img class="img2"src="car.png">cars</a>
	</div>
	
	<div class="categorias">
		<h2 class="cat">Destaques</h2>
		<a class="lik" href="gun"><img class="img1"src="img/gun1.JPG">Desactivated Ak47</a>
		<a class="lik"href="r2d2"><img class="img1"src="img/r2d2.jpg">Perfect R2D2!</a>	
		<a class="lik"href="iphone"><img class="img1"src="img/iphone.jpg">Stolen IPhone</a>
		<a class="lik"href="outro"><img class="img1"src="img/turtle.jpg">Rare Turtle!</a>
		<a class="lik"href="outro"><img class="img1"src="img/lsd.jpg">Lsd</a>
		<a class="lik"href="outro"><img class="img1" src="img/pills.jpg">Lsd pills</a>
		<a class="lik" href="outro"><img class="img1" src="img/g3.jpg">G3 new</a>
		<a class="lik"href="outro"><img class="img1" src="img/monkey.jpg">Exotic Monkeys</a>	
		<a class="lik"href="outro"><img class="img1"src="img/giraffe.JPG">Wild giraffe</a>
		
		
	</div>


<!--	<div class="sell">
	<div class="photo"><img src="img/gun1.JPG"><br>Desactivated Ak47</div>
	<div class="photo"><img src="img/gun1.JPG"><br>Desactivated Ak47</div>
	<div class="photo"><img src="img/iphone.jpg"><br>Stolen IPhone</div>
	</div> -->
	
	<div class="popular">
		Popular searches: 
		<a class="lik"href="sport">sport;</a>
		<a class="lik"href="fashion"> fashion;</a>
		<a class="lik"href="home"></a>
		<a class="lik"href="gadgets"> gadgets;</a>
		<a class="lik"href="babies"> babies;</a>
		<a class="lik"href="farming"> farming;</a>
		<a class="lik"href="cars"> cars.</a>
	</div>
<a href='search.php?tipo=name'><button>Product search</button></a>
</body>

</html>

