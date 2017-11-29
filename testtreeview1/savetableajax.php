<?php
require "class._database.php";

$db=new _database();
$conn = $db->connect();
echo "out";
$tableid="";
$tablename="";
$view="";
$xpos="";
$ypos="";
$view="";
$qry="";
if (isset($_POST['tableid']))
	$tableid = $_POST['tableid'];

if (isset($_POST['view']))
	$view=$_POST['view'];

if (isset($_POST['xpos']))
	$xpos = $_POST['xpos'];

if (isset($_POST['ypos']))
	$ypos = $_POST['ypos'];

if ($view=="table")
	$qry = "UPDATE `restable` SET `PosX`=". $xpos .",`PosY`=". $ypos ." WHERE tableid=". $tableid;
else
	$qry = "UPDATE `section` SET `PosX`=". $xpos .",`PosY`=". $ypos ." WHERE sectionid=". $tableid;
echo $qry;
try{
	$result = mysqli_query($conn, $qry);
}	
catch (Exception $e)
{
	echo mysqli_error($conn);
}

?>