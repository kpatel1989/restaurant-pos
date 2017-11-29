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
$sectionid=1;
$tablename="";
$xpos=0;
$ypos=0;
$addnew = 1;
$addbutton = 0;
$refresh=true;
$view="table";

if (isset($_SESSION["sectionid"]))
	$sectionid=$_SESSION["sectionid"];
else
	$sectionid=1;
$_SESSION["sectionid"] = $sectionid;

if (isset($_POST['addtable'])) // -----------------------------------------------------------------SAVE
{
	$tmp = implode('addtable',$_POST);
	$tablehash = md5($tmp);
	if(isset($_SESSION["tablehash"]) && $_SESSION["tablehash"] == $tablehash)
	{
		$refresh=true;
	}
	else
	{
		$_SESSION["tablehash"] = $tablehash;
		
		if (isset($_POST['tableid']))
			$tableid = $_POST['tableid'];
		
		if (isset($_POST['tablename']))
			$tablename = $_POST['tablename'];	
		
		if (isset($_POST['view']))
			$view=$_POST['view'];
		
		if (isset($_POST['xpos']))
			$xpos = $_POST['xpos'];
		//$xpos = substr($xpos,0,strpos($xpos,"px"));
		
		if (isset($_POST['ypos']))
			$ypos = $_POST['ypos'];
		//$ypos = substr($ypos,0,strpos($ypos,"px"));
		
		if ($view == "table" )
		{
			$width = 40;
			$height= 40;
		}
		else
		{
			$width = 100;
			$height= 100;
		}
		if ($view=="table")
			$qry="SELECT max(tableid) as 'newid' FROM restable";
		else
			$qry="SELECT max(sectionid) as 'newid' FROM section";
		if ($result = mysqli_query($conn, $qry))
		{
			$row = mysqli_fetch_assoc($result);
			$newid = $row['newid']+1;
			
			if ($view=="table")
				$qry2="INSERT INTO `restable`(`TableId`, `TableName`, `PosX`, `PosY`, `Width`, `Height`, `SectionId`) VALUES (".$newid.",'".$tablename."',".$xpos.",".$ypos.",".$width.",".$height.",".$sectionid.")";
			else
				$qry2="INSERT INTO `section`(`SectionId`, `SectionName`, `PosX`, `PosY`, `Width`, `Height`) VALUES (".$newid.",'".$tablename."',".$xpos.",".$ypos.",".$width.",".$height.")";
			if ($result=mysqli_query($conn, $qry2))
			{
				
			}
			else mysqli_error($conn);
			
		}
	}
}
else if (isset($_POST['save'])) // -----------------------------------------------------------------SAVE
{
	$tmp = implode('save',$_POST);
	$tablehash = md5($tmp);
	if(isset($_SESSION["tablehash"]) && $_SESSION["tablehash"] == $tablehash)
	{
		$refresh=true;
	}
	else
	{
		$_SESSION["tablehash"] = $tablehash;
		
		if (isset($_POST['tableid']))
			$tableid = $_POST['tableid'];
		
		if (isset($_POST['tablename']))
			$tablename = $_POST['tablename'];	
		
		if (isset($_POST['view']))
			$view=$_POST['view'];
		
		if (isset($_POST['xpos']))
			$xpos = $_POST['xpos'];
		$xpos = substr($xpos,0,strpos($xpos,"px"));
		
		if (isset($_POST['ypos']))
			$ypos = $_POST['ypos'];
		$ypos = substr($ypos,0,strpos($ypos,"px"));
		
		if ($view == "table" )
		{
			$width = 40;
			$height= 40;
		}
		else
		{
			$width = 100;
			$height= 100;
		}
		if (isset($_POST['isaddnew']))
				$addnew=$_POST['isaddnew'];
	
		if (isset($_POST['addbutton']))
				$addbutton=$_POST['addbutton'];

		
		if ($view=="table")
			$qry = "UPDATE `restable` SET `TableName`= '".$tablename."',`PosX`=". $xpos .",`PosY`=". $ypos .",`Width`= ". $width ." ,`Height`=". $height .",`SectionId`=". $sectionid." WHERE tableid=". $tableid;
		else
			$qry = "UPDATE `section` SET `sectionName`= '".$tablename."',`PosX`=". $xpos .",`PosY`=". $ypos .",`Width`= ". $width ." ,`Height`=". $height ." WHERE sectionid=". $tableid;
		try{
			$result = mysqli_query($conn, $qry);
		}	
		catch (Exception $e)
		{
			
		}

		
		$tablename="";
		$xpos="";
		$ypos="";
		$addnew = 1;
		$addbutton = 2;
		unset($_POST['save']);
	}
	
}

else if (isset($_POST['delete']))
{
	$tmp = implode('delete',$_POST);
	$tablehash = md5($tmp);
	if(isset($_SESSION["tablehash"]) && $_SESSION["tablehash"] == $tablehash)
	{
		 $refresh=true;
	}
	else
	{
		$_SESSION["tablehash"] = $tablehash;
		if (isset($_POST['tableid']))
			$tableid = $_POST['tableid'];
			
		if ($view=="table")
			$qry = "delete from restable WHERE tableid=". $tableid;
		else
			$qry = "delete from section WHERE sectionid=". $tableid;
		try{
			$result = mysqli_query($conn, $qry);
		}	
		catch (Exception $e)
		{
			  $e->getMessage();
		}	
	}
	$tablename="";
	$xpos="";
	$ypos="";
	$addnew = 1;
	$addbutton = 2;
	
	unset($_POST['delete']);
}
else if (isset($_POST['changeview']))
{
	$tablename="";
	$xpos="";
	$ypos="";
	$addnew = 1;
	$addbutton = 2;
	if (isset($_POST['view']))
		$view=$_POST['view'];
		
	if ($view=="table")
		$view = "section";
	else
		$view = "table";
	
}
else if (isset($_POST['changesection']))
{
	$tablename="";
	$xpos="";
	$ypos="";
	$addnew = 1;
	$addbutton = 2;
	
	if (isset($_POST['sectionid']))
		$sectionid=$_POST['sectionid'];
	$_SESSION['sectionid']=$sectionid;
	
}
else if (isset($_POST['data']))
{
	
}
else
{

}
?>

<html>

<head>
<title> LIVE SCREEN MANAGEMENT </title>
<script src="jquery.js">
	</script>
<script language = "javascript">
var drag=false;
var nodrag = "none";
var newid = 5;
var mousePosX, mousePosY;
function down(event)
{
	var obj = event.target;
drag=true;
mousePosX = event.pageX;
mousePosY = event.pageY;
nodrag=obj.id;
}

function up(obj)
{
	drag=false;
	nodrag="none";
	if (document.getElementById("addbutton").value!=1)
	{
		var tableid = obj.id;
		var xpos=document.getElementById("xpos").value;
		var ypos=document.getElementById("ypos").value;
		var view =document.getElementById("view").value;
		var form = $('#screenmgmt');
		$.ajax({
			 type: "POST",
			 url: "savetableajax.php",
			 data: "tableid="+tableid+"&xpos="+xpos+"&ypos="+ypos+"&view="+view, 
			 success: function(data) {
			 }
		});
	}
}

function move(e)
{
	if (drag==true)
	{

		x= parseInt(e.target.style.left) + (e.pageX-mousePosX);
		y= parseInt(e.target.style.top) + (e.pageY-mousePosY);

		mousePosX = event.pageX;
		mousePosY = event.pageY;

		coor="Coordinates: (" + x + "," + y + ")";
		
		document.getElementById("xpos").value = x;
		document.getElementById("ypos").value = y;

		
		var id = e.target.id;
		if (nodrag==id) 
		{
			if (x > 10 && x <650)
				e.target.style.left = x+'px';
			if (y < 680 && y > 10)
				e.target.style.top = y+'px';
			
		}
		
		
	}
}

function addnew()
{
	var element = document.createElement("input");
    element.setAttribute("type", "button");
	v1 = document.getElementById("view").value;
	if (v1 == "table")
	{
		element.style.width="40px";
		element.style.height="40px";
	}
	else
	{
		element.style.width="100px";
		element.style.height="100px";
	}
	element.style.left="20px";
	element.style.top="20px";
	element.style.position="absolute";
	element.setAttribute("onmousedown", "down(event)");
	element.setAttribute("onmouseup", "up(this)");
	element.setAttribute("onmousemove", "move(event)");
	element.setAttribute("onclick", "showdetails(this)");
	newid = parseInt(newid) + 1;
	element.id = newid;
    document.getElementById("screen").appendChild(element);
	
	document.getElementById("xpos").value = 20;
	document.getElementById("ypos").value = 20;
	
	document.getElementById("isaddnew").value = 1;
	document.getElementById("addbutton").value = 1;
}

function showdetails(obj)
{
	document.getElementById("tableid").value = obj.id;
	document.getElementById("xpos").value = obj.style.left;
	document.getElementById("ypos").value = obj.style.top;
	document.getElementById("tablename").value = obj.name ;
	document.getElementById("isaddnew").value = 2;
	document.getElementById("tablename").style.border='0';
	document.getElementById("save").style.display="inline";
	document.getElementById("delete").style.display="inline";
	document.getElementById("addtable").style.display="none";
}


function changeselectsection(obj)
{
	id = obj.options[obj.selectedIndex].value;
	document.getElementById("sectionid").value = id;
}

function validatetablename()
{
	if(document.getElementById("tablename").value == "" )
	{
		
		document.getElementById("tablename").style.border='2px solid red';
		alert ("Name field is invalid." );
		return false;
	}
	else
	{
		document.getElementById("tablename").style.border='0';
		addnew();
		return true;
	}
}
function clearf()
{
	
	document.getElementById("tableid").value="";
	document.getElementById("tablename").value="";
	document.getElementById("xpos").value="";
	document.getElementById("ypos").value="";
	document.getElementById("isaddnew").value="1";
	document.getElementById("save").style.display="none";
	document.getElementById("delete").style.display="none";
	document.getElementById("addtable").style.display="inline";
}

</script>
</head>

<body>

<script>
	document.title = "Live Screen Management";
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("livescreenmgmt").className="current";
	document.getElementById("reports").className="";
</script>
<form name="screenmgmt" id="screenmgmt" action="LiveScreenManagement.php" method="post" >


<center>
<div style="width:700px">
	<div style="height:870;width:700;">
		<div style="width:600;height:120">
			<div style="width:350;float:left">
				<input type="submit" value="Change to <?php if($view=="table") echo "section"; else echo "table"; ?> View" name="changeview" style="width: 200;"/>
				<br>
				<?php if ($view=="table") { ?>
				Current Section : <select name="sections" id="sections" onchange="changeselectsection(this)"> <?php 
								
				$qry = "SELECT sectionid,sectionname from section";
					if ($result = mysqli_query($conn, $qry))
					{
						while ($row = mysqli_fetch_assoc($result))
						{  ?>
							<option value="<?php echo $row['sectionid']; ?>"> <?php echo $row['sectionname']; ?> </option>
						<?php
						}
					} else {
					}
					?>
				</select> 
				
				<input type="submit" value="change" name="changesection"/>
				<?php } ?>
			</div>
			<div style="width:250px;float:right;">
				<input type="hidden" name="tableid" id="tableid" value="" />
				<input type="hidden" name="sectionid" id="sectionid" value="<?php echo $sectionid; ?>" />
				<input type="hidden" name="isaddnew" id="isaddnew" value="<?php echo $addnew; ?>" />
				<input type="hidden" name="addbutton" id="addbutton" value="<?php echo $addbutton; ?>" />
				<input type="hidden" name="view" id="view" value="<?php echo $view?>" />
				<input type="hidden" id="xpos" name="xpos" />
				<input type="hidden" id="ypos" name="ypos" />
				
				<div style="background-color:#DBDBDB;">
					<table> <tr> <td> <?php if($view=="table") echo "Table"; else echo "Section"; ?> Name </td> </tr>
						<tr> <td> <input type="text" id="tablename"  name="tablename" /> </td> </tr>
						<tr><td colspan=2>
								<input type="submit" value="save <?php if($view=="table") echo "Table"; else echo "Section"; ?>" name="save" id="save" style="width: 80;"/>
								<input type="submit" value="delete <?php if($view=="table") echo "Table"; else echo "Section"; ?>" name="delete" id="delete"  style="width: 90;"/>
								<input type="submit" value="Add new <?php if($view=="table") echo "Table"; else echo "Section"; ?>" name="addtable" id="addtable" onclick="javascript:return validatetablename()"  style="width: 105;"/>
								<input type="button" value="cancel" name="cancel" onclick="clearf()"/>
								<script>
									
								</script>
								<script language="javascript">
									if (document.getElementById("isaddnew").value == 2)
									{
										document.getElementById("save").style.display="inline";
										document.getElementById("delete").style.display="inline";
										document.getElementById("addtable").style.display="none";
									}
									else
									{
										document.getElementById("save").style.display="none";
										document.getElementById("delete").style.display="none";
										document.getElementById("addtable").style.display="inline";
									}
								</script>
							</td></tr>
					</table>
				</div>
				<input type="button" value="Publish Screen" onclick="window.open('LiveScreen.php')" style="width: 95;"/>
			</div>
		</div>
		<div id="screen" style="background-color:#294145;height:740;width:700;position:relative">
			<?php 
				if ($view=="table")
				{
					$qry = "SELECT tableid,tablename,posx,posy,width,height,sectionid from restable where sectionid = " .$sectionid;
					
					if ($result = mysqli_query($conn, $qry))
					{
						while ($row = mysqli_fetch_assoc($result))
						{  ?>
							<input type="button"  style="width:<?php echo $row['width'] ?>;height:<?php echo $row['height']?>;position:absolute;left:<?php echo $row['posx']?>;top:<?php echo $row['posy']?>"  value="" id = "<?php echo $row['tableid']?>" onmouseup="up(this)" onmousedown="down(event)" onmousemove="move(event)" onclick="showdetails(this)" name="<?php echo $row['tablename']?> "/>						
							
						<?php
						}
					} else {
						echo mysqli_error($conn);
					}
				}
				else
				{
					$qry = "SELECT sectionid,sectionname,posx,posy,width,height from section";
					
					if ($result = mysqli_query($conn, $qry))
					{
						while ($row = mysqli_fetch_assoc($result))
						{  ?>
							
							<input type="button" name="<?php echo $row['sectionname']?> " style="width:<?php echo $row['width'] ?>;height:<?php echo $row['height']?>;position:absolute;left:<?php echo $row['posx']?>;top:<?php echo $row['posy']?>"  value="" onmouseup="up(this)" onmousedown="down(event)" onmousemove="move(event)" onclick="showdetails(this)" id = "<?php echo $row['sectionid']?>" />						
							
						<?php
						}
					} else {
						echo mysqli_error($conn);
					}
				}
			?>
		</div>
	</div>
	
</div>
<script language="javascript"> 
	document.getElementById("sections").selectedIndex = document.getElementById("sectionid").value-1;
</script>
<center>
</form>
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
</body></html>