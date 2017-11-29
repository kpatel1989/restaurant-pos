<?php
require "class._database.php";
session_start();

if(isset($_REQUEST['name'])){
		$x=$_REQUEST['name'];
		if(strpos($x,'@')!=false)
		{
			$_SESSION["cat_flag"]="existing";
			$_SESSION["id"]=$x;
			include "cat.php";
		}
		elseif(strpos($x,'^')!=false){
			$_SESSION['itemid'] = $x;
			$_SESSION['Eitem'] = true;
			//header('Location: /ItemDetails.php');
			include "ItemDetails.php";
			
		}
		elseif(strpos($x,'I')!=false){
			$_SESSION['addnew'] = true;
			$_SESSION['itemid'] = $x;
			//header('Location: /ItemDetails.php');
			include "ItemDetails.php";
		}		
		else{
			if($x==0){
				$inpt="Add NEw Category";
				$_SESSION["cat_flag"]="new root";
				$_SESSION["id"]=$x;
				include "cat.php";
				
			}	  
			else{
				$inpt="add new sub category";
				$_SESSION["cat_flag"]="new sub";
				$_SESSION["id"]=$x;
				include "cat.php";
				}
		}
			
	}
?>

