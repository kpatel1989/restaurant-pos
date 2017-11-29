<?php
require "class._database.php";

$db=new _database();
$conn = $db->connect();
if (isset($_REQUEST['androidid']))
{
	$androidid = $_REQUEST['androidid'];
	//echo "got android id :" . $androidid;
	$qry="SELECT tableid,waiterid FROM device where devicename='".$androidid."'";
	if ($result = mysqli_query($conn, $qry))
	{
		if ($row = mysqli_fetch_assoc($result))
		{
			$tableid = $row['tableid'];
			$waiterid = $row['waiterid'];
                        if($tableid=="")
                            $tableid=-1;
                        if($waiterid=="")
                            $waiterid=-1;
			echo $tableid . "#" . $waiterid;
			
		}
		else
		{
			echo mysqli_error($conn);
		}
	}
	else
	{
		echo mysqli_error($conn);
	}
}
?>