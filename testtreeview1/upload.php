
<font color="red">
<?php
echo $_REQUEST["file"]["name"];
$allowedExts = array("jpg", "jpeg", "gif", "png");
$extension = end(explode(".", $_FILES["file"]["name"]));
//(($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/jpg")) && 
$extension=strtolower($extension);

if (in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
   echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
		//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		//echo "Type: " . $_FILES["file"]["type"] . "<br>";
		//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    if (file_exists("temp/" . $_FILES["file"]["name"]))
      {
		echo "rename file";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "temp/" . $_FILES["file"]["name"]);
	  echo "uploaded successfully";
				$img_nm= "temp/" . $_FILES["file"]["name"];
		
      }
    }
  }
else
  {
		echo "Invalid file";
  }
?>

</font>
<img src="<?php echo $img_nm; ?>" id="uploadedimage"/>
