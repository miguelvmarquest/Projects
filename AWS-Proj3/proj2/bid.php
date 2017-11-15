<?php
	include "openconn.php";
        global $conn;
	$user = $_GET["user_id"];
	$sql="select distinct p.name,p.end_date,p.valor,u.nick,p.id_product from licitations lic, products p, utilizadores u where lic.id_user='$user' and p.id_product=lic.id_product and u.user_id in (select u.user_id from utilizadores u, licitations l where l.id_user=u.user_id and l.valor=(select max(valor) from licitations where id_product=p.id_product)) order by p.end_date";
    	$result = mysqli_query($conn,$sql);
    	$num_rows = mysqli_num_rows($result);
    	$row = mysqli_fetch_array($result);
        if (strtotime($row[1])<strtotime(date("Y-m-d"))){
		echo json_encode($row);
	}
?>