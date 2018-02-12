<?php

require "class._database.php";

$db=new _database();
$conn = $db->connect();
session_start();
if (isset($_REQUEST['orderitemid']))
{
	$date = date("Y-m-d H:i:s");
	$orderitemid=$_REQUEST["orderitemid"];
	
	$qry = "update `order` set status=9 where orderid=(Select orderid from orderitem where orderitemid=" . $orderitemid . ")";
	mysqli_query($conn, $qry);
        $qry="delete from orderitem where orderitemid=".$orderitemid;
	mysqli_query($conn, $qry);     
}


?>