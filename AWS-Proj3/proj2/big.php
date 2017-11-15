<?php
	include "openconn.php";
        global $conn;
	$user = $_GET["user_id"];
	$sql="select distinct id_product from licitations where id_user = '$user'";
    $result = $conn->query($sql);
	$bids = array();    	
	while($row = mysqli_fetch_array($result)){
		array_push($bids, $row["id_product"]);
	};
	foreach ($bids as $value){		
		$sql = "SELECT *  FROM `licitations` WHERE id_product = '$value' and notified = 0 and id_user != $user order by bid_date desc limit 1 ";
		$result = $conn ->query($sql);
		$row = mysqli_fetch_assoc($result);
		$rowcount = mysqli_num_rows($result);	
		if ($rowcount != 00000) {
			echo json_encode($row);
			$id_licitacao = $row["id_licitacao"];
			$sql = "update licitations set notified = 1 where id_licitacao = $id_licitacao";
			$conn ->query($sql);
		}
		
	}
	mysqli_close($conn);
        
?>