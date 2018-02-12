<?php
require "class._database.php";

$db=new _database();
$conn = $db->connect();
if (isset($_REQUEST['tableid']))
{
	$tableid = $_REQUEST['tableid'];
	$qry="SELECT o.orderid,o.Quantity,i.item_id,i.item_name,o.isServed,o.price FROM OrderItem o LEFT JOIN items i ON o.item_id = i.item_id  where OrderId=(select OrderId from `order` where TableId=".$tableid." and status<4)";
	if ($result = mysqli_query($conn, $qry))
	{
		while ($row = mysqli_fetch_assoc($result))
		{
			$qty = $row['Quantity'];
			$itemname = $row['item_name'];
            $isserved=$row['isServed'];
            $orderid=$row['orderid'];
			$price=$row['price'];			
			echo $orderid.",".$itemname.",".$qty.",".$price."+";
			
		}
		
	}
	else
	{
		echo mysqli_error($conn);
	}
}
else if (isset($_REQUEST['devicename']))
{
	$devicename=$_REQUEST['devicename'];
	$result = mysqli_query($conn, "Select tableid from device where devicename='". $devicename."'");
	$row = mysqli_fetch_assoc($result);
	$tableid = $row['tableid'];
	$qry="SELECT o.orderid,o.Quantity,i.item_id,i.item_name,o.isServed,o.price FROM OrderItem o LEFT JOIN items i ON o.item_id = i.item_id  where OrderId=(select OrderId from `order` where TableId=".$tableid." and status<4)";
	if ($result = mysqli_query($conn, $qry))
	{
		while ($row = mysqli_fetch_assoc($result))
		{
			$qty = $row['Quantity'];
			$itemname = $row['item_name'];
            $isserved=$row['isServed'];
            $orderid=$row['orderid'];    
			$price=$row['price'];
			echo $orderid.",".$itemname.",".$qty.",".$price."+";
			
		}
		
	}
	else
	{
		echo mysqli_error($conn);
	}
}
?>