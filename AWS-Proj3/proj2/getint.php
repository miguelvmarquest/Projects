<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
$q = doubleval($_GET['q']);
$id = intval($_GET['id']);
$user = intval($_GET['user']);
include "openconn.php";
global $conn;


$sql="SELECT valor FROM products WHERE id_product= '".$id."'";
$result = mysqli_query($conn,$sql);
$num_rows = mysqli_num_rows($result);
if ($num_rows == 0){
    echo "licitacao incorrecta(necessita de fazer um a licitacao superior)";
}
else{ 
    while($row = mysqli_fetch_array($result)) {
        $maior=$row['valor'];
    }
    if ($maior>=$q ){
        echo $maior;
        
        
    }
    else{
        
        $sql= "UPDATE products SET valor='$q' WHERE id_product= $id";
        $res = mysqli_query($conn, $sql); 
		if(!$res){
			die("Error, insert query failed:".$sql);
		}
        mysqli_close($conn);
        include "openconn.php";
        global $conn;
        $query = "insert into licitations (id_user, id_product, valor) values('$user', $id, '$q')";
        $res = mysqli_query($conn, $query);
        if(!$res){
            die("Error inserting into database: ".$query);
        }
        echo $q;
    }
}
mysqli_close($conn);
?>
</body>
</html>