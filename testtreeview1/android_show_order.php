<?
require_once("autoload.php") 
?>

<? // MPage::BeginBlock() ?>
<?php include "head.php"; ?>
<? // MPage::EndBlock("head") ?>

<? // MPage::BeginBlock() ?>
<div id="wrapper">
    <div id="content">
<br/><br/>


<html>
<head>
<script src="jquery.min.js">
</script>
<script type="text/javascript">
	function myFunction()
	{
		setInterval(function(){test()},1000);
	}

	
	function test(){
		
		 var request = $.ajax({
		        url: "android_order.php",
		        type: "post",
		        data: "name=aa"
		    });

		    // callback handler that will be called on success
		    request.done(function (response, textStatus, jqXHR){
		    	document.getElementById("mainDiv").innerHTML="";
			      var initokens=response.split("*");
				  
			      for(var k=0;k<initokens.length-1;k++){
						var tokens=initokens[k].split("-");
						document.getElementById("mainDiv").innerHTML=document.getElementById("mainDiv").innerHTML+"Order ID: "+tokens[0]+" Table ID: "+tokens[1]+"</br>";
			      }
		    });

		    // callback handler that will be called on failure
		    request.fail(function (jqXHR, textStatus, errorThrown){
		    });
		   
	}
</script>
</head>
<body onload="myFunction();">
	<div id="mainDiv">
	</div>
</body>
</html>
</div>
<? // MPage::EndBlock("body") ?>

<? // MPage::BeginBlock() ?>

<?php include "sidebar.php"; ?>
<? // MPage::EndBlock("sidebar") ?>

<? // MPage::BeginBlock() ?>

<?php include "foot.php"; ?>
<? // MPage::EndBlock("footer") ?>