
<?php
require "class._database.php";
$db=new _database();
$conn = $db->connect();


 if (isset($_GET["checkout"]))
{
	$orderid=$_GET['orderid'];
	$qry = "update `order` set status=4 where orderid=" . $orderid;
	mysqli_query($conn, $qry);
	
}
	
	?>