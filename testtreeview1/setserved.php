<?php

require "class._database.php";

$db=new _database();
$conn = $db->connect();
session_start();
if (isset($_REQUEST['orderitemid']))
{
	$date = date("Y-m-d H:i:s");
	$orderitemid=$_REQUEST["orderitemid"];
	$qry="update orderitem set isserved=1 where orderitemid=".$orderitemid;
	mysqli_query($conn, $qry);
	$qry = "update `order` set ServedTime=".$date ." where orderid=(Select orderid from orderitem where orderitemid=" . $orderitemid .")";
	mysqli_query($conn, $qry);
        
        $ser = 0;
        $result3=mysqli_query($conn, "Select count(*) as served from orderitem where orderid=(Select orderid from orderitem where orderitemid=" . $orderitemid ."). and isserved!=1");
        $row3 = mysqli_fetch_assoc($result3);
        $ser=$row3['served'];
        
        
        if ($ser==0)
        {
                $qry = "update `order` set status=3 where orderid=(Select orderid from orderitem where orderitemid=" . $orderitemid .")";
                mysqli_query($conn, $qry);
        }
}


?>