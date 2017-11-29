<?php
if(!isset($_COOKIE["PHPSESSID"]))
{
  session_start();
}$itemname='';
$des='';
$ing='';
$isactive='';
$prep='';
$price='';
$duration='';
$notes='';
$images= '';
$addNew ='';
$EItem='';
$id = '';
$category='';
$cid= '';
$save=false;
$_SESSION['saved']=true;																												//save
if (isset($_POST['save']))
{
	require "class._database.php";
	$db=new _database();
	$conn = $db->connect();
	//fetch values
	if (isset($_POST['itemid']))
		$id = $_POST['itemid'];

	if (isset($_POST['cid']))
		$cid = $_POST['cid'];

	if (isset($_POST['itemname']))
		$itemname = $_POST['itemname'];
		
	$isactive = isset($_POST['isactive']) && $_POST['isactive'] ? '1' : '0' ;
	$is_active=$isactive;
	if($isactive == '1') 
		$isactive = 'checked';
	else
		$isactive = 'unchecked';
		
	if (isset($_POST['des']))
		$des = $_POST['des'];
		
	if (isset($_POST['ing']))
		$ing = $_POST['ing'];

	if (isset($_POST['prep']))
		$prep = $_POST['prep'];

	if (isset($_POST['price']))
		$price = $_POST['price'];

	if (isset($_POST['duration']))
		$duration = $_POST['duration'];

	if (isset($_POST['notes']))
		$notes = $_POST['notes'];

	if (isset($_POST['Category']))
		$category = $_POST['Category'];

	if (isset($_POST['isAddNew']))
		$addNew=$_POST['isAddNew'];

	if (isset($_POST['img']))
		$images=$_POST['img'];
	$no=0;	
	$img_arr = array();
	$image = strtok($images,',');
	$img_arr[$no] = $image;
	$no++;
	while ($image = strtok(','))
	{
		$img_arr[$no] = $image;
		$no++;
	}
	$filename ='';
	for ($i=0;$i<$no;$i++)
	{
		$image = $img_arr[$i];
		$imgname = strtok($image,'/');
		while($imgname=strtok('/'))
			$filename = $imgname;
			
		if (file_exists("temp/".$filename) && $filename!="")
		{
			copy("temp/".$filename,"upload/items/". $filename);
			unlink("temp/".$filename);
		}
	}
	
	if ($addNew=="1")
	{
		$qry="SELECT max(items.item_id) as 'Newid' FROM items";
		if ($result = mysqli_query($conn, $qry))
		{
			$row = mysqli_fetch_assoc($result);
			$id = $row['Newid']+1;
			$qry="INSERT INTO items (`item_id` ,`item_name` ,`Category_id` ,`description` ,`ingredients` ,`preperation` ,`price` ,`is_active` ,`approx_duration` ,`notes`,`images`) VALUES (".$id.",'".$itemname."',".$cid.",'".$des."','".$ing."','".$prep."',".$price.",".$is_active.",'".$duration."','".$notes."','".$images."')";	
			if ($result = mysqli_query($conn, $qry))
				$save=true;
				//header("Location: /index.php");
			else
				echo mysqli_error($conn);
		}
		else
			echo mysqli_error($conn);
	}
	else
	{
		$qry = "UPDATE `items` SET `item_name`='".$itemname."',`Category_id`=".$cid.",`description`='".$des."',`ingredients`='".$ing."',`preperation`='".$prep."',`price`=".$price.",`images`='".$images."',`is_active`=".$is_active.",`approx_duration`='".$duration."',`notes`='".$notes."' WHERE item_id=".$id;
		if ($result = mysqli_query($conn, $qry))
			$save=true;
			//header("Location: /index.php");
		else
				echo mysqli_error($conn);
	}
	if (isset($_SESSION['saved']))
		unset($_SESSION['saved']);
	
}
																												//cancel
elseif (isset($_POST['cancel']))
{
	if (isset($_POST['img']))
		$images=$_POST['img'];
	$no=0;
	$img_arr = array();
	$image = strtok($images,',');
	$img_arr[$no] = $image;
	$no++;
	while ($image = strtok(','))
	{
		$img_arr[$no] = $image;
		$no++;
	}
	$filename ='';
	
	for ($i=0;$i<$no;$i++)
	{
		$image = $img_arr[$i];
		$imgname = strtok($image,'/');
		
		while($imgname=strtok('/'))
			$filename = $imgname;
		
		if (file_exists("temp/".$filename))
			unlink("temp/".$filename);

	}
	if(isset($_SESSION['saved']))
		unset($_SESSION['saved']);
	//header("Location: /index.php");
	//echo 'cancel';
	$save=true;
}

else if (isset($_POST['delete']))
{
include "class._database.php";
$db=new _database();
$conn = $db->connect();

	//echo 'Delete';
	if (isset($_POST['itemid']))
		$itemid = $_POST['itemid'];
		
	$qry = "DELETE from items where item_id=" .$itemid;
	if ($result = mysqli_query($conn, $qry))
	{
		$save=true;
		//echo "Deleted";
	}
	else
		//echo mysqli_error($conn);
	$save=true;
}
																												//image upload 
elseif (isset($_POST['images']) && !empty($_POST['images']))
{
	include "class._database.php";
		$db=new _database();
		$conn = $db->connect();
	if (isset($_POST['cid']))
		$cid = $_POST['cid'];
		
	if (isset($_POST['itemid']))
		$id = $_POST['itemid'];
		
	if (isset($_POST['itemname']))
		$itemname = $_POST['itemname'];
	
	$isactive = isset($_POST['isactive']) && $_POST['isactive'] ? '1' : '0' ;
	if($isactive == '1') 
		$isactive = 'checked';
	else
		$isactive = 'unchecked';
		
	if (isset($_POST['des']))
		$des = $_POST['des'];
		
	if (isset($_POST['ing']))
		$ing = $_POST['ing'];
		
	if (isset($_POST['prep']))
		$prep = $_POST['prep'];
	
	if (isset($_POST['category']))
		$category = $_POST['category'];
	$qry = 'SELECT name from categories where id=' . $cid;
	if ($result = mysqli_query($conn, $qry))
	{
		$row = mysqli_fetch_assoc($result);
		$category = $row['name'];
	} else {
		echo mysqli_error($conn);
	}
	
	if (isset($_POST['price']))
		$price = $_POST['price'];
		
	if (isset($_POST['duration']))
		$duration = $_POST['duration'];
		
	if (isset($_POST['notes']))
		$notes = $_POST['notes'];
		
	if (isset($_POST['isAddNew']))
		$addNew=$_POST['isAddNew'];

	if (isset($_POST['img']))
		$images=$_POST['img'];
		
	if ($addNew==1)
	{	$qry="SELECT max(item_id) as 'id' FROM items "; 
		if ($result = mysqli_query($conn, $qry))
		{
			$row = mysqli_fetch_assoc($result);
			$id = $row['id']+1;
			$_SESSION['photoItemId']= $id;
			
		}
		else
			echo mysqli_error($conn);
	}
	
	
	$no=1;
	$image = strtok($images,',');
	while ($image!=false)
	{
		$no++;
		$image = strtok(',');
	} 
	if ($no+1 >5)
		echo "Max. Image for an item exceeded";
	else
	{
		//include "upload_item.php";
		//--------------------------------------------------------
		$extension = end(explode(".", $_FILES["file"]["name"]));
		$extension=strtolower($extension);
		$allowedExts = array("jpg", "jpeg", "png");

		if (in_array($extension, $allowedExts))
		  {
		  if ($_FILES["file"]["error"] > 0)
			{
			echo "Error uploading file. Error Code: " . $_FILES["file"]["error"] . "<br>";
			}
		  else
			{
				if (file_exists("temp/" . $id . "_" . $no .".". $extension))
				{	
					unlink("temp/". $id   . "_" . $no .".". $extension);
				}
				
				move_uploaded_file($_FILES["file"]["tmp_name"], "temp/" . $id . "_" . $no . "." . $extension);
				$images = $images . ',upload/items/' . $id . "_" . $no . "." . $extension;
				$_SESSION['saved']=false;
			}
		}
		else
		{
			echo "Invalid file";
		}
		
		
	}
}
																													//addnew
elseif(isset($_SESSION['addnew'])) {
	require "class._database.php";
	$db=new _database();
	$conn = $db->connect();
	$addNew = $_SESSION['addn\ew'];
	unset($_SESSION['addnew']);
	//echo 'Add New Item';
}
																													//Existing Item
elseif(isset($_SESSION['Eitem'])) {
	include "class._database.php";
	$db=new _database();
	$conn = $db->connect();
	$EItem = $_SESSION['Eitem'];
	unset($_SESSION['Eitem']);
}
																													//Set itemId and Cid
if (isset($_SESSION['itemid']))
{
	

	if ($EItem==1)
	{
		//include "class._database.php";
		$db=new _database();
		$conn = $db->connect();
		$id = $_SESSION['itemid'];
		$id = substr($id,0,strpos($id,'^'));
		$qry = "SELECT * FROM items where item_id=".$id;
		if ($result = mysqli_query($conn, $qry))
		{
			$row = mysqli_fetch_assoc($result);
			$id = $row['item_id'];
			$cid = $row['Category_id'];
			$itemname=$row['item_name'];
			$des= $row['description'];
			$ing=$row['ingredients'];	
			$isactive=$row['is_active'];
			$prep=$row['preperation'];
			$price=$row['price'];
			$duration=$row['approx_duration'];
			$notes=$row['notes'];
			$images=$row['images'];
			$addNew ='';
			if($isactive == '1') 
				$isactive = 'checked';
			else
				$isactive = 'unchecked';
		}
	}
	elseif ($addNew==1)
	{
		$cid = $_SESSION['itemid'];
		$cid = substr($cid,0,strpos($cid,'I'));
		
	}
	unset($_SESSION['itemid']);
	$qry = 'SELECT name from categories where id=' . $cid;
	if ($result = mysqli_query($conn, $qry))
	{
		$row = mysqli_fetch_assoc($result);
		$category = $row['name'];
	} else {
		echo mysqli_error($conn);
	}

}

																									//html
?>
<html>
<head>
<script language="javascript">
	function upload()
	{
		alert("upload");
		document.getElementById("items").submit();
	}
	
	function displayCategory()
	{
		
		document.getElementById("catDiv").style.display = "block";
	}
	function changeCat(obj)
	{
		var catText = obj.options[obj.selectedIndex].text;
		if (catText != "Select a Category")
		{
			document.getElementById("catDiv").style.display = "none";
			document.getElementById("Category").value= catText;
			document.getElementById("cid").value= obj.options[obj.selectedIndex].value;
		}
	}
	
</script>
<link rel="stylesheet" type="text/css" href="css/style.css" />

</head>
<body>
<?php
if ($save){
?>
<script>
	window.top.location.href = window.top.location.href;
</script>
<?php }
?>
<form name="items" id="items" action="ItemDetails.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="isAddNew" value="<?php echo $addNew ?>" />
<input type="hidden" name="itemid" value="<?php echo $id; ?>"/>
<input type="hidden" name="cid" id="cid" value="<?php echo $cid;	?>" />	
<input type="hidden" name="img" value="<?php echo $images ?>" />
<table>
		<caption> Subcategory </caption>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Item Name   </td>
			<td style=" border: 1px solid #c3d7db; "><input type="text" size="20" id="itemname" name="itemname" value = "<?php echo $itemname; ?>"/>
			</td>
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> <input type="checkbox" name="isactive" id="isactive" value="1" <?php echo $isactive; ?>/> Is Available in menu.. ?</td>
			<td style=" border: 1px solid #c3d7db; ">  </td>
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Description </td>
			<td style=" border: 1px solid #c3d7db; "><textarea id="des" name="des" width="200px" height="100px" style="resize:none"><?php echo $des; ?></textarea></td>
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Ingredients </td>
			<td style=" border: 1px solid #c3d7db; "><textarea id="ing" name="ing" width="200px" height="100px" style="resize:none"><?php echo $ing; ?></textarea></td>
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Preperation </td>
			<td style=" border: 1px solid #c3d7db; "><textarea id="preperation" name="prep" width="200px" height="100px" style="resize:none"><?php echo $prep; ?></textarea></td>
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Price </td>
			<td style=" border: 1px solid #c3d7db; "><input type="number" size="20" name="price" id="price" value="<?php echo $price; ?>"/></td>
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Belongs to : </td> 
			<td style=" border: 1px solid #c3d7db; ">
				<input type="text" name="Category" id="Category" value="<?php echo $category;?>" disabled/>
				<a href="#" onclick="displayCategory()"> change ... ? </a>
				<div name="catDiv" id="catDiv" style="display:none">
					<select name="categories" id="categories" onchange="changeCat(this)">
					<option selected="selected">Select a Category</option>
					<?php
						//require "class._database.php";
						$db=new _database();
						$conn = $db->connect();
						$qry = 'SELECT id,name from categories where parent_id!=0';
						
 						if ($result = mysqli_query($conn, $qry))
						{
							while ($row = mysqli_fetch_assoc($result))
							{  ?>
								<option value="<?php echo $row['id']; ?>"> <?php echo $row['name']; ?> </option>
							<?php
							}
						} else {
							?>
								<option> <?php echo mysqli_error($conn); ?> </option>
							<?php
						}
						?>
						
					</select>
				</div>
			</td>
			
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Preperation duration </td> 
			<td style=" border: 1px solid #c3d7db; "><input type="text" size="20" name="duration" value="<?php echo $duration; ?>"/> </td>
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Notes </td>
			<td style=" border: 1px solid #c3d7db; "><textarea id="notes" name="notes" width="200px" height="100px" style="resize:none"><?php echo $notes; ?></textarea></td>
		</tr>
		<tr>
			<td style=" border: 1px solid #c3d7db; "> Item Images </td>
			<td style=" border: 1px solid #c3d7db; ">
				<table><tr><td>
				<?php
					//echo "image" .$images . "###";
					$image = strtok($images,',');
					if ($image!="")
					{ ?> <img src="<?php echo $image ?>" width="50px" height="50px"/>
					<?php } while ($image = strtok(','))
					{ 	?><img src="<?php echo $image ?>" width="50px" height="50px"/>
				<?php } ?>
				</td></tr></table>
			</td>
			<td><font color="red">
				<?php
					if (isset($_SESSION['saved'])) {
						if ($_SESSION['saved']==false) {
							?><label> <?php echo 'Images are stored in a temporary folder.<br> Please Click Save to Save it permanently!';
							?></label> <?php 
						}
					}
				?>
			</font>
			</td>
			
		</tr>
		<tr>
			<td colspan=2 style=" border: 1px solid #c3d7db; ">
					<input type="file" name="file" id="file"><br>
					<input type="Submit" name="images" value="Submit" onclick="uploadImg()">
				
			</td>
		</tr>
		<tr>
			<td colspan=2 style=" border: 1px solid #c3d7db; "><center> <input type="Submit" value="Save" name="save"/><input type="Submit" value="Cancel" name="cancel"/><input type="Submit" value="Delete" name="delete"/> </center></td>
		</tr>
</table>

</form>
</body></html>