<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>美術資料連結2.0</title>
</head>
<body bgcolor="#b5c4b1">

<?php
      require_once('/Apis/PubApi20.php');
	  require_once('/Apis/mysqlApi20.php');
	  defineBaseData();
	  PubApi_DrawUserData(10,50);
	  DrawTitle();
	  DrawRootType();
	  DrawChildType();
?>

<?php
      function defineBaseData(){
	           global $URL;
			   $URL="index.php";
			   global  $startY,$colorCodes;
			   $startY=50;
	           PubApi_getCookie();
			   global $selectype ;
		       $selectype=$_POST["selectype"];
			   $selectypeSort=$_POST["selectypeSort"];
			   if( $selectypeSort=="")   $selectypeSort=0;
			   if( $selectype=="") $selectype=0;
			   global $RootType,$childType;
			   $tmp=getMysqlDataArray("artindex2");
			   $RootType=filterArray( $tmp,2,"rootType");
			   $childType_T=filterArray( $tmp,2,"childType");
			   $childType=filterArray(  $childType_T,3,$selectypeSort);
	  }
	  function DrawTitle(){
		       global  $startY,$colorCodes;
		       DrawRect("","10","#ffffff",array( 10,$startY+30, "1400","2"),$colorCodes[2][5]);
			   DrawText("ArtWorks",array(120,($startY-10),200,60),10,"#aaaaaa");
			   DrawText("TaipeiRD2",array(10,$startY-30,200,60),30,"#ffffff");
	  }
	  function DrawRootType(){
		       global  $RootType ,$selectype ;
	     	   global $URL;
		       global $colorCodes;
		       $x=190;
	           for ($i=0;$i<count(  $RootType );$i++){
			        $color=$colorCodes[0][0];
			        $boxWidth=180;
			        $boXheight=30;
				    $y=$startY+50;
				    if($i==$selectype){
				       $color=$colorCodes[0][1];
			           $boxWidth=200;
			           $boXheight=40;
					   $y=$startY+40;
			     	 }
			        sendVal( $URL,array(array("selectype",$i),array("selectypeSort", $RootType[$i][4])),"submit",$RootType[$i][5],array($x,$y,$boxWidth, $boXheight) ,20,$color);
				    $x+=$boxWidth+5;
		  }
	  }
	   function DrawChildType(){
	            global $childType;
				global $colorCodes;
				$x=160;
				$y=90;
				$fontSize=12;
				$BgColor=$colorCodes[0][0];
				$fontColor="#ffffff";  
				for($i=0;$i<count($childType);$i++){
					$msg=$childType[$i][5];
					$Link=$childType[$i][7];
					$Rect=array($x,$y+$i*28,200,20);
				    DrawLinkRect($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link,$border,$Layer)  ;
				}
	   
	   }
?>