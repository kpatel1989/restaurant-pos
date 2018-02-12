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

$result = mysqli_query($conn, "Select restaurantname,address,phoneno,faxno from Restaurantdetail");
$row = mysqli_fetch_assoc($result);
$restaurantname=$row['restaurantname'];
$address=$row['address'];
$faxno=$row['phoneno'];
$phoneno=$row['faxno'];

if (isset($_POST['save']))
{
	$restaurantname=$_POST['restaurantname'];
	$address=$_POST['address'];
	$phoneno=$_POST['phoneno'];
	$faxno=$_POST['faxno'];
	
	if (mysqli_query($conn, "UPDATE `restaurantdetail` SET `RestaurantName`='". $restaurantname ."',`Address`='". $address ."',`PhoneNo`=". $phoneno .",`FaxNo`=". $faxno." WHERE restaurantdetailid=1"))
	{}
	else
		echo mysqli_error($conn);
	
}

?>

<script>
	function editable()
	{
		document.getElementById("restaurantname").removeAttribute("disabled");
		document.getElementById("address").removeAttribute("disabled");
		document.getElementById("phoneno").removeAttribute("disabled");
		document.getElementById("faxno").removeAttribute("disabled");
		document.getElementById("edit").style.display = "none";
		document.getElementById("save").style.display = "inline";
	}
	function validate()
	{
		msg="";
		if (document.getElementById("restaurantname").value==""){
			document.getElementById("restaurantname").style.border = '2px solid red';
			msg += "Name is Invalid";
		}
		
		if (document.getElementById("address").value==""){
			document.getElementById("restaurantname").style.border = '2px solid red';
			msg += "\n Address is Invalid";
		}
		 if (document.getElementById("phoneno").value==""){
			document.getElementById("restaurantname").style.border = '2px solid red';
			msg += "\n Phone Number is Invalid";
		}
		 if (document.getElementById("faxno").value==""){
		document.getElementById("faxno").style.border = '2px solid red';
			msg += "\n Fax Number is Invalid";
		}
		if (msg!=""){
			alert(msg);
			return false;
		}
		else{

			return true;
			
		}
	}
	
	function cancel()
	{
		document.getElementById("restaurantname").value="";
		document.getElementById("address").value="";
		document.getElementById("phoneno").value="";
		document.getElementById("faxno").value="";
		document.getElementById("restaurantname").style.border="";
		document.getElementById("address").style.border="";
		document.getElementById("phoneno").style.border="";
		document.getElementById("faxno").style.border="";
		
		document.getElementById("edit").style.display = "inline";
		document.getElementById("save").style.display = "none";
	}
</script>


<script>
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="current";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("reports").className="";
	document.title = "Settings";
</script>
<form action="Settings.php" method="post">
<table>
	<tr>
		<td width="150"> Restaurant Name </td> <td> <input type="text" name="restaurantname" id="restaurantname" value="<?php echo $restaurantname  ?>" disabled /> </td>
	</tr>
	<tr>
		<td> Address </td> <td> <textarea name="address" id="address"  style="width:150;height:100;resize:none" disabled ><?php echo $address ?> </textarea> </td>
	</tr>
	<tr>
		<td> Phone Number </td> <td> <input type="text" name="phoneno" id="phoneno"  value="<?php echo $phoneno ?>" disabled /> </td>
	</tr>
	<tr>
		<td> Fax Number </td> <td> <input type="text" name="faxno" id="faxno" value="<?php echo $faxno ?>" disabled /> </td>
	</tr>
	<tr>
		<td> </td>
		<td> <input type="button" name="edit" id="edit" onclick="editable()" value="Edit" /> <input type="submit" name="save" id="save" style="display:none" value="Save" onclick="javascript:return validate()"/>  <input type="button" name="cancel" onclick="cancel()" value="Cancel"/> 
	</tr>
</table>
</form>

</div>
<? // MPage::EndBlock("body") ?>

<? // MPage::BeginBlock() ?>

<?php include "sidebar.php"; ?>
<? // MPage::EndBlock("sidebar") ?>

<? // MPage::BeginBlock() ?>

<?php include "foot.php"; ?>
<? // MPage::EndBlock("footer") ?>
</body></html>
<?php
}
else
{
header("Location: /");
}

?>