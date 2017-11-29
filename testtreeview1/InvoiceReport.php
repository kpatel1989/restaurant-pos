<?php
  session_start();

if (isset($_SESSION['admin']))
{
?>
<?
require_once("autoload.php") 
?>

<? // MPage::BeginBlock() ?>
<?php include "head.php"; ?>
<? // MPage::EndBlock("head") ?>

<? // MPage::BeginBlock() ?>
<div id="wrapper">
    <div id="content">

<html>
<head>
<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
<script type="text/javascript" src="jsDatePick.min.1.3.js"></script>
<link rel="stylesheet" href="print.css" type="text/css" media="print" />
<script src="jquery.min.js">
</script>
<script type="text/javascript">
	
	window.onready = function(){
		new JsDatePick({
			useMode:2,
			target:"to",
			dateFormat:"%Y-%m-%d"
		});
		new JsDatePick({
			useMode:2,
			target:"from",
			dateFormat:"%Y-%m-%d"
		});
		
	};
	
	function printreport()
	{
		var divele = document.getElementById("printdiv").innerHTML;
		var oldpage = document.body.innerHTML;
		document.body.innerHTML = "<html><head><title> Invoice Report </title></head><body>" + divele + "</body></html>";
		window.print();
		document.body.innerHTML = oldpage;
		
	}
</script>

</head>
<body>
<script>
	document.title="Invoice Report";
	
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("addorder").style.color="";
	document.getElementById("search").style.color="";
	document.getElementById("livescreen").style.color="";
	document.getElementById("reports").className="current";
	

</script>
<a href="InvoiceReport.php"> Invoice Report </a>
<br/>
<a href="UserLogReport.php"> User Log </a>

<form action="InvoiceReport.php" method="post">
	From Date: <input type="text" id="from" name="from" style="margin-right:15px;"/>
	To Date: <input type="text" id="to" name="to" style="margin-right:15px;"/>
	<input type="submit" name="search" id="search" value="search"/>
</form>
<div id="printdiv" class="print" >
<center>
<table>
	<tr>
		<th>
			Customer Name
		</th>
		<th>
			Customer Address
		</th>
		<th>
			Created Time
		</th>
		<th>
			Total
		</th>
		<th>
			Admin User
		</th>
		<th>
			Show Invoice
		</th>
	</tr>
<?php
REQUIRE "class._database.php";


	if(isset($_POST['search']))
	{
		$db=new _database();
		$conn = $db->connect();
		
		$result2=mysqli_query($conn, "select invoice.invoiceid, MIN(CustomerName) as CustomerName,MIN(CustomerAddress) as CustomerAddress,MIN(CreatedTime) as CreatedTime,SUM(Quantity*Amount) as totamt,MIN(concat(Fname , ' ' , Lname)) as Name from invoice,userdetail,invoiceitem where invoice.AdminUserDetailId=userdetail.UserDetailId and invoice.InvoiceId=invoiceitem.InvoiceId and invoice.createdtime > '".$_POST['from']."' and  invoice.createdtime < '".$_POST['to']."' group by invoice.InvoiceId");
		//echo "select invoiceid, MIN(CustomerName) as CustomerName,MIN(CustomerAddress) as CustomerAddress,MIN(CreatedTime) as CreatedTime,SUM(Quantity*Amount) as totamt,MIN(concat(Fname , ' ' , Lname)) as Name from invoice,userdetail,invoiceitem where invoice.AdminUserDetailId=userdetail.UserDetailId and invoice.InvoiceId=invoiceitem.InvoiceId and invoice.createdtime > '".$_POST['from']."' and  invoice.createdtime < '".$_POST['to']."' group by invoice.InvoiceId";
		if($result2)
		{
		
			while($row = mysqli_fetch_assoc($result2))
			{
			
?>
			<tr>
				<td>
					<?php echo $row["CustomerName"]; ?>
				</td>
				<td>
					<?php echo $row["CustomerAddress"]; ?>
				</td>
				<td>
					<?php echo $row["CreatedTime"]; ?>
				</td>
				<td>
					<?php echo $row["totamt"]; ?>
				</td>
				<td>
					<?php echo $row["Name"]; ?>
				</td>
				<td>
					<a href="invoice.php?invoiceid=<?php echo $row['invoiceid'] ?>" target="_blank"> Show Invoice </a>
 				</td>
			</tr>
			

<?php
			}
		}
		 
	}
?>
</table>
</center>
</div>
<?php
if(isset($_POST['search']))
	{ ?>
<div>
	<center><input type=submit name="print" value="Print" onClick="printreport()"> </center>
</div>
<?php }
?>
</html>
	</div>
<? // MPage::EndBlock("body") ?>

<? // MPage::BeginBlock() ?>

<?php include "sidebar.php"; ?>
<? // MPage::EndBlock("sidebar") ?>

<? // MPage::BeginBlock() ?>

<?php include "foot.php"; ?>
<? // MPage::EndBlock("footer") ?>
<?php
}
else
{
header("Location: /");
}

?>