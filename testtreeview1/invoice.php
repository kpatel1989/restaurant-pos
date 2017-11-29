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
if (isset($_GET['invoiceid']))
{

	$invoiceid = $_GET['invoiceid'];
	
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
		<?php echo "Invoice : ". $invoiceid; ?>
	</title>
	
	<link rel="stylesheet" type="text/css" href="bill.css" />
	<link rel="stylesheet" href="print.css" type="text/css" media="print" />
	
	<script>
		function printbill()
		{
			window.print();
			
		}
	</script>
	</head>
	<body>
		
		<input type="hidden" value="<?php echo $orderid; ?>" name="orderid" />
		<div class="print">
			<center>
			<h3> <?php echo $name; ?> <br>
				<?php echo $address; ?> <br> </h3>
			<h5> Phone no :<?php echo $phoneno; ?> ,Fax No :<?php echo $faxno ?>
			<?php
				$result = mysqli_query($conn, "Select * from invoice where invoiceid=".$invoiceid);
				$row=mysqli_fetch_assoc($result);
			
			?>
			<table>
				<td> Customer Name </td><td> <?php echo $row['CustomerName'] ?></td> <td> Date : </td> <td> <?php echo $row['CreatedTime'] ?> </td>
			</tr><tr><td> Customer Address</td><td colspan=3> <?php echo $row['CustomerAddress'] ?> </td>
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
					$qry="SELECT *,Quantity*Amount as total FROM `invoiceitem` where invoiceid=".$invoiceid;
					$result = mysqli_query($conn, $qry);
					$no=1;
					$nettotal=0;
					
					while ($row = mysqli_fetch_assoc($result))
					{ ?> <tr>
						<td style="width:10%"> <?php echo $no;  ?> </td>
						<td style="width:45%" > <?php echo $row['ItemName'];?> </td>
						<td style="width:10%"> <?php echo $row['Amount'];  ?> </td>
						<td style="width:15%"> <?php echo $row['Quantity']; ?> </td>
						<td style="width:20%"> Rs. <?php echo $row['total']; $nettotal += $row['total']; ?> </td></tr>
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
			
			</center>
		</div>
		<center>
		<input type=submit name="print" value="Print" onClick="printbill()">
		</center>
		
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
