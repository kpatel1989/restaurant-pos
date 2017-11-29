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

if (isset($_SESSION["sectionid"]))
	$sectionid=$_SESSION["sectionid"];
else
	$sectionid=1;
$_SESSION["sectionid"] = $sectionid;

if (isset($_POST['changesection']))
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

?>

<html>
<head>
<title> LIVE SCREEN </title>
<script src="jquery.min.js">
</script>
<script language="javascript">
	function changeselectsection(obj)
	{
		id = obj.options[obj.selectedIndex].value;
		document.getElementById("sectionid").value = id;
	}
	function sendwaiter(obj)
	{
		value = obj.options[obj.selectedIndex].value;
		obj.style.background="";
		
		request = $.ajax({
			url:"android_waiterrequests.php",
			type:"get",
			data:"sendwaiter="+value,
		});
		
		request.done(function(response,textstatus,asd){
		});
	}
	function myFunction()
	{
		setInterval(function(){test1()},5000);
	}

	
	function test1(){
		 var request = $.ajax({
		        url: "android_order.php",
		        type: "post",
		        data: "name=aa"
		    });

		    // callback handler that will be called on success
		    request.done(function (response, textStatus, jqXHR){
				var ele = document.forms['livescreen']['tablename'];
				for (i=0; i<ele.length; i++) {
					ele[i].style.background="";
					ele[i].style.color="white";
					ele[i].value="";
					

				}
				//alert(response);
				var seper = response.split("^");
				document.getElementById("sidebar").innerHTML=seper[1];
				document.getElementById("waiterrequests").innerHTML=seper[2];
				
				if(document.getElementById("waiterrequests").options.length>1)
					document.getElementById("waiterrequests").style.background = "red";
				else
					document.getElementById("waiterrequests").style.background = "";
				//select.options[select.options.length] = new Option("Option text", "optionValue");
			    var initokens=seper[0].split("*");
				
					for(var k=0;k<initokens.length-1;k++){
						var tokens=initokens[k].split("-");
						
						var qty = tokens[0];
						var tableid = tokens[1];
						var status = tokens[2];
						document.getElementById(tableid).value=qty.trim();

						if (status==0)
						{
							
							document.getElementById(tableid).style.background = "";
							document.getElementById(tableid).style.backgroundImage = "url('./red.gif')";
						}
						else if (status==1 || status == 2 || status == 9)
						{
							document.getElementById(tableid).style.backgroundImage = "";
							document.getElementById(tableid).style.background = "yellow";
							document.getElementById(tableid).style.color="black";
						}
						else if (status==3)
						{
							document.getElementById(tableid).style.backgroundImage = "";
							document.getElementById(tableid).style.background = "green";
						}
						else if (status==4) 
						{
							document.getElementById(tableid).style.backgroundImage = "url('./yellow.gif')";
							document.getElementById(tableid).value="";
						}
						
						document.getElementById(tableid).style.backgroundSize = 1;
					}
			    
		    });

		    // callback handler that will be called on failure
		    request.fail(function (jqXHR, textStatus, errorThrown){
		        
		    });
		   
	}
	function showorder(obj)
	{
		window.open("orderitemdetails.php?tableid="+obj.id);
	}
</script>
</head>

<body onload="myFunction(this)">
<script>
	document.title = "LIVE SCREEN";
	document.getElementById("menu").className="";
	document.getElementById("waiter").className="";
	document.getElementById("device").className="";
	document.getElementById("userdetail").className="";
	document.getElementById("themes").className="";
	document.getElementById("settings").className="";
	document.getElementById("livescreenmgmt").className="";
	document.getElementById("addorder").style.color="";
	document.getElementById("search").style.color="";
	document.getElementById("reports").className="";
	document.getElementById("livescreen").style.color="#5D99A3";


</script>


<center>
<div style="width:700px;height:800px;background-color:#DDDDDD;">
	<div style="height:720;width:700;float:left;">
<form name="livescreen" id="livescreen" action="LiveScreen.php" method="post" >
<input type="hidden" name="sectionid" id="sectionid" value="<?php echo $sectionid; ?>" />
		<div><div style=" float: left; margin-left: 70; ">
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
		</div>
			<div>
				Waiter Requests : <select id="waiterrequests" name="waiterrequests" onchange="sendwaiter(this)"><option> Select a table </option></select>
		
			</div>
		</div>
		
		<div id="screen" style="background-color:#294145;height:700;width:650;position:relative;margin-top:20" >
			<?php 
				
				$qry = "SELECT tableid,tablename,posx,posy,width,height,sectionid from restable where sectionid = " .$sectionid;
				
				if ($result = mysqli_query($conn, $qry))
				{
					while ($row = mysqli_fetch_assoc($result))
					{  ?>
						<div style="height:<?php echo $row['height']?>;position:absolute;left:<?php echo $row['posx']?>;top:<?php echo $row['posy']?>">
							<input type="button" name="tablename" id = "<?php echo $row['tableid']?>" style="width:<?php echo $row['width'] ?>;height:<?php echo $row['height']?>;"  value="" onclick="showorder(this)"  readonly/>
							<br><label for="tablename" ><font color="#3AACB1"> <?php echo $row['tablename']?> </font></label>
						</div>						
					<?php
					}
				} else {
					echo mysqli_error($conn);
				}
			
				
			?>
		</div>
		</form>
	</div>
</div>

</center>

<script language="javascript"> 
	document.getElementById("sections").selectedIndex = document.getElementById("sectionid").value-1;
</script>
</body>
</html>
</div>
<? // MPage::EndBlock("body") ?>

<? // MPage::BeginBlock() ?>

<?php include "sidebar.php"; ?>
<? // MPage::EndBlock("sidebar") ?>

<? // MPage::BeginBlock() ?>

<?php include "foot.php"; ?>
<?php
}
else
{
header("Location: /");
}

?>
<? // MPage::EndBlock("footer") ?>