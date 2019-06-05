 <!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>色表檢視器</title>
</head>
<body bgcolor="#999999">
 <?php
     include('PubApi.php');
	  $ColorKeys=getMysqlDataArray("colorcodes");
	  DrawColors($ColorKeys);
      
	   function DrawColors($ColorKeys){
			  for($i=0;$i<count($ColorKeys);$i++){
				   $color=$ColorKeys[$i][0];
				   $set=$ColorKeys[$i][1];
				   $sn=$ColorKeys[$i][2];
				   $n=$i."[".$set."_".$sn."</br>".$color;
			       DrawRect($n ,"10","#000000",$sn*60 +100,$set*60+60,"58","58",$color);
			  }
	 }
 ?>