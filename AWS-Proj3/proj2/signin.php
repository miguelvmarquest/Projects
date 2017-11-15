<?php
session_save_path('/tmp');
include "openconn.php";
global $conn;
session_start();
if (count($_SESSION) == 0){
	$signin = "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group' id='getUser'><input type='text' name='username' placeholder='Username or Email' required id='username'></div><div class='form-group' id='getPass'><input type='password' name='password' placeholder='Password' required id='passsword'></div>
	<div class='form-group' id='logIn'><input class='btn btn-default' type='submit' name='submit' id='submit' value='Log In'></div></form>";
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
	$signin = "<form class='navbar-form navbar-right' action='index.php' method='post'><div class='form-group'>Ola ". $_SESSION["username"] . "</div><div style='margin-left:10px' class='form-group' id='logOut'><input class='btn btn-default' type='submit' name='logout' id='logout' value='Log Out'></div></form><form class='navbar-form navbar-right' action='form1.php' method='post'><div class='form-group' id='signIn'><input class='btn btn-default' type='submit' name='newProduct' id='newProduct' value='New Product'></div></form>";
	if (isset($_POST["logout"])){
		session_unset();
		header('Location: index.php');
	}
}
?>


<html>
<title>B Auction</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="css/theme.css">
<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<?php
include "basecheck.php";
$target_path = "users/";
$uploadOK = 1;
$today = date("Y-m-d");
global $conn;
if (isset($_POST["send"])){
	$username = htmlspecialchars($_POST["user"]);
	$password = md5(htmlspecialchars($_POST["pass"]));
	$confirm_password = md5(htmlspecialchars($_POST["confirm-password"]));
	$email = htmlspecialchars($_POST["email"]);
	$first_name = htmlspecialchars($_POST["first-name"]);
	$last_name = htmlspecialchars($_POST["last-name"]);
	$day = $_POST["day"];
	$month = $_POST["month"];
	$year = $_POST["year"];
	$date = BirthdayCheck($day, $month, $year);
	$birthday = $year."-".$month."-".$day." 00:00:00";
	$sex = $_POST["gender"];
	$country = $_POST["country"];
	if ($country == "PT"){
		$council= htmlspecialchars($_POST["council"]);
		$district = htmlspecialchars($_POST["district"]);	
	}
	else{
		$council= None;
		$district = None;
	}
	if (! $_POST["pass"]){
		$errPassword = "Não inseriu uma password";
	}
	if (! $_POST["confirm-password"]){
		$errConfirmPassword = "Não inseriu a confirmação da password";
	}
	if (! PasswordChecker($password, $confirm_password)){
		$errConfirmPassword = "As passwords não são iguais";
	}
	if (! $_POST["user"]){
		$errUsername = "Não inseriu um username.";
	}
	if (! UsernameCheck($username)){
		$errUsername = "Não inseriu um username valido.";
	}
	if(! $_POST["first-name"]){
		$errNome = "Não inseriu um nome";
	}
	if(! $_POST["last-name"]){
		$errApelido = "Não inseriu um apelido";
	}
	if(! $_POST["email"]){
		$errEmail = "Não inseriu um email";
	}
	if(! EmailCheck($_POST["email"])){
		$errEmail = "Email já existe";
	}
	if ($_POST["email"] && ! filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errEmail = "Não inseriu um email valido";
	}
	if (! BirthdayCheck($day, $month, $year)){
		$errData = "Data de nascimento não valida";
	}
	if ($_POST["country"] == "PT" && ! $_POST["council"]){
		$errConcelho = "Não inseriu um concelho";
	}
	if($_POST["country"] == "PT" && ! $_POST["district"]){
		$errDistrito = "Não inseriu um distrito";
	}
	$check = getimagesize($_FILES["foto"]["tmp_name"]);
	if ($_FILES["foto"]["name"]){
	    if($check == false) {
	        $errFoto = "Ficheiro não e uma imagem";
	        $uploadOk = 0;
	    }
	    if ($_FILES["foto"]["size"] > 50000000000) {
	    	$errFoto = "Ficheiro grande demais.";
	    	$uploadOk = 0;	
		}

		if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $_FILES["foto"]["type"]) ) {
		    $errFoto = "Apenas .jpg, .png, .jpeg, .gif são permitidos.";
		    $uploadOk = 0;
		}
		
	}
	if (!$errNome && !$errUsername && !$errUsername && !$errApelido && !$errEmail && !$errConcelho && !$errDistrito && !$errPassword && !$errConfirmPassword && !$errFoto && !$errData){
		preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $_FILES["foto"]['name'], $ext);
		$target_file = $target_path . md5(uniqid(time())) . "." . $ext[1];
		if (! empty($_FILES["foto"]["name"]) && move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
	        global $conn;
			$query = "insert into utilizadores (nick, pass, nome, apelido, email, sexo, data_nascimento, pais, concelho, distrito, foto, data_reg) values('$username', '$password', '$first_name', '$last_name', '$email', '$sex', '$birthday
			','$country', '$council', '$district', '$target_file', '$today')";
			$res = mysqli_query($conn, $query);
			if(!$res){
				die("Erro de insercao na base de dados: ".$query);
			}
			mysqli_close($conn);
			echo "<script>$(document).ready(function(){
				$('.form-unit').children().css('display', 'none');
				$('.form-unit').append('<div class=\"row\"><div class=\"mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2\"><div class=\"alert alert-success\" role=\"alert\">You are now signed up! In five seconds you will be redirected to the home page!</div></div></div>');
				setTimeout(function(){window.location=\"index.php\";}, 5000);
			})</script>";
			#newSignEmail($email, $password); aqui estaria o codigo para enviar o email de confirmacao.
	    } else if (empty($_FILES["foto"]["name"])) {
	    	global $conn;
			$query = "insert into utilizadores (nick, pass, nome, apelido, email, sexo, data_nascimento, pais, concelho, distrito, data_reg) values('$username', '$password', '$first_name', '$last_name', '$email', '$sex', '$birthday
			','$country', '$council', '$district', '$today')";
			$res = mysqli_query($conn, $query);
			if(!$res){
				die("Erro de insercao na base de dados: ".$query);
			}
			mysqli_close($conn);
			echo "<script>$(document).ready(function(){
				$('.form-unit').children().css('display', 'none');
				$('.form-unit').append('<div class=\"row\"><div class=\"mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2\"><div class=\"alert alert-success\" role=\"alert\">You are now signed up! In five seconds you will be redirected to the home page!</div></div></div>');
				setTimeout(function(){window.location=\"index.php\";}, 5000);
			})</script>";
			#newSignEmail($email, $password)

		}else {
	        $errFoto ="Exitiu um problema a enviar o ficheiro.";
	    }
	}


}
?>
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
    <div class="page-header">
    	<div class="row">
    		<div class="col-lg-6">
    			<h1 class="sign-up-header">Welcome to B Auction!</h1>
    			<small class="sign-up-text">He are delighted to have shop in our site! <br> Please enter your details below to be signed up with our site.</small>
    		</div>
    	</div>
    </div>
    <div class="row form-unit">
    	<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    		<div class="row list-group">
    			<div class="col-xs-12 list-group-item ">
    				<form action="signin.php" method="post" enctype="multipart/form-data">
    					<div style="margin-bottom: 2%" class="form-group">
    						<label>Username</label>
    						<div class="input-group">
	    						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	    						<input type="text" class="form-control" name="user" id='username' placeholder="Username"/>
	    						<?php echo "<p class='text-danger'>$errUsername</p>";?>
	    					</div>
    					</div>
    					<div style="margin-bottom: 2%" class="form-group">
    						<label>Email</label>
    						<div class="input-group">
	    						<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
	    						<input type="text" class="form-control" name="email" id='email' placeholder="Email"/>
	    						<?php echo "<p class='text-danger'>$errEmail</p>";?>
    						</div>
    					</div>
    					<div style="margin-bottom: 2%" class="form-group">
    						<label>First Name</label>
    						<div class="input-group">
	    						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	    						<input type="text" class="form-control" name="first-name" id='first-name' placeholder="First Name"/>
	    						<?php echo "<p class='text-danger'>$errNome</p>";?>
	    					</div>
    					</div>
    					<div style="margin-bottom: 2%" class="form-group">
    						<label>Last Name</label>
    						<div class="input-group">
	    						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	    						<input type="text" class="form-control" name="last-name" id='last-name' placeholder="Last Name"/>
	    						<?php echo "<p class='text-danger'>$errApelido</p>";?>
	    					</div>
    					</div>
    					<div style="margin-bottom: 2%" class="form-group">
    						<label>Password</label>
    						<div class="input-group">
	    						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	    						<input type="password" class="form-control" name="pass" id='password' placeholder="Password"/>
	    						<?php echo "<p class='text-danger'>$errPassword</p>";?>
	    					</div>
    					</div>
    					<div style="margin-bottom: 2%" class="form-group">
    						<label>Confirm Password</label>
    						<div class="input-group">
	    						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	    						<input type="password" class="form-control" name="confirm-password" id='confirm-password' placeholder="Confirm Password"/>
	    						<?php echo "<p class='text-danger'>$errConfirmPassword</p>";?>
	    					</div>
    					</div>
    					<div style="margin-bottom: 2%" class="input-group">
    						<label>Country</label>
    						<div class="input-group">
	    						<span class="input-group-addon"><i class="glyphicon glyphicon-flag"></i></span>
	    						<select id="country" name="country" class="form-control">
							      <option value="PT">Portugal</option>
							      <option value="AF">Afeganist&atilde;o</option><option value="ZA">&Aacute;frica do Sul</option><option value="AL">Alb&acirc;nia</option><option value="DE">Alemanha</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguila</option><option value="AQ">Ant&aacute;rtida</option><option value="AG">Ant&iacute;gua e Barbuda</option><option value="SA">Ar&aacute;bia Saudita</option><option value="DZ">Arg&eacute;lia</option><option value="AR">Argentina</option><option value="AM">Arm&eacute;nia</option><option value="AW">Aruba</option><option value="AU">Austr&aacute;lia</option><option value="AT">&Aacute;ustria</option><option value="AZ">Azerbaij&atilde;o</option><option value="BS">Bahamas</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BH">Bar&eacute;m</option><option value="BE">B&eacute;lgica</option><option value="BZ">Belize</option><option value="BJ">Benim</option><option value="BM">Bermudas</option><option value="BY">Bielorr&uacute;ssia</option><option value="BO">Bol&iacute;via</option><option value="BA">B&oacute;snia e Herzegovina</option><option value="BW">Botswana</option><option value="BR">Brasil</option><option value="BN">Brunei</option><option value="BG">Bulg&aacute;ria</option><option value="BF">Burquina Faso</option><option value="BI">Burundi</option><option value="BT">But&atilde;o</option><option value="CV">Cabo Verde</option><option value="CM">Camar&otilde;es</option><option value="KH">Camboja</option><option value="CA">Canad&aacute;</option><option value="QA">Catar</option><option value="KZ">Cazaquist&atilde;o</option><option value="EA">Ceuta e Melilha</option><option value="TD">Chade</option><option value="CL">Chile</option><option value="CN">China</option><option value="CY">Chipre</option><option value="VA">Cidade do Vaticano</option><option value="CO">Col&ocirc;mbia</option><option value="KM">Comores</option><option value="CG">Congo-Brazzaville</option><option value="CD">Congo-Kinshasa</option><option value="KP">Coreia do Norte</option><option value="KR">Coreia do Sul</option><option value="CI">Costa do Marfim</option><option value="CR">Costa Rica</option><option value="HR">Cro&aacute;cia</option><option value="CU">Cuba</option><option value="CW">Cura&ccedil;au</option><option value="DG">Diego Garcia</option><option value="DK">Dinamarca</option><option value="DM">Dom&iacute;nica</option><option value="EG">Egipto</option><option value="SV">El Salvador</option><option value="AE">Emirados &Aacute;rabes Unidos</option><option value="EC">Equador</option><option value="ER">Eritreia</option><option value="SK">Eslov&aacute;quia</option><option value="SI">Eslov&eacute;nia</option><option value="ES">Espanha</option><option value="US">Estados Unidos</option><option value="EE">Est&oacute;nia</option><option value="ET">Eti&oacute;pia</option><option value="FJ">Fiji</option><option value="PH">Filipinas</option><option value="FI">Finl&acirc;ndia</option><option value="FR">Fran&ccedil;a</option><option value="GA">Gab&atilde;o</option><option value="GM">G&acirc;mbia</option><option value="GH">Gana</option><option value="GE">Ge&oacute;rgia</option><option value="GI">Gibraltar</option><option value="GD">Granada</option><option value="GR">Gr&eacute;cia</option><option value="GL">Gronel&acirc;ndia</option><option value="GP">Guadalupe</option><option value="GU">Guame</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GY">Guiana</option><option value="GF">Guiana Francesa</option><option value="GN">Guin&eacute;</option><option value="GQ">Guin&eacute; Equatorial</option><option value="GW">Guin&eacute;-Bissau</option><option value="HT">Haiti</option><option value="HN">Honduras</option><option value="HK">Hong Kong, RAE da China</option><option value="HU">Hungria</option><option value="YE">I&eacute;men</option><option value="AC">Ilha de Ascens&atilde;o</option><option value="IM">Ilha de Man</option><option value="CX">Ilha do Natal</option><option value="NF">Ilha Norfolk</option><option value="AX">Ilhas &Aring;land</option><option value="KY">Ilhas Caim&atilde;o</option><option value="IC">Ilhas Can&aacute;rias</option><option value="CC">Ilhas Cocos</option><option value="CK">Ilhas Cook</option><option value="UM">Ilhas Distantes dos EUA</option><option value="FK">Ilhas Falkland</option><option value="FO">Ilhas Faro&eacute;</option><option value="GS">Ilhas Ge&oacute;rgia do Sul e Sandwich do Sul</option><option value="MP">Ilhas Marianas do Norte</option><option value="MH">Ilhas Marshall</option><option value="SB">Ilhas Salom&atilde;o</option><option value="TC">Ilhas Turcas e Caicos</option><option value="VG">Ilhas Virgens Brit&acirc;nicas</option><option value="VI">Ilhas Virgens dos EUA</option><option value="IN">&Iacute;ndia</option><option value="ID">Indon&eacute;sia</option><option value="IR">Ir&atilde;o</option><option value="IQ">Iraque</option><option value="IE">Irlanda</option><option value="IS">Isl&acirc;ndia</option><option value="IL">Israel</option><option value="IT">It&aacute;lia</option><option value="JM">Jamaica</option><option value="JP">Jap&atilde;o</option><option value="JE">Jersey</option><option value="DJ">Jibuti</option><option value="JO">Jord&acirc;nia</option><option value="XK">Kosovo</option><option value="KW">Kuwait</option><option value="LA">Laos</option><option value="LS">Lesoto</option><option value="LV">Let&oacute;nia</option><option value="LB">L&iacute;bano</option><option value="LR">Lib&eacute;ria</option><option value="LY">L&iacute;bia</option><option value="LI">Liechtenstein</option><option value="LT">Litu&acirc;nia</option><option value="LU">Luxemburgo</option><option value="MO">Macau, RAE da China</option><option value="MK">Maced&oacute;nia</option><option value="MG">Madag&aacute;scar</option><option value="YT">Maiote</option><option value="MY">Mal&aacute;sia</option><option value="MW">Malawi</option><option value="MV">Maldivas</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MA">Marrocos</option><option value="MQ">Martinica</option><option value="MU">Maur&iacute;cia</option><option value="MR">Maurit&acirc;nia</option><option value="MX">M&eacute;xico</option><option value="MM">Mianmar (Birm&acirc;nia)</option><option value="FM">Micron&eacute;sia</option><option value="MZ">Mo&ccedil;ambique</option><option value="MD">Mold&aacute;via</option><option value="MC">M&oacute;naco</option><option value="MN">Mong&oacute;lia</option><option value="MS">Monserrate</option><option value="ME">Montenegro</option><option value="NA">Nam&iacute;bia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NI">Nicar&aacute;gua</option><option value="NE">N&iacute;ger</option><option value="NG">Nig&eacute;ria</option><option value="NU">Niue</option><option value="NO">Noruega</option><option value="NC">Nova Caled&oacute;nia</option><option value="NZ">Nova Zel&acirc;ndia</option><option value="OM">Om&atilde;</option><option value="NL">Pa&iacute;ses Baixos</option><option value="BQ">Pa&iacute;ses Baixos Caribenhos</option><option value="PW">Palau</option><option value="PA">Panam&aacute;</option><option value="PG">Papua-Nova Guin&eacute;</option><option value="PK">Paquist&atilde;o</option><option value="PY">Paraguai</option><option value="PE">Peru</option><option value="PN">Pitcairn</option><option value="PF">Polin&eacute;sia Francesa</option><option value="PL">Pol&oacute;nia</option><option value="PR">Porto Rico</option><option value="KE">Qu&eacute;nia</option><option value="KG">Quirguist&atilde;o</option><option value="KI">Quiribati</option><option value="GB">Reino Unido</option><option value="CF">Rep&uacute;blica Centro-Africana</option><option value="CZ">Rep&uacute;blica Checa</option><option value="DO">Rep&uacute;blica Dominicana</option><option value="RE">Reuni&atilde;o</option><option value="RO">Rom&eacute;nia</option><option value="RW">Ruanda</option><option value="RU">R&uacute;ssia</option><option value="EH">Saara Ocidental</option><option value="PM">Saint Pierre e Miquelon</option><option value="WS">Samoa</option><option value="AS">Samoa Americana</option><option value="SH">Santa Helena</option><option value="LC">Santa L&uacute;cia</option><option value="BL">S&atilde;o Bartolomeu</option><option value="KN">S&atilde;o Crist&oacute;v&atilde;o e Nevis</option><option value="SM">S&atilde;o Marino</option><option value="MF">S&atilde;o Martinho</option><option value="ST">S&atilde;o Tom&eacute; e Pr&iacute;ncipe</option><option value="VC">S&atilde;o Vicente e Granadinas</option><option value="SC">Seicheles</option><option value="SN">Senegal</option><option value="SL">Serra Leoa</option><option value="RS">S&eacute;rvia</option><option value="SG">Singapura</option><option value="SX">Sint Maarten</option><option value="SY">S&iacute;ria</option><option value="SO">Som&aacute;lia</option><option value="LK">Sri Lanka</option><option value="SZ">Suazil&acirc;ndia</option><option value="SD">Sud&atilde;o</option><option value="SS">Sud&atilde;o do Sul</option><option value="SE">Su&eacute;cia</option><option value="CH">Su&iacute;&ccedil;a</option><option value="SR">Suriname</option><option value="SJ">Svalbard e Jan Mayen</option><option value="TH">Tail&acirc;ndia</option><option value="TW">Taiwan</option><option value="TJ">Tajiquist&atilde;o</option><option value="TZ">Tanz&acirc;nia</option><option value="IO">Territ&oacute;rio Brit&acirc;nico do Oceano &Iacute;ndico</option><option value="PS">Territ&oacute;rio Palestiniano</option><option value="TF">Territ&oacute;rios Franceses do Sul</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TO">Tonga</option><option value="TK">Toquelau</option><option value="TT">Trindade e Tobago</option><option value="TA">Trist&atilde;o da Cunha</option><option value="TN">Tun&iacute;sia</option><option value="TM">Turquemenist&atilde;o</option><option value="TR">Turquia</option><option value="TV">Tuvalu</option><option value="UA">Ucr&acirc;nia</option><option value="UG">Uganda</option><option value="UY">Uruguai</option><option value="UZ">Uzbequist&atilde;o</option><option value="VU">Vanuatu</option><option value="VE">Venezuela</option><option value="VN">Vietname</option><option value="WF">Wallis e Futuna</option><option value="ZM">Z&acirc;mbia</option><option value="ZW">Zimbabu&eacute;</option>
							    </select>
	    					</div>
    					</div>
    					<div style="margin-bottom: 2%" class="form-group" id="visible">
							<div class="row">
								<div class="col-xs-6 col-md-6">
										<label>District</label>
	    								<input type="text" class="form-control" name="district" id='district' placeholder="District"/>
	    								<?php echo "<p class='text-danger'>$errDistrito</p>";?>
									</div>
								<div class="col-xs-6 col-md-6">
										<label>Council</label>
    									<input type="text" class="form-control" name="council" id='council' placeholder="Council"/>
    									<?php echo "<p class='text-danger'>$errConcelho</p>";?>
								</div>
							</div>
						</div>
						<div style="margin-bottom: 2%" class="form-group">
							<label>Birthday</label>
							<div class="row">
								<div class="col-xs-4 col-md-4">
									<label>Day</label>
									<select name="day" class="form-control">
										<?php for($i=1; $i<=31; $i++){
												echo "<option value='".sprintf("%02d", $i)."'>$i</option>";
											};	
											?>
									</select>
								</div>
								<div class="col-xs-4 col-md-4">
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
						<div style="margin-bottom: 2%" class="input-group">
    						<label>Gender</label>
    						<div class="input-group">
	    						<select id="gender" name="gender" class="form-control">
							      <option value="Male">Male</option>
							      <option value="Female">Female</option>
							    </select>
	    					</div>
    					</div>
				    	<div style="margin-bottom: 2%"  class="form-group">
				    		<label>Profile Picture</label>
				    		<div class="row">
							    <div class="col-sm-2">
					    			<input id="foto" name="foto" type="file" class="file">
					    		</div>
				    		</div>
				    	</div>
				    	<div class="form-group">
				    		<div class="row">
							<div class="col-sm-6">
								<input id="submit" name="send" type="submit" value="Send" class="btn btn-primary">
								<?php echo "<p class='text-danger'>$errFoto</p>";?>
							</div>
						</div>
						</div>
					</form>
    			</div>
    		</div>
    	</div>
    </div>

<script>
	
$(document).ready(function($) {
$("#country").bind('change', function(e){
	if ($("#country").val() == "PT"){
		$("#visible").css("display", 'block');
	}
	else {
		$("#visible").css("display", 'none');
	}
}).trigger('change');

$('#radioBtn a').on('click', function(){
    var sel = $(this).data('title');
    var tog = $(this).data('toggle');
    $('#'+tog).prop('value', sel);
    
    $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
    $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
})
});
</script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>