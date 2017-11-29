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



$devicename="";
$deviceid="";
$assignedto="";
$radiowaiter="";
$radiotable="";
$addnew=1;
$waiterid="";
$tableid="";
$notes="";
$searchquery="SELECT d.deviceid, d.devicename, d.tableid,d.waiterid,w.waitername,t.tablename FROM `device` d LEFT JOIN waiter w on w.waiterid = d.waiterid LEFT JOIN restable t on  t.tableid = d.tableid order by devicename";

	if (isset($_POST['save'])) // -----------------------------------------------------------------SAVE
	{
		$tmp = implode('',$_POST);
		$devicedetailhash = md5($tmp);
		if(isset($_SESSION["devicedetailhash"]) && $_SESSION["devicedetailhash"] == $devicedetailhash)
		{
		}
		else
		{
			$_SESSION["devicedetailhash"] = $devicedetailhash;
			if (isset($_POST['deviceid']))
				$deviceid = $_POST['deviceid'];
			if (isset($_POST['devicename']))
				$devicename = $_POST['devicename'];	
			if (isset($_POST['waiterid']))
				$waiterid = $_POST['waiterid'];
			if (isset($_POST['tableid']))
				$tableid = $_POST['tableid'];
			if (isset($_POST['notes']))
				$notes = $_POST['notes'];
				
			if (isset($_POST['isaddnew']))
				$addnew=$_POST['isaddnew'];

			$qry="";
			if ($addnew=="1")
			{
				$qry="SELECT max(deviceid) as 'newid' FROM device";
				if ($result = mysqli_query($conn, $qry))
				{
					$row = mysqli_fetch_assoc($result);
					$newid = $row['newid']+1;
					$qry2="";
					if ($tableid!="")
						$qry2="INSERT INTO  `restaurantpos`.`device` (`DeviceID` ,`DeviceName` ,`WaiterId` ,`TableId` ,`Notes`) VALUES (". $newid .",'". $devicename ."',NULL,". $tableid .",'". $notes ."')";
					else if ($waiterid!="")
						$qry2="INSERT INTO  `restaurantpos`.`device` (`DeviceID` ,`DeviceName` ,`WaiterId` ,`TableId` ,`Notes`) VALUES (". $newid .",'". $devicename ."',". $waiterid .",NULL,'". $notes ."')";
					else
						$qry2="INSERT INTO  `restaurantpos`.`device` (`DeviceID` ,`DeviceName` ,`Notes`) VALUES (". $newid .",'". $devicename ."','". $notes ."')";
					mysqli_query($conn, $qry2);
					
				}
				else
					echo mysqli_error($conn);
			}
			else
			{
				if ($tableid!="")
					$qry = "UPDATE `device` SET `DeviceName`='". $devicename ."',`TableId`=". $tableid .",`WaiterId`= NULL,`Notes`='". $notes ."' WHERE deviceid=". $deviceid;
				else if ($waiterid!="")
					$qry = "UPDATE `device` SET `DeviceName`='". $devicename ."',`TableId`= NULL,`WaiterId`='". $waiterid ."',`Notes`='". $notes ."' WHERE deviceid=". $deviceid;
				else
					$qry = "UPDATE `device` SET `DeviceName`='". $devicename ."',`TableId`= NULL,`WaiterId`=NULL,`Notes`='". $notes ."' WHERE deviceid=". $deviceid;
				
				try{
					$result = mysqli_query($conn, $qry);
				}	
				catch (Exception $e)
				{
					echo $e->getMessage();
				}

			}
			$devicename="";
			$deviceid="";
			$assignedto="";
			$radiowaiter="";
			$radiotable="";
			$addnew=1;
			$waiterid="";
			$tableid="";
			$notes="";
			unset($_POST['save']);
		}
	}
	else if (isset($_POST['cancel']))
	{
		$devicename="";
		$deviceid="";
		$assignedto="";
		$radiowaiter="";
		$radiotable="";
		$addnew=1;
		$waiterid="";
		$tableid="";
		$notes="";
		unset($_POST['cancel']);
	}
	
	else if (isset($_POST['delete']))
	{
		$tmp = implode('',$_POST);
		$devicedetailhash = md5($tmp);
		if(isset($_SESSION["devicedetailhash"]) && $_SESSION["devicedetailhash"] == $devicedetailhash)
		{
		}
		else
		{
			$_SESSION["devicedetailhash"] = $devicedetailhash;

				if (isset($_POST['deviceid']))
					$deviceid = $_POST['deviceid'];
					
				$qry = "DELETE from device where deviceid=" .$deviceid;
				if ($result = mysqli_query($conn, $qry))
				{
				}
				else
					echo mysqli_error($conn);
			$devicename="";
			$deviceid="";
			$assignedto="";
			$radiowaiter="";
			$radiotable="";
			$addnew=1;
			$waiterid="";
			$tableid="";
			$notes="";
		}
	}
	else if (isset($_POST['search']))
	{
		if (isset($_POST['searchstring']))
		$searchstring = $_POST['searchstring'];
		if ($searchstring=="")
			$searchquery="SELECT d.deviceid, d.devicename, d.tableid,d.waiterid,w.waitername,t.tablename FROM `device` d LEFT JOIN waiter w on w.waiterid = d.waiterid LEFT JOIN restable t on  t.tableid = d.tableid ";
		else
		{
			$searchquery = "SELECT d.deviceid,d.devicename,d.tableid,d.waiterid,w.waitername,t.tablename FROM `device` d LEFT JOIN waiter w on w.waiterid = d.waiterid LEFT JOIN restable t on  t.tableid = d.tableid WHERE d.devicename like '%".$searchstring ."%' or w.waitername like '%".$searchstring ."%' or t.tablename like '%".$searchstring ."%' ";
		}
		
		unset($_POST['search']);
		$addnew=1;
	}
	else if (isset($_POST['showall']))
	{
		$searchquery='SELECT deviceid,devicename,waiterid,tableid from device order by devicename';
		unset($_POST['showall']);
	}
	else if (isset($_POST['devicemgmt']))
	{
	}
	
	else if (isset($_REQUEST['deviceid']))
	{
		$deviceid = $_REQUEST['deviceid'];
		
		$qry = 'SELECT devicename,tableid,waiterid,notes from device where deviceid='.$deviceid;
		if ($result = mysqli_query($conn, $qry))
		{
			$row = mysqli_fetch_assoc($result);
			$devicename = $row['devicename'];
			$tableid = $row['tableid'];
			$waiterid = $row['waiterid'];
			$notes = $row['notes'];
			if($tableid != NULL)
			{
				$qry = 'SELECT tablename from restable where tableid='.$tableid;
				$result = mysqli_query($conn, $qry);
				$row = mysqli_fetch_assoc($result);
				$assignedto=$row['tablename'];
				
			}
			else if ($waiterid != NULL)
			{	
				$qry = 'SELECT waitername from waiter where waiterid='.$waiterid;
				$result = mysqli_query($conn, $qry);
				$row = mysqli_fetch_assoc($result);
				$assignedto=$row['waitername'];
				
			}
		}
		$addnew=2;
		unset($_REQUEST['deviceid']);
	}

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style1.css" media="screen"/>
<script language="javascript">
	function displayids()
	{
		document.getElementById("changeid").style.display = "block";
		document.getElementById("waiters").selectedIndex = 0;
		document.getElementById("tables").selectedIndex = 0;
	}
	function changeid(obj)
	{
		var idText = obj.options[obj.selectedIndex].text;
		
		if (idText != "Select a ID")
		{
			document.getElementById("changeid").style.display = "none";
			document.getElementById("assignedto").value= idText;
			
			
			if (obj.id == "waiters")
			{
				document.getElementById("waiterid").value= obj.options[obj.selectedIndex].value;
				document.getElementById("tableid").value= "";
			}
			else
			{
				document.getElementById("tableid").value= obj.options[obj.selectedIndex].value;
				document.getElementById("waiterid").value= "";
			}
		}
	}
	
	function validatesave()
	{
		var name= document.getElementById("devicename").value;
		var error = document.getElementById("error");
		if (name=="")
		{
			error.innerHTML = "Device name is a compulsory field.";
			return false;
		}
		else
		{
			error.innerHTML ="";
			return true;
		}
	}
	
	function checkavailability(obj)
	{
		$.ajax({
			url  : "uniquedevicename.php",
			type : "get",
		    data : "devicename="+obj.value,
			success: function(data) {
				if (data!="unique")
				{
					document.getElementById("error").innerHTML="Device already exists";
					document.getElementById(obj.id).focus();
				}
				else
					document.getElementById("error").innerHTML="&nbsp";
			}
		});
		
	}
</script>
</head>

<body >
<script>

	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="current";
	document.getElementById("userdetail").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("reports").className="";
	document.title = "Device Details";
</script>
<form name="devicemgmt" id="devicemgmt" action="DeviceManagement.php" method="post" >
<input type="hidden" id="deviceid" name="deviceid" value = "<?php echo $deviceid; ?>"/> 
<input type="hidden" name="waiterid" id="waiterid" value="<?php echo $waiterid; ?>" />
<input type="hidden" name="tableid" id="tableid" value="<?php echo $tableid; ?>" />
<input type="hidden" name="isaddnew" id="isaddnew" value="<?php echo $addnew; ?>" />
<center>
<div style=" width: 700px; ">
	<div style=" width: 300px; float: left; ">
		<table style=" width: 300px; " border=2> 
			<caption>Registered Tablets<br> 
				<input type="submit" name="search" value="Show All" >  
				<input type = "text" name="searchstring" />
				<input type="submit" name="search" value="" style="background-image: url(search.png);width: 40;background-size: 55%;background-repeat: no-repeat;background-position: center;">  

			</caption>
			
			<?php
				//$qry = 'SELECT deviceid,devicename,waiterid,tableid from device';
				$qry = $searchquery;
				if ($result = mysqli_query($conn, $qry))
				{
					while ($row = mysqli_fetch_assoc($result))
					{
						$tid=$row['tableid'];
						$wid=$row['waiterid'];
						$briefdes="";
						if($tid != NULL)
						{
							$briefdes = "assigned to  : " . $row['tablename'];
						}
						else if ($wid!=NULL)
						{	
							$briefdes = "assigned to : " .$row['waitername'];
						}
					?>
						<tr><td><a href="DeviceManagement.php?deviceid=<?php echo $row['deviceid']; ?>" ><?php echo $row['devicename'];?> </a></td>
							<td><?php 
										if ($briefdes=="")
										{
											$briefdes="not assigned yet.!!";
											?> <font color="red"> <?php
										} else {
											?> <font color="green"> <?php
									}echo " " . $briefdes;
								?></font> 
							</td></tr>
					<?php
					}
				} 
				
			?>
			</table>
	</div>
	
<div style=" width: 380px; float: right; ">
<table  >
		<caption>Device Details </caption>
		<tr>
			<td> Device name </td>
			<td><input type="text" size="20" id="devicename" name="devicename" value = "<?php echo $devicename; ?>" onblur="checkavailability(this)"/>
			<label id="error" > &nbsp; </label>
			</td>
		</tr>
		<tr height="60">
			<td> Assigned to :</td>
			<td width="307"><input type="text" size="20" id="assignedto" name="assignedto" value = "<?php echo $assignedto; ?>" disabled/>
				<a href="#" onclick="displayids()"> change ... ? </a>
				<div name="changeid" id="changeid" style="display:none">
					
					Waiter
					<select name="waiters" id="waiters" onchange="changeid(this)">
						<option selected="selected">Select an ID</option>
						<?php
							$qry = "SELECT waiterid,waitername from waiter where waiterid not in (Select distinct waiterid from device  where waiterid <> 'NULL')";
							
							if ($result = mysqli_query($conn, $qry))
							{
								while ($row = mysqli_fetch_assoc($result))
								{  ?>
									<option value="<?php echo $row['waiterid']; ?>"> <?php echo $row['waitername']; ?> </option>
								<?php
								}
							} else {
								
							}
						?>
					</select>
					
					Table
					<select name="tables" id="tables" onchange="changeid(this)">
						<option selected="selected">Select an ID</option>
						<?php
							$qry = "SELECT tableid,tablename from restable where tableid not in (Select distinct tableid from device  where tableid <> 'NULL')";
							
							if ($result = mysqli_query($conn, $qry))
							{
								while ($row = mysqli_fetch_assoc($result))
								{
									?>
									<option value=" <?php echo $row['tableid']; ?>"> <?php echo $row['tablename']; ?> </option>
								<?php
								}
							} else {
								
							}
						?>
					</select>

				</div>
			</td>
		</tr>
		<tr>
			<td> Notes </td>
			<td><textarea id="notes" name="notes" style="width: 255px;height: 70px;resize: none;"><?php echo $notes; ?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td> <input type="Submit" value="Save" name="save" onclick="javascript:return validatesave()"/>
			<input type="Submit" value="Cancel" name="cancel"/>
			
			<?php
			if ($addnew==2) {
			?>
 				<input type="Submit" value="Delete" name="delete"/>
			<?php }
			?>
			<br>
			<font color="red">
				<label for="error" id="error"> </label>
			</font>
			</td>
		</tr>
		
</table>
</div></div></center>
</form>
</body></html>
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