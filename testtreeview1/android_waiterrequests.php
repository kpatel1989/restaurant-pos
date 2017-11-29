<?php
require "class._database.php";
$db=new _database();
$conn = $db->connect();
session_start();

if (isset($_GET['waiterrequests']))
{
	$devicename=$_GET['devicename'];
	$result = mysqli_query($conn, "Select tableid from device where devicename='".$devicename."'");
	$row = mysqli_fetch_assoc($result);
	$tableid = $row['tableid'];
	$requestids = array();
	$result = mysqli_query($conn, "Select tableid from waiterrequests");
	$i=0;
	while($row = mysqli_fetch_assoc($result))
	{
		$requestids[$i++]=$row['tableid'];
	}
	if (!in_array($tableid, $requestids))
	{
		mysqli_query($conn, "insert into waiterrequests values($tableid)");
	}
	
	/*if (isset($_SESSION['waiterrequests']))
	{	$requestids = $_SESSION['waiterrequests'];
		$flag=0;
		for ($i=0;$i<sizeof($requestids);$i++)
		{
			if ($requestids[$i]==$tableid)
			{
				$flag=1;
				break;
			}
		}
		if ($flag==0)
			$requestids[$i]=$tableid;
	}
	else
	{
		$requestids[0]=$tableid;
	}
	$_SESSION['waiterrequests']=$requestids;*/
	print_r($requestids);
	
}
else if (isset($_GET['sendwaiter']))
{
	 $tablename =  $_GET['sendwaiter'];
	$result = mysqli_query($conn, "Select tableid from restable where tablename='".$tablename."'");
	$row = mysqli_fetch_assoc($result);
	$tableid = $row['tableid'];
	
	mysqli_query($conn, "delete from waiterrequests where tableid=$tableid");
	/*$newids = array();
	$requestids = $_SESSION['waiterrequests'];
	print_r($_SESSION['waiterrequests']);
	$count=0;
	for ($i=0;$i<sizeof($requestids);$i++)
	{
		if ($requestids[$i]!=$tableid)
		{
			 $newids[$count]=$requestids[$i];
			$count++;
		}	
	}
	$_SESSION['waiterrequests']=$newids;*/
}
?>
