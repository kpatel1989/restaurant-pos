<?php
require "class._database.php";
	$db=new _database();
	$conn = $db->connect();
$devicename = $_GET['devicename'];

if ($result=mysqli_query($conn, "Select devicename from device where devicename='".$devicename."'"))
{
	if ($row=mysqli_fetch_assoc($result))
	{	
		echo $row['devicename'];
	}
	else
	{
		echo "unique";
	}
}
else
{
	echo mysqli_error($conn);

}	
?>