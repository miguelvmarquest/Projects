<?php
$ids=$_GET["item_id"];

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
$user = intval($_SESSION["user_id"]);

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
<head>
<script>
function showUser(user) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET","listint.php?user="+user,true);
        xmlhttp.send();
}
    
function interval(){ 
    setInterval(function(){ showUser(<?php echo $user;?>);}, 10000);
}
                
</script>
<style>
/* Methods */
.method .header, .method .cell {
  padding: 6px 6px 6px 10px; }
.method .list-header .header {
  font-weight: normal;
  text-transform: uppercase;
  font-size: 0.8em;
  color: #999;
  background-color: #eee; }
.method [class^="row"],
.method [class*=" row"] {
  border-bottom: 1px solid #ddd; }
  .method [class^="row"]:hover,
  .method [class*=" row"]:hover {
    background-color: #f7f7f7; }
.method .cell {
  font-size: 0.85em; }
  .method .cell .mobile-isrequired {
    display: none;
    font-weight: normal;
    text-transform: uppercase;
    color: #aaa;
    font-size: 0.8em; }
  .method .cell .propertyname {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; }
  .method .cell .type {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; }
  .method .cell code {
    color: #428bca; }
  .method .cell a, .method .cell a:hover {
    text-decoration: none; }
  .method .cell code.custom {
    color: #8a6d3b;
    text-decoration: none; }
  .method .cell .text-muted {
    color: #ddd; }
@media (max-width: 991px) {
  .method [class^="row"],
  .method [class*=" row"] {
    padding-top: 10px;
    padding-bottom: 10px; }
  .method .cell {
    padding: 0 10px; }
    .method .cell .propertyname {
      font-weight: bold;
      font-size: 1.2em; }
      .method .cell .propertyname .lookuplink {
        font-weight: normal;
        font-size: 1.5em;
        position: absolute;
        top: 0;
        right: 10px; }
    .method .cell .type {
      padding-left: 10px;
      font-size: 1.1em; }
    .method .cell .isrequired {
      padding-left: 10px;
      display: none; }
    .method .cell .description {
      padding-left: 10px; }
    .method .cell .mobile-isrequired {
      display: inline; } }


/* Row Utilities */
[class^='row'].margin-0,
[class*=' row'].margin-0,
[class^='form-group'].margin-0,
[class*=' form-group'].margin-0 {
  margin-left: -0px;
  margin-right: -0px; }
  [class^='row'].margin-0 > [class^='col-'],
  [class^='row'].margin-0 > [class*=' col-'],
  [class*=' row'].margin-0 > [class^='col-'],
  [class*=' row'].margin-0 > [class*=' col-'],
  [class^='form-group'].margin-0 > [class^='col-'],
  [class^='form-group'].margin-0 > [class*=' col-'],
  [class*=' form-group'].margin-0 > [class^='col-'],
  [class*=' form-group'].margin-0 > [class*=' col-'] {
    padding-right: 0px;
    padding-left: 0px; }
  [class^='row'].margin-0 [class^='row'],
  [class^='row'].margin-0 [class*=' row'],
  [class^='row'].margin-0 [class^='form-group'],
  [class^='row'].margin-0 [class*=' form-group'],
  [class*=' row'].margin-0 [class^='row'],
  [class*=' row'].margin-0 [class*=' row'],
  [class*=' row'].margin-0 [class^='form-group'],
  [class*=' row'].margin-0 [class*=' form-group'],
  [class^='form-group'].margin-0 [class^='row'],
  [class^='form-group'].margin-0 [class*=' row'],
  [class^='form-group'].margin-0 [class^='form-group'],
  [class^='form-group'].margin-0 [class*=' form-group'],
  [class*=' form-group'].margin-0 [class^='row'],
  [class*=' form-group'].margin-0 [class*=' row'],
  [class*=' form-group'].margin-0 [class^='form-group'],
  [class*=' form-group'].margin-0 [class*=' form-group'] {
    margin-left: 0;
    margin-right: 0; }
      

</style>

</head>
<body onLoad="interval()">
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
<?php
        
        include "openconn.php";
        global $conn;


            $sql="select distinct p.name,p.end_date,p.valor,u.nick,p.id_product from licitations lic, products p, utilizadores u where lic.id_user='$user' and p.id_product=lic.id_product and u.user_id in (select u.user_id from utilizadores u, licitations l where l.id_user=u.user_id and l.valor=(select max(valor) from licitations where id_product=p.id_product)) order by p.end_date";
            $result = mysqli_query($conn,$sql);
            $num_rows = mysqli_num_rows($result);
            $array=array();
            while($row = mysqli_fetch_array($result)) {
                if (strtotime($row[1])>=strtotime(date("Y-m-d"))){
                    array_push($array,"<a href='item.php?item_id=". $row[4] . "'><div class='row margin-0'>
                                <div class='col-md-3'>
                                    <div class='cell'>
                                        <div class='propertyname'>
                                            " . $row[0] . "
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class='cell'>
                                        <div class='type'>
                                            <code>" . date("d-m-Y", strtotime($row[1])) . "</code>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class='cell'>
                                        <div class='isrequired'>
                                            " . $row[2] . "
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-5'>
                                    <div class='cell'>
                                        <div class='description'>
                                            " . $row[3] . "
                                        </div>
                                    </div>
                                </div>
                            </div></a>");
                
                }
            }

        mysqli_close($conn);
        ?>


<div class="container">

    <h1>My bids on the run....</h1>
    <p class="lead">
        Click on a product and make your bid 
    </p>
    
    

    <div class="method">
        <div class="row margin-0 list-header hidden-sm hidden-xs">
            <div class="col-md-3"><div class="header">Name</div></div>
            <div class="col-md-2"><div class="header">End Date</div></div>
            <div class="col-md-2"><div class="header">Best Value</div></div>
            <div class="col-md-5"><div class="header">Best Value User</div></div>
        </div>
        <font id="txtHint"><?php foreach ($array as $value){echo $value; } ?></font>
    </div>
</div>

<script>

</script>
</body>
</html>