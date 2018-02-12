<?php
$save=false;
	if(isset($_POST["name"]))
	{
	
		include "class._database.php";
		$db=new _database();
		$conn = $db->connect();
		if($_POST["flag"]=="existing"){

			$ext=substr($_POST["img"],strpos($_POST["img"],'.')+1);
			//echo "id=".$_POST['id'];
			if (file_exists($_POST["img"])){
				if(copy($_POST["img"],"upload/category/".$_POST["id"].".".$ext))
				{
					unlink($_POST["img"]);		
					mysqli_query($conn, "update categories set name='". $_POST["name"] ."', title='". $_POST["description"] . "', image='" . "upload/category/".$_POST["id"] .".".$ext. "' where id=" . $_POST["id"]);
				}else
				{
				//echo "not excuted";
				if (mysqli_query($conn, "update categories set name='". $_POST["name"] ."', title='". $_POST["description"] . "' where id=" . $_POST["id"]))
				{}
				else
					echo mysqli_error($conn);
				//header('location:/index.php');
				$save=true;
				}
			}
			if (isset($_POST['delete']))
			{
				if (mysqli_query($conn, "delete from categories where id = ". $_POST['id']))
				{}
				else
					echo mysqli_error($conn);
			}
		}else
		{
			$ext=substr($_POST["img"],strpos($_POST["img"],'.')+1);
			$result2=mysqli_query($conn, "select max(id) from categories");
			$row = mysqli_fetch_assoc($result2);
			$id=$row["max(id)"];
			$id=$id+1;
			//echo $id;
			
			if(copy($_POST["img"],"upload/category/".$id.".".$ext))
			{
			unlink($_POST["img"]);		
			if (mysqli_query($conn, "insert into categories(name,title,image,parent_id,id) values('". $_POST["name"] ."','". $_POST["description"] . "','" . "upload/category/".$id . "'," . $_POST["parent_id"]. "," . $id.")"))
			{}
			else
				echo mysqli_error($conn);
			//header('location:/index.php');
			
			}
			$save=true;
		}
		
			//echo "save=".$save;
			if ($save==true){
			?>
				<script>
				
				window.top.location.href = window.top.location.href;
			</script>
			<?php }
			
	}else{
?>

<?php
$name="";
$description="";
$image="";
$id="";
$parent_id="";

$idd=$_SESSION['id'];

if($_SESSION["cat_flag"]=="existing"){
	$parent_id=substr($idd,strpos($idd,'@')+1,strlen($idd)-1);
	$name=substr($idd,0,strpos($idd,'@'));

	$db=new _database();
	$conn = $db->connect();
	$result2=mysqli_query($conn, "select * from categories where name='".$name."'");
	$row = mysqli_fetch_assoc($result2);
	$description=$row['title'];
	$image=$row['image'];
	$id=$row['id'];
}else if($_SESSION["cat_flag"]=="new root")
{
	$parent_id="0";
}
else if($_SESSION["cat_flag"]=="new sub")
{
	//echo "111111"."<br/><br/>";
	$parent_id=$idd;
}



if($parent_id != 0)
{
//include "class._database.php";
$db=new _database();
$conn = $db->connect();
$result2=mysqli_query($conn, "select name from categories where id=".$parent_id);
$row = mysqli_fetch_assoc($result2);
$parent_name=$row['name'];
}
else
	$parent_name="";
	
 if($_SESSION["cat_flag"]=="existing")
			echo "   " . $parent_name. " >> " . $name;
  else
			echo "   " . $parent_name. " >> Add new" ;
			
?>

<html>

<head>
	<title>
		Category/SubCategory
	</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script>
	

	var fnm;
		function upload(val)
		{	
		
			// document.catimg.submit();
			//var fnm=val.split("/");
			
			fnm=val.substring(val.lastIndexOf('\\') + 1);
			
			document.forms["catimg"].submit();
			//document.getElementById('workFrame').style.display="inline";
			var x=setInterval(function(){
				document.getElementById('upimg').src="temp/" + fnm ;
				document.getElementById('img').value="temp/" + fnm ;
			},500);
			 
			
			//
		}
		
		function validate1()
		{
			var msg="";
			if(document.getElementById('img').value=="")
			{
				document.getElementById('img').style.border='2px solid red';
				msg=msg+"please select an image\n";				
			}
			if(document.getElementById('name').value=="")
			{
				document.getElementById('name').style.border='2px solid red';
				msg=msg+"name can not be blank\n";				
			}			
			
			
			if(msg!="")
			{
				alert(msg);
			}else
			{
				document.forms["catsave"].submit();
			}
			
		}
		
	</script>
</head>
<body>

<br/><br/><br/>
	
	<table>
		<tr>
			<td width="150px">
				Category Image : 
			</td>
			<td>
				<img src="<?php echo $image; ?>" id="upimg" width="50px" height="50px"/>
				<form action="upload.php" method="post" id="catimg" name="catimg"
				enctype="multipart/form-data" target="workFrame">			
				<input type="file" name="file" id="file" onchange="upload(this.value)">
				<iframe id="workFrame" src="about:blank" style="display:none; width:200px; height:25px; border-style:none; " scrolling="no" ></iframe>			
				</form>	
			</td>
		</tr>
		
	</table>
	
	<form action="cat.php" method="post" id="catsave" name="catsave" >
	<input type="hidden" id="flag" name="flag" value="<?php echo $_SESSION["cat_flag"]; ?>"/>
	<input type="hidden" id="parent_id" name="parent_id" value="<?php echo $parent_id; ?>"/>
	<input type="hidden" id="img" name="img" value="<?php echo $image; ?>"/>
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>
	<table>
		<tr>
			<td width="150px">
				Category Name:
			</td>
			<td>
				<input type="text" id="name" name="name" value="<?php echo $name; ?>"/>
			</td>
		</tr>
		<tr>
			<td width="150px">
				Category Description:
			</td>
			<td>
				
				<textarea id="description" name="description" cols="40" rows="5"  style="resize:none; " ><?php echo $description; ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<center>
			
			<input type="button" id="save" name="save" value="Save" onclick="validate1()"/>
			<input type="submit" id="delete" name="delete" value="Delete"/>
			<input type="Button" id="cancel" value="Cancel" onclick="hidd()"/>
			
			</center>
			</td>
			
		</tr>
	</table>
	</form>
</body>
<html>
<?php } ?>