<?php
require "class._database.php";
$db=new _database();
$conn = $db->connect();

if (isset($_REQUEST['save']))
{
	echo "saved";
	$oid=$_POST["orderid"];
	$ids=$_POST["itemids"];
	$prices=$_POST["itemprice"];
	$qtys=$_POST["itemqty"];
	mysqli_query($conn, "delete from orderitem where orderid=".$oid);
	while ($ids != "") {
		$tmpid=substr($ids,0,strpos($ids,","));
		$ids=substr($ids,strpos($ids,",")+1);
		
		$tmpprice=substr($prices,0,strpos($prices,","));
		$prices=substr($prices,strpos($prices,",")+1);
		
		$tmpqty=substr($qtys,0,strpos($qtys,","));
		$qtys=substr($qtys,strpos($qtys,",")+1);
		
		$result3=mysqli_query($conn, "select max(OrderItemId) from orderitem");
						$row3 = mysqli_fetch_assoc($result3);
						$oiid=$row3["max(OrderItemId)"];
						$oiid+=1;
		
		$result1=mysqli_query($conn, "Insert into orderitem (OrderItemId,OrderId,Item_Id,Quantity,Price) values(" .$oiid.",".$oid.",".$tmpid.",".$tmpqty.",'".$tmpprice."')");
		
		header("location: /editorders.php?id=".$oid);
		
	
	}
	
}

?>