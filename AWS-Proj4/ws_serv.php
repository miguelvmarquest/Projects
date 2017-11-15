<?php
require_once "lib/nusoap.php";
require "openconn.php";
//
//$connect = mysql_pconnect("appserver.di.fc.ul.pt","asw47139","mikeTondo95");
//    if ($connect) {
//      if(mysql_select_db("asw47139", $connect)) {
//      $sql = "select valor from products where id_product=$id";
//          $result = mysql_fetch_array(mysql_query($sql));
//          return $result["valor"];
//      }
//  }
//  return false;


function ValorAtualDoItem($id){
	include "openconn.php";
	global $conn;
	$query = "select valor from products where id_product=$id";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
  	$array = mysqli_fetch_assoc($result);
		return $array["valor"];
	} else {
		return "No Item!";
	}
}

function LicitaItem($id, $valor, $username, $password) {
		include "openconn.php";
		global $conn;
    $username = htmlspecialchars($username);
    $password = md5($password);
    $query = "select user_id from utilizadores where (nick = '$username' or email='$username') and pass = '$password'";
    $result = mysqli_query($conn, $query) or die("Error ". $query);
    $num_row = mysqli_num_rows($result);
    if ($num_row > 0){
	    $sql="SELECT valor,end_date starting_date, end_date FROM products WHERE id_product=$id";
	    $result = mysqli_query($conn,$sql);
	    $num_rows = mysqli_num_rows($result);
	    if ($num_rows == 0){
	       return "Não aceite";
	    }
	    else{
				$row = mysqli_fetch_array($result);
            
                if (strtotime(date('Y-m-d'))>strtotime($row['end_date'])){
                    return "Terminado";
                }
				elseif ($row['valor']>=$valor ){
		    	return "Não aceite";
				}
				else{
		    	$sql= "UPDATE products SET valor=$valor WHERE id_product=$id";
		    	$res = mysqli_query($conn, $sql);
		    	if(!$res){
						die("Error, insert query failed:".$sql);
		    	}
		    	$query = "insert into licitations (id_user, id_product, valor) values('$user', $id, '$q')";
		    	$res = mysqli_query($conn, $query);
		    	if(!$res){
						die("Error inserting into database: ".$query);
		    	}
		    	return "Aceite";
				}
			}
		}
    else{
    	return "Não aceite";
    }
    mysqli_close($conn);
}

$server = new soap_server();
$server->configureWSDL('cumpwsdl', 'urn:cumpwsdl');

$server->register("ValorAtualDoItem",  // nome metodo
	array('id' => 'xsd:integer'),      // input
	array('return' => 'xsd:string'),    // output
	'uri:cumpwsdl',                   // namespace
	'urn:cumpwsdl#ValorAtualDoItem',      // SOAPAction
	'rpc',                         // estilo
	'encoded'                      // uso
);

$server->register("LicitaItem",  // nome metodo
	array('id' => 'xsd:integer', 'valor' => 'xsd:double', 'username' => 'xsd:string', 'password' => 'xsd:string'),      // input
	array('return' => 'xsd:string'),    // output
	'uri:cumpwsdl',                   // namespace
	'urn:cumpwsdl#LicitaItem',      // SOAPAction
	'rpc',                         // estilo
	'encoded'                      // uso
);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
