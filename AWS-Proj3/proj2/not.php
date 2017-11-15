<?php
include "openconn.php";
global $conn;

$sql = "select * from products where end_date < CURDATE()";
$result = $conn->query($sql);
while ($row = mysqli_fetch_assoc($result)){
	$id_product = $row["id_product"];
	$s = "select p.id_user,p.name,p.description,p.valor,p.start_date,p.end_date,p.keywords,p.fotos,u.nick,p.id_product from licitations lic, products p, utilizadores u where lic.id_user=u.user_id and p.id_product='$id_product' and p.id_product=lic.id_product and u.user_id in (select u.user_id from utilizadores u, licitations l where l.id_user=u.user_id and l.valor=(select max(valor) from licitations where id_product=p.id_product))";
	$sd = $conn->query($sql);
	while ($ro = mysqli_fetch_assoc($sd)) {
		$id_product = $ro["id_product"]; 
		$id_user = $ro["id_user"]; 
		$user_name = $ro["nick"]; 
		$product_name = $ro["name"]; 
		$valor = $ro["valor"]; 
		$inser = "insert into notifications (id_product, id_user, user_name, product_name, valor) values ($id_product, $id_user, $user_name, $product_name, $valor)";
		$conn->query($sql);
	}
}
?>