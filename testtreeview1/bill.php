<?php
  session_start();

if (isset($_SESSION['admin']))
{
?>
<?php
require "class._database.php";
$db=new _database();
$conn = $db->connect();
$orderid = 0;
$name =  "";
$address = "";
$phoneno = "";
$faxno = "";
if (isset($_GET['orderid']))
{
	$_SESSION['orderid']=$_GET['orderid'];
}
if (isset($_SESSION['orderid']) )
{
	$orderid=$_SESSION['orderid'];
	$orderid=$_SESSION['orderid'];
	unset($_SESSION['orderid']);
	$qry="Select * from restaurantdetail";
	if ($result = mysqli_query($conn, $qry))
	{	
		if ($row = mysqli_fetch_assoc($result))
		{
			$name=$row['RestaurantName'];
			$address=$row['Address'];
			$phoneno=$row['PhoneNo'];
			$faxno = $row['FaxNo'];
		}
		else 
			echo mysqli_error($conn);
	}


?>
	<html>
	<head>
	<title>
		<?php echo "Order : ". $orderid; ?>
	</title>
	
	<link rel="stylesheet" type="text/css" href="bill.css" />
	<link rel="stylesheet" href="print.css" type="text/css" media="print" />
	
	<script>
		function printbill()
		{
			document.getElementById("customername").style.border="";
			document.getElementById("customeraddr").style.border="";
			name=document.getElementById("customername").value;
			addr=document.getElementById("customeraddr").value;
			msg="";
			if (name=="")
			{
				msg="name";
				document.getElementById("customername").style.border='2px solid red';
			}
			if (addr=="")
			{
				msg="addr";
				document.getElementById("customeraddr").style.border='2px solid red';
			}
			if (msg!="")
			{
				alert ("Some fields are invalid. \n  Invalid fields are marked with RED borders");
				return false;
			}
			else
			{
				
				window.print();
				return true;
			}
		}
	</script>
	</head>
	<body>
		<form id="bill" action="android_order.php" method="post">
		<input type="hidden" value="<?php echo $orderid; ?>" name="orderid" />
		<div class="print">
			<center>
			<h3> <?php echo $name; ?> <br>
				<?php echo $address; ?> <br> </h3>
			
			<table>
				<td> Customer Name </td><td>  <input type="text" size="50" name="customername" id="customername"/></td> <td> Date : </td> <td> <input type="text" id="date" name="date" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly> </td>
			</tr><tr><td> Customer Address</td><td colspan=3>  <textarea name="customeraddr" id="customeraddr" ></textarea></td>
			</tr>
			</table>	
			
			<table>
		
									
										
				<tr>
					<th style="width:10%"> Sr No : </th>
					<th style="width:45%"> Items </th>
					<th style="width:10%"> Rate </th>
					<th style="width:15%"> Quantity </th>
					<th style="width:20%"> Total </th>
				</tr>
				<?php
					$qry="SELECT o.item_id,i.item_name,sum(o.quantity) as qty ,o.price,o.price*sum(o.quantity) as total FROM `orderitem` o LEFT JOIN `items` i on o.item_id=i.item_id WHERE orderid=". $orderid ." group by item_id";
					$result = mysqli_query($conn, $qry);
					$no=1;
					$nettotal=0;
					$itemnames = "";
					$prices = "";
					$qtys="";
					while ($row = mysqli_fetch_assoc($result))
					{ ?> <tr>
						<td style="width:10%"> <?php echo $no;  ?> </td>
						<td style="width:45%" > <?php echo $row['item_name']; $itemnames=$itemnames.",".$row['item_name']; ?> </td>
						<td style="width:10%"> <?php echo $row['price']; $prices = $prices.",".$row['price']; ?> </td>
						<td style="width:15%"> <?php echo $row['qty']; $qtys= $qtys.",".$row['qty']; ?> </td>
						<td style="width:20%"> Rs. <?php echo $row['total']; $nettotal=$nettotal+$row['total'] ;?> </td></tr>
					<?php $no=$no+1; }
					
					for ($i=$no;$i<20;$i++)
					{ ?> 
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
					<?php }
				?>
				<tr>
					<td style="width:10%"></td>
					<td style="width:45%">  </td>
					<td style="width:10%">  </td>
					<td style="width:15%">Net Total : </td>
					<td style="width:20%"> Rs. <?php echo $nettotal ;?> </td>
				</tr>
				<tr>
					<td colspan=3> Thank you.! </td>
					<td colspan=2>Signature : </td>
				</tr>
				
			</table>
			<input type="hidden" value="<?php echo $itemnames; ?>" name="itemnames" />
			<input type="hidden" value="<?php echo $prices; ?>" name="prices" />
			<input type="hidden" value="<?php echo $qtys; ?>" name="qtys" />
			</center>
		</div>
		<center>
		<input type=submit name="print" value="Print" onClick="javascript:return printbill()">
		</center>
		</form>
	</body>
	</html>
	<?php

}

}
else
{
header("Location: /");
}

?>
