<?php
require "class._database.php";

$db=new _database();
$conn = $db->connect();

if (isset($_REQUEST['deviceid']))
{
        $tableid;
        $seltbl="SELECT * from device where DeviceName='".$_REQUEST['deviceid']."'";
	if ($res = mysqli_query($conn, $seltbl))
	{
		while ($row1 = mysqli_fetch_assoc($res))
		{
			$tableid = $row1['TableId'];
		}
		
	}
        
	
	$qry="SELECT o.orderid,o.Quantity,i.item_id,i.item_name,o.isServed,o.Price FROM OrderItem o LEFT JOIN items i ON o.item_id = i.item_id  where OrderId=(select OrderId from `order` where TableId=".$tableid." and status!=5)";
	if ($result = mysqli_query($conn, $qry))
	{
		while ($row = mysqli_fetch_assoc($result))
		{
			$qty = $row['Quantity'];
			$itemname = $row['item_name'];
            $isserved=$row['isServed'];
            $orderid=$row['orderid']; 
            $price=$row['Price'];
			echo $orderid.",".$itemname.",".$qty.",".$price."+";
			
		}
		
	}
	else
	{
		echo mysqli_error($conn);
	}
}
?>