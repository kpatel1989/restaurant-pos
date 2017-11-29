<?php
if (isset($_GET['devicename']))
    
{
    require "class._database.php";

$db=new _database();
$conn = $db->connect();
   
	
    $tablerow = mysqli_query($conn, "select TableId from device where DeviceName='".$_GET["devicename"]."'");
	$row2 =  mysqli_fetch_assoc($tablerow);
	$tableid = $row2["TableId"];
	
	$result = mysqli_query($conn, "Select status from `order` where tableid=".$tableid." and status!=5");
	$row = mysqli_fetch_assoc($result);
	echo $row['status'];
        
        if ($row['status']==9){
            
            $result3 = mysqli_query($conn, "select count(*) as total from orderitem where orderid=(Select orderid from `order` where tableid=".$tableid." and status!=5)");
            //echo "select count(*) as total from orderitem where orderid=(Select orderid from `order` where tableid=".$tableid." and status!=5)";
            $row3=mysqli_fetch_assoc($result3);
            if ($row3['total']==0)
            {
                    //mysqli_query($conn, "update `order` set status=5 where orderid=".$_POST['orderid']);
                    mysqli_query($conn, "delete from `order` where tableid=".$tableid." and status!=5");

            }
            else
            {
                 mysqli_query($conn, "update `order` set status=1 where tableid=".$tableid." and status!=5");
            }
              
        } 
            
}

?>