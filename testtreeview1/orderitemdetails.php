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
require "class._database.php";

$db=new _database();
$conn = $db->connect();
$tableid=0;
$orderid=0;
$status=0;
$order="";

	if (isset($_REQUEST['tableid']))
	{
		$tableid=$_REQUEST['tableid'];
		$qry="Select orderid,status from `order` where tableid=".$tableid . " and status!=5";
		if ($result = mysqli_query($conn, $qry))
		{	
			if ($row = mysqli_fetch_assoc($result))
			{
				$orderid=$row['orderid'];
				$status=$row['status'];
			}
			else 
				echo mysqli_error($conn);
			if ($status==0)
				$qry = "update `order` set status=1 where orderid=" . $orderid;
			if (mysqli_query($conn, $qry))
			{	
				if (mysqli_fetch_assoc($result))
				{}
				else 
					echo mysqli_error($conn);
				
			}
			else 
				echo mysqli_error($conn);
		}
		else 
			echo mysqli_error($conn);
	}
	
	if(isset($_POST['approve']))
	{
		
		$orderid=$_POST['orderid'];
	
	
		$date = date("Y-m-d H:i:s");
		$qry = "update `order` set adminuserdetailid=". $_SESSION['admin'] .", status=2,AcceptedTime='".$date."' where orderid=" . $orderid;
		//echo $qry;
		if (mysqli_query($conn, $qry))
		{
			
		}else 
			echo mysqli_error($conn);
	}
	else if(isset($_POST['disapprove']))
	{
		
		mysqli_query($conn, "delete from `orderitem` where orderid=".$_POST['orderid'] ." and isserved!=1");
		$result = mysqli_query($conn, "select count(*) as total from orderitem where orderid=".$_POST['orderid']);
		$row=mysqli_fetch_assoc($result);
		if ($row['total']==0)
		{
			mysqli_query($conn, "update `order` set status=5 where orderid=".$_POST['orderid']);
			//mysqli_query($conn, "delete from `order` where orderid=".$_POST['orderid']);
			
		}
		
	}
	else if (isset($_POST['checkout']))
	{
		$_SESSION["orderid"]=$orderid;
		header('Location: /bill.php');
	}
	
	
	
	

?>
<html>
<head>
<script src="jquery.min.js">
</script>
<script>
	function approve()
	{
		return true;
	}
	function disapprove()
	{
		window.close();
		return true;
	}
	function checkout()
	{
		return true;
	}
	function setserved(obj)
	{
		
		request = $.ajax({
			url: "setserved.php?orderitemid="+obj.id,
			method: "get",
			data: "served=1"
		});
		request.done(function (response, textStatus, jqXHR){
			obj.style.display = "none";
			
			document.getElementById("img"+obj.id).style.display = "block";
                        
		});
		
	}
        function disapprove(obj){
            request = $.ajax({
			url: "disapproveorder.php?orderitemid="+obj.id,
			method: "get",
			data: "disapprove=1"
		});
		request.done(function (response, textStatus, jqXHR){
			obj.style.display = "none";
			window.top.location.href = window.top.location.href;
			
		});
        }
</script>
</head>
<body>
<script>
	document.title="Order Item Details";
</script>
<form action="orderitemdetails.php" method="post">
<input type="hidden" value="<?php echo $tableid ?>" name="tableid"/>
<input type="hidden" value="<?php echo $orderid ?>" name="orderid"/>
<div>
	<table>
		<tr>
			<th> Items </th>
			<th> Quantity </th>
			<th> Served </th>
            <th> Disapprove </th>
		</tr> 
		<?php
                        $result1 = mysqli_query($conn, "Select status from `order` where orderid=".$orderid);
                        $row1=  mysqli_fetch_array($result1);
			$qry = "Select o.orderitemid,i.item_name,o.quantity,o.isserved from orderitem o LEFT JOIN items i on o.item_id=i.item_id where o.orderid=".$orderid;
			$result=mysqli_query($conn, $qry);
			while($row = mysqli_fetch_assoc($result)){
				?><tr> <td> <?php echo $row['item_name']; ?> </td>
				<td> <?php echo $row['quantity']; ?> </td>
				<td>
					<input type="checkbox" id="<?php echo $row['orderitemid'] ?>" onchange="setserved(this)" unchecked/>
					
                                        <?php $name= "img".$row['orderitemid'] ;?>
					<img id=<?php echo $name; ?> src="ok.png"/>
                                </td>
					<script>
					<?php if ($row['isserved']!=1) { ?>
						document.getElementById("<?php echo $name ?>").style.display="none";
						document.getElementById("<?php echo $row['orderitemid'] ?>").style.display="block";
					<?php  } else { ?> 
						document.getElementById("<?php echo $name ?>").style.display="block";
						document.getElementById("<?php echo $row['orderitemid'] ?>").style.display="none";
					<?php } ?>
					</script>
                                <td>
                                    <?php if ($row['isserved']!=1 && ($row1['status']==1 || $row1['status']==9)) { 
                                        ?>
                                       <input type="checkbox" id="<?php echo $row['orderitemid'] ?>" onchange="disapprove(this)" unchecked/>
                                    <?php } ?>	

                                </td>
				</tr>
			<?php  } ?> 
					
		<tr>
			<td colspan=4 align="center">
				<?php
					$qry="Select status from `order` where tableid=".$tableid . "and status!=5";
					if ($result = mysqli_query($conn, $qry)){	
						if ($row = mysqli_fetch_assoc($result))
							$status=$row['status'];
						else 
							mysqli_error($conn);
					} else 
						mysqli_error($conn);
					if ($status!=4)	{
						
					?>
				<input type="submit" value="Approve Order" id="approve" name="approve"onclick="javascript:return approve()" style="width:150" />
				
				
				<?php 
					} else { ?>
				<input type="submit" value="Checkout" name="checkout" onclick="javascript:return checkout()" style="width:150"/>
				<?php } ?>
		</tr>
	</table>
</div>

</form>
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