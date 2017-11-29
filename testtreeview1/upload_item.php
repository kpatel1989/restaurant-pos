<?php
session_start();
echo "id:" .$_SESSION['photoItemId'];
$allowedExts = array("jpg", "jpeg", "gif", "png");
if (isset($_SESSION['photoItemId']))
	$Iid = $_SESSION['photoItemId'];
unset($_SESSION['photoItemId']);
echo "iid" . $Iid;
if (isset($_SESSION['count']))
	$no = $_SESSION['count'];
unset($_SESSION['count']);

$extension = end(explode(".", $_FILES["file"]["name"]));
$extension=strtolower($extension);

if (in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Error uploading file. Error Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
		if (file_exists("temp/" . $Iid . "_" . $no .".". $extension))
		{	
			unlink("temp/". $Iid   . "_" . $no .".". $extension);
		}
		
		move_uploaded_file($_FILES["file"]["tmp_name"], "temp/" . $Iid . "_" . $no . "." . $extension);
		$_SESSION['newimage']= $Iid . "_" . $no . "." . $extension;
		echo "Stored in: temp/" . $Iid . "_" . $no . "." . $extension;
	}
  }
else
{
	echo "Invalid file";
}
?>