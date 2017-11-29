<?php 
    $image = imagecreatefromjpeg('Desert.jpg'); 
    //$image2= imagecreatefromjpeg('Desert2.jpg');
	$image_type=IMAGETYPE_JPEG;
	$compression=70;
	$permissions=null;
	
	$height = 250;
	$width = 250;
	$new_image = imagecreatetruecolor($width, $height);
	imagecopyresampled($new_image,$image,0,0,0,0,$width,$height,imagesx($image),imagesy($image));
	
	imagejpeg($new_image,'Desert2.jpg',$compression);
	
  
	
?> 