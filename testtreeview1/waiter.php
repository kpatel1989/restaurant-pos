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

 $save=false;
 $edit="false";
 $wid="";
 $name="";
 $age=0;
 $notes="";
 $db=new _database();
 $conn = $db->connect();
 
 if(isset($_POST["delete"]))
 {
	$tmp = implode('',$_POST);
	$waiterhash = md5($tmp);
	
	if(isset($_SESSION["waiterhash"]) && $_SESSION["waiterhash"] == $waiterhash)
	{
		
	}
	else
	{
		$_SESSION["waiterhash"] = $waiterhash;
		if(mysqli_query($conn, "delete from waiter where WaiterId=". $_POST["wid"]))
		{
		$save=true;
		}
		else{
		
		}
	}
 }else if(isset($_POST["save"]))
 {
	$tmp = implode('',$_POST);
	$waiterhash = md5($tmp);
	
	if(isset($_SESSION["waiterhash"]) && $_SESSION["waiterhash"] == $waiterhash)
	{
		
	}
	else
	{
		$_SESSION["waiterhash"] = $waiterhash;
		
		if($_POST["edit"]=="false")
		{
			$result=mysqli_query($conn, "select max(WaiterId) from waiter");
			$row = mysqli_fetch_assoc($result);
			$id=$row["max(WaiterId)"];
			$id=$id+1;
			if(mysqli_query($conn, "insert into waiter(WaiterId,WaiterName,Age,Notes) values(".$id.",'".$_POST["name"]."',".$_POST["age"].",'".$_POST["notes"]."')"))
			{
			$save=true;
			}
			else{
			
			}
		}else
		{
			if(mysqli_query($conn, "update waiter set WaiterName='". $_POST["name"] ."', Age=".$_POST["age"] . ", Notes='". $_POST["notes"] ."' where WaiterId=". $_POST["wid"]))
			{
			$save=true;
			}
			else{
			
			}
		}
	}
}
else if(isset($_GET["edit"]))
{
	$result=mysqli_query($conn, "select * from waiter where WaiterId=".$_GET["edit"]);
	$row = mysqli_fetch_assoc($result);
	$name=$row["WaiterName"];
	$age=$row["Age"];
	$notes=$row["Notes"];
	$wid=$row["WaiterId"];
	$edit="true";
}
else if (isset($_POST['cancel']))
{
 $save=false;
 $edit="false";
 $wid="";
 $name="";
 $age="";
 $notes="";
 
}
 

?>

<html>
<head>
<title>
	Waiter Management
</title>
<script>
	function validate1()
		{
			var msg="";
			
			if(document.getElementById('name').value=="")
			{
				document.getElementById('name').style.border='2px solid red';
				msg=msg+"Name can not be blank\n";				
			}			
			
			if(msg!="")
			{
				alert(msg);
				return false;
			}else
			{
				return true;
				//document.forms["waitersave"].submit();
			}
			
		}
		
		
			
			
		
	</script>
</head>

<body>
<script>
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="current";
	document.getElementById("device").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("reports").className="";
	document.title = "Waiter Management";
</script>
<center>
<form action="waiter.php" method="post" id="waitersave" name="waitersave" >
<input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>"/>
<input type="hidden" name="wid" id="wid" value="<?php echo $wid; ?>" />
<table border="1">
	<caption>
		Waiter Management
	</caption>
	<tr>
	
	<td  align="center" valign="Center" style="width:200px;" >
		 Waiter List
	<?php
		$db=new _database();
		$conn = $db->connect();
		$result2=mysqli_query($conn, "select * from waiter order by WaiterName");
		while($row = mysqli_fetch_assoc($result2))
		{
	?>
		<li><a href="waiter.php?edit=<?php echo $row['WaiterId']; ?>" ><?php echo $row['WaiterName']; ?> </a></li>
		<?php
		}
		?>
	</td valign="Center">
	<td>

	<table>
		<tr>
			<td>
				Name
			</td>
			<td>
				<input type="text" id="name" name="name" value="<?php echo $name; ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				Age
			</td>
			<td>
			<input type="text" id="age" name="age" value="<?php echo $age; ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				Notes
			</td>
			<td>
				<textarea id="notes" name="notes" cols="40" rows="5"  style="resize:none; " ><?php echo $notes; ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<center>
			<input type="Submit" id="save" name="save" value="Save" onclick="javascript: return validate1()"/>
			<?php
			  if($edit=="true")
			  {
			?>
			<input type="Submit" id="delete" name="delete" value="Delete" />
			<?php } ?>
			<input type="Submit" id="cancel" name="cancel" value="Cancel" />
			</center>
			</td>
			
		</tr>
	</table>
		</td>
	</tr>
</table>

	</form>
</center>
</body>
</html>

<?php

?>
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