
<?php
require "class._database.php";
$db=new _database();
$conn = $db->connect();
session_start();
	if(isset($_GET["tableid"])){
		$tableid=$_GET["tableid"];
		if($_GET["orderid"]==-2 || $_GET["orderid"]==-1){
			
			
			$result=mysqli_query($conn, "select max(orderid) from restaurantpos.order;");
			$row = mysqli_fetch_assoc($result);
			$id=$row["max(orderid)"];
			$id=$id+1;
		}
		else{
			$id=$_GET["orderid"];
		}
		
		$today = date("Y-m-d H:i:s");
		
		
		if($waiterrow=mysqli_query($conn, "select WaiterId from device where DeviceName='".$_GET["devicename"]."'")){
			$row2=  mysqli_fetch_assoc($waiterrow);
			$waiterid=$row2["WaiterId"];
			
		}else
			echo "waiter".mysqli_error($conn);
		//echo "INSERT INTO restaurantpos.order (`OrderId`, `UserDetailId`, `TableId`, `WaiterId`, `CreatedTime`, `AcceptedTime`, `ServedTime`, `BilledTime`, `Status`, `Notes`) VALUES (".$id.",1,".$_GET["tableid"].",".$waiterid.",'".$today."','".$today."','".$today."','".$today."',0,'')";
		if($result1=mysqli_query($conn, "INSERT INTO restaurantpos.order (`OrderId`, `UserDetailId`, `TableId`, `WaiterId`, `CreatedTime`, `AcceptedTime`, `ServedTime`, `BilledTime`, `Status`, `Notes`) VALUES (".$id.",1,".$_GET["tableid"].",".$waiterid.",'".$today."','".$today."','".$today."','".$today."',0,'')"))
		{
			
			$result3=mysqli_query($conn, "select max(devicelogid) from restaurantpos.devicelog");
			$row3 = mysqli_fetch_assoc($result3);
			$id3=$row3["max(devicelogid)"];
			$id3=$id3+1;
			
			$result3=mysqli_query($conn, "select deviceid from device where devicename='".$_GET['devicename']."'");
			$row3 = mysqli_fetch_assoc($result3);
			$deviceid=$row3["deviceid"];
			

			mysqli_query($conn, "INSERT INTO `devicelog`(`DeviceLogId`, `DeviceId`, `WaiterId`, `TableId`) VALUES (".$id3.",".$deviceid.",".$waiterid.",".$tableid.")");
			echo $id;
		}else{
			echo "orderinsert".mysqli_error($conn);
		}
		//echo "id=".$id;
		$qry = "update `order` set status=0 where orderid=" . $id;
		mysqli_query($conn, $qry);
		$order=$_GET["order"];
		$tok=strtok($order,"*");
		$initialtokens=array();
		$count=0;
		while($tok!=false){
			$initialtokens[$count]=$tok;
			$tok=strtok("*");
			$count++;
		}

		for($k=0;$k<sizeof($initialtokens);$k++){
			$token=$initialtokens[$k];
			$itemid=strtok($token,"-");
			
			$conn = $db->connect();
			
			
			if($result=mysqli_query($conn, "select price from items where item_id=".$itemid)){
                            $row = mysqli_fetch_assoc($result);
                            $price=$row["price"];
                            $qty=strtok("-");
                        }else{
                            echo "price"+mysqli_error($conn);
                        }

			if($result1=mysqli_query($conn, "select max(OrderItemId) from orderitem;")){
                            $row1 = mysqli_fetch_assoc($result1);
                            $orderitemid=$row1["max(OrderItemId)"];
                            $orderitemid=$orderitemid+1;
                        }else
                        {
                            echo "itemid"+mysqli_error($conn);
                        }
			if($result2=mysqli_query($conn, "INSERT INTO orderitem (`OrderItemId`, `OrderId`, `Item_Id`, `Quantity`, `Price`, `isServed`) VALUES (".$orderitemid.",".$id.",".$itemid.",".$qty.",".$price.",0)")){
                            
                        }else
                            echo "oerderitem"+mysqli_error($conn);
			
		}
	}
    else if(isset($_POST["name"])){
		$neworders="";
		$conn = $db->connect();
		$result1=mysqli_query($conn, "select * from restaurantpos.order where status!=5 and tableid is not NULL");
		while($row1 = mysqli_fetch_assoc($result1)){
			$result2=mysqli_query($conn, "Select sum(quantity) as qty from orderitem where orderid=".$row1['OrderId']);
			$row2 = mysqli_fetch_assoc($result2);
			$total = $row2['qty'];
			$neworders= $total."-".$row1["TableId"]. "-" . $row1["Status"] ."*";
			
		echo $neworders;
		}
		echo "^<table><caption> Table Orders </caption><tr><th> Table Name </th><th> Quantity </th></tr>";
	
			
			$qry = "Select orderid,tableid,status from `order` where status!=5 and tableid is not NULL";
			$result=mysqli_query($conn, $qry);
			while($row = mysqli_fetch_assoc($result)){
				
				echo '<tr> <td> <a href="orderitemdetails.php?tableid='.$row['tableid'].'" target="_blank">';
							$result1=mysqli_query($conn, "Select tablename from restable where tableId=".$row['tableid']);
							$row1 = mysqli_fetch_assoc($result1);
							echo $row1['tablename'];
						
				echo "</a></td><td>";
						$ser = 0;
						$result3=mysqli_query($conn, "Select sum(quantity) as served from orderitem where orderid=".$row['orderid']." and isserved=1");
						$row3 = mysqli_fetch_assoc($result3);
						$ser=$row3['served'];
						echo  $ser."/";
						$result2=mysqli_query($conn, "Select sum(quantity) as qty from orderitem where orderid=".$row['orderid']);
						$row2 = mysqli_fetch_assoc($result2);
						echo $row2['qty'];
						
						if ($ser == $row2['qty'] && $row['status']<4)
						{
							$qry = "update `order` set status=3 where orderid=" . $row['orderid'];
							//mysqli_query($conn, $qry);
							
						}
				echo "</td>	</tr>";
			}
		echo "</table>^<option> Select a table</option>";
		$result = mysqli_query($conn, "select tablename from restable where tableid in (select tableid from waiterrequests)");
		while($row = mysqli_fetch_assoc($result))
		{
			
			/*$waiterrequests = $_SESSION['waiterrequests'];
			for($i=0;$i<sizeof($waiterrequests);$i++)
			{
				$result=mysqli_query($conn, "Select tablename from restable where tableid=$waiterrequests[$i]");
				$row=mysqli_fetch_assoc($result);
			*/	
			echo "<option>".$row['tablename']."</option>";
			//}
		
		}
	}
	else if(isset($_POST["print"])){
		$customername = $_POST['customername'];
		$customeraddr = $_POST['customeraddr'];
		$orderid = $_POST['orderid'];
		$date = $_POST['date'];
		$itemnames = strtok($_POST['itemnames'],",");
		$names=array();
		$count=0;
		while($itemnames!=false){
			$names[$count]=$itemnames;
			$itemnames=strtok(",");
			$count++;

		}

		
		$quantities=array();
		$qtys = strtok($_POST['qtys'],",");
		$count=0;
		while($qtys!=false){
			 $quantities[$count]=$qtys;
			$qtys=strtok(",");
			$count++;
			
		}

		
		$prcs = array();
		$prices = strtok($_POST['prices'],",");
		$count=0;
		while($prices!=false){
			 $prcs[$count]=$prices;
			$prices=strtok(",");
			$count++;

		}
		$qry = "update `order` set status=5,BilledTime='".$date ."' where orderid=" . $orderid;
		
		if (mysqli_query($conn, $qry))
		{}
		else
			echo mysqli_error($conn);
		
		$result=mysqli_query($conn, "select max(invoiceid) from restaurantpos.invoice;");
		$row = mysqli_fetch_assoc($result);
		$id=$row["max(invoiceid)"];
		$id=$id+1;
		$invoiceid=$id;
		$qry="INSERT INTO `invoice`(`InvoiceId`, `OrderId`, `CustomerName`, `CustomerAddress`, `CreatedTime`, `AdminUserDetailId`) VALUES (".$id .",".$orderid .",'".$customername ."','".$customeraddr ."','".$date."',". $_SESSION['admin'].")";
		mysqli_query($conn, $qry);
		$result=mysqli_query($conn, "select max(invoiceitemid) from restaurantpos.invoiceitem;");
		$row = mysqli_fetch_assoc($result);
		$id=$row["max(invoiceitemid)"];
		$id=$id+1;
		for ($i=0;$i<$count;$i++)
		{
			$qry="INSERT INTO `invoiceitem`(`InvoiceItemId`, `InvoiceId`, `ItemName`, `Quantity`, `Amount`) VALUES (".$id .",".$invoiceid .",'".$names[$i] ."',".$quantities[$i] .",".$prcs[$i] .")";
			$id++;
			mysqli_query($conn, $qry);
		}
		echo "<body><script language=\"javascript\"> window.close(); </script></body>";
	}
    else{
            
		if($_GET["orderid"]==-2 || $_GET["orderid"]==-1){
			
			
			$result=mysqli_query($conn, "select max(orderid) from restaurantpos.order;");
			$row = mysqli_fetch_assoc($result);
			$id=$row["max(orderid)"];
			$id=$id+1;
		}
		else{
			$id=$_GET["orderid"];
		}
		
		$today = date("Y-m-d H:i:s");
               
		$tablerow=mysqli_query($conn, "select TableId from device where DeviceName='".$_GET["devicename"]."'");
		$row2=  mysqli_fetch_assoc($tablerow);
		$tableid=$row2["TableId"];
		$userid = 0;
		if (isset($_GET['userid']) && $_GET['userid']>0)
		{
			$userid = $_GET['userid'];
                        $qry="INSERT INTO restaurantpos.order (`OrderId`, `UserDetailId`, `TableId`,  `CreatedTime`, `AcceptedTime`, `ServedTime`, `BilledTime`, `Status`, `AdminUserDetailId`, `Notes`) VALUES (".$id.",". $userid.",".$tableid.",'".$today."','".$today."','".$today."','".$today."',0,0,'". $_GET["order"]."')";
		}
                else
                        $qry = "INSERT INTO restaurantpos.order (`OrderId`, `TableId`,  `CreatedTime`, `AcceptedTime`, `ServedTime`, `BilledTime`, `Status`, `AdminUserDetailId`, `Notes`) VALUES (".$id.",".$tableid.",'".$today."','".$today."','".$today."','".$today."',0,0,'". $_GET["order"]."')";
		if($result8=mysqli_query($conn, $qry))
		{
			
			
			echo $id;
		}
		else {
			echo mysqli_error($conn);    
		}
		$result3=mysqli_query($conn, "select max(devicelogid) from restaurantpos.devicelog");
		$row3 = mysqli_fetch_assoc($result3);
		$id3=$row3["max(devicelogid)"];
		$id3=$id3+1;
		
		$result3=mysqli_query($conn, "select deviceid from device where devicename='".$_GET['devicename']."'");
		$row3 = mysqli_fetch_assoc($result3);
		$deviceid=$row3["deviceid"];
		
		mysqli_query($conn, "INSERT INTO `devicelog`(`DeviceLogId`, `DeviceId`, , `TableId`) VALUES (".$id3.",".$deviceid.",".$tableid.")");

		if (isset($_GET['userid']) && $_GET['userid']>0)
		{
			echo "asd:" + $_GET['userid'];
			$result8=mysqli_query($conn, "select max(userlogid) from restaurantpos.userlog;");
			$row8 = mysqli_fetch_assoc($result8);
			$id=$row8["max(userlogid)"];
			$id=$id+1;
			mysqli_query($conn, "INSERT INTO `userlog`(`UserLogId`, `UserDetailId`, `Action`) VALUES (".$id.",".$_GET['userid'].",'Placed order')");
		}
		$order=$_GET["order"];
		$tok=strtok($order,"*");
		$initialtokens=array();
		$count=0;
		while($tok!=false){
			$initialtokens[$count]=$tok;
			$tok=strtok("*");
			$count++;
		}
		$qry = "update `order` set status=0 where orderid=" . $id;
		mysqli_query($conn, $qry);
		for($k=0;$k<sizeof($initialtokens);$k++){
			$token=$initialtokens[$k];
			$itemid=strtok($token,"-");
			
			$conn = $db->connect();
			
			
			$result=mysqli_query($conn, "select price from items where item_id=".$itemid);
			$row = mysqli_fetch_assoc($result);
			$price=$row["price"];
			$qty=strtok("-");
			
			$result1=mysqli_query($conn, "select max(OrderItemId) from orderitem;");
			$row1 = mysqli_fetch_assoc($result1);
			$orderitemid=$row1["max(OrderItemId)"];
			$orderitemid=$orderitemid+1;
			
			if($result2=mysqli_query($conn, "INSERT INTO orderitem (`OrderItemId`, `OrderId`, `Item_Id`, `Quantity`, `Price`, `isServed`) VALUES (".$orderitemid.",".$id.",".$itemid.",".$qty.",".$price.",0)")){
						
                        }
                        else
                            echo mysqli_error($conn);
			
		}
	}
	

?>


