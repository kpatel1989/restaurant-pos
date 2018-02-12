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

<?php
REQUIRE "class._database.php";
//echo "<link rel='stylesheet' type='text/css' href='table_style.css' />";


?>
<html>
<head>
<title>
	Search Orders
</title>
<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
<script type="text/javascript" src="jsDatePick.min.1.3.js"></script>

<script src="jquery.min.js">
</script>

</head>
<body >
<center>
<script type="text/javascript">
	
	window.onready = function(){
		new JsDatePick({
			useMode:2,
			target:"date",
			dateFormat:"%Y-%m-%d"
		});
	};
	
	
</script>
<script>
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("addorder").style.color="";
	document.getElementById("search").style.color="#5D99A3";
	document.getElementById("livescreen").style.color="";
	document.getElementById("reports").className="";
	document.title="Search Order";
</script>
<div style="background:#858585; padding-top:5px; padding-bottom:1px; color:#ffffff; font-size:14;">
<font class="header">
<form action="searchorder.php" method="post">
<pre>
	<font class="header">Date : </font><input type="text" id="date" name="date" />	<font class="header">Table : </font><select id="tableid" name="tableid">
		<option value="all">All Table</option>
		<?php
		$db=new _database();
		$conn = $db->connect();
		$result2=mysqli_query($conn, "select TableId,TableName from restable");
		while($row = mysqli_fetch_assoc($result2))
		{
		?>
		<option value="<?php echo $row['TableId']; ?>">
		<?php echo $row['TableName']; ?>
		</option>
		<?php } ?>
	</select>	<input id="search" name="search" type="Submit" value="Search"/>
</pre>
</form>
</div>
</center>
<br/> <br/> <br/>
<center>
<?php
if(isset($_POST["search"])){
	$db=new _database();
$conn = $db->connect();



?>
<table width="600px"> 
<tr style="background:#858585;color:#ffffff;">
	<th>
		Order ID
	</th>
	<th>
		Table
	</th>
	<th>
		Customer Name
	</th>
	
	<th>
		Billed Time
	</th>
	
	<th>
		Total Price
	</th>
	<th>
		EDIT
	</th>
</tr>
<?php 
$tableid = $_POST['tableid'];
if ($tableid=="all")
{
	$qry="select o.*, sum(i.quantity*i.price) as 'total', iv.customername from `order` o, orderitem i, invoice iv where o.orderid=i.orderid and i.orderid= iv.orderid and DATE_FORMAT(o.CreatedTime, '%Y-%m-%d')='".$_POST["date"]."' group by o.orderid ";
}
else{
	$qry="select o.*, sum(i.quantity*i.price) as 'total', iv.customername from `order` o, orderitem i, invoice iv where o.orderid=i.orderid and i.orderid= iv.orderid and DATE_FORMAT(o.CreatedTime, '%Y-%m-%d')='".$_POST["date"]."' and TableId=".$_POST["tableid"] ." group by o.orderid ";
}

if ($result2=mysqli_query($conn, $qry))
{
	$flag=false;
}
else
	echo mysqli_error($conn);

while($row = mysqli_fetch_assoc($result2))
{
	if ($row['TableId']!=NULL)
	{
		if($flag==false){
			$flag=true;
			echo "<tr class='even'>";
			}
		else {
			echo "<tr class='odd'>";
			$flag=false;
		}
		$result3 = mysqli_query($conn, "Select tablename from restable where tableid=".$row['TableId']);
		$row3 = mysqli_fetch_assoc($result3);
		
		echo "<td>".$row['OrderId']."</td>";
		echo "<td>".$row3['tablename']."</td>";
		echo "<td>".$row['customername']."</td>";
		echo "<td>".$row['BilledTime']."</td>";
		echo "<td>". $row['total']."</td>";
		echo "<td><a href='editorders.php?id=".$row['OrderId']."'>Edit</a></td></tr>";
	}
}
?>
</table>
<?php 
}
?>
</center>
</body>
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
