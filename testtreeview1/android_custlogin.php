<?php
require "class._database.php";
$db=new _database();
$conn = $db->connect();


if (isset($_GET['login']))
{
	$login = $_GET['login'];
	$password = $_GET['password'];
	
	$result = mysqli_query($conn, "Select userdetailid from userdetail where email='".$login."' and password='".$password."' and isAdmin=0");
	if ($row = mysqli_fetch_assoc($result))
	{
		$result2=mysqli_query($conn, "select max(userlogid) from restaurantpos.userlog;");
		$row2 = mysqli_fetch_assoc($result2);
		$id=$row2["max(userlogid)"];
		$id=$id+1;

		mysqli_query($conn, "INSERT INTO `userlog`(`UserLogId`, `UserDetailId`, `Action`) VALUES (".$id.",".$row['userdetailid'].",'Login')");
		echo $row['userdetailid'];
	}
	else
	{
		echo "invalid";
	}
}
?>