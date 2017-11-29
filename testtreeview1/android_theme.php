<?php
require "class._database.php";

$db=new _database();
$conn = $db->connect();
$result=mysqli_query($conn, "Select themename from theme where isset=1");
$row=mysqli_fetch_assoc($result);
echo $row['themename'];
?>