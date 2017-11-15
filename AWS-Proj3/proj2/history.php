<?php
	ini_set("display_errors", "on");
	$user = intval($_GET['user']);
        include "openconn.php";
        global $conn;

            $sql="select distinct p.name,p.end_date,p.valor,u.nick,p.id_product from licitations lic, products p, utilizadores u where lic.id_user='$user' and p.id_product=lic.id_product and u.user_id in (select u.user_id from utilizadores u, licitations l where l.id_user=u.user_id and l.valor=(select max(valor) from licitations where id_product=p.id_product)) order by p.end_date";
            $result = mysqli_query($conn,$sql);
            $num_rows = mysqli_num_rows($result);
            while($row = mysqli_fetch_array($result)) {
                if (strtotime($row[1])<strtotime(date("Y-m-d"))){
                    echo "<a href='item.php?item_id=". $row[4] . "'><div class='row margin-0'>
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
                            </div></a>";
                
                }
            }

        mysqli_close($conn);
?>