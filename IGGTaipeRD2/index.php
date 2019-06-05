 
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>美術資料連結</title>
</head>
<body bgcolor="#b5c4b1">
<?php
      include('PubApi.php');
 
	  if($selectype=="")$selectype=0;
      $startY=50;
	  $TypeCont;
	  $types=array();	
      $typeColors=array();
      DrawTitle();	  
	  DrawType();
	  DrawDetailed();
      DrawUserData(10,$startY);
      DrawEditIcon();
	  
	  function DrawEditIcon(){
	           global $rank;
			   if($rank==1){
			   
			   }
	  }
	  
	  function DrawDetailed(){
		    global $selectype,$TypeCont;
	        $all_num=getAll_num("artindex");	
	        $t=mysql_num_rows($all_num); 
            $DetailedName=array();
			$DetailedLinkAddres=array();
			$fs=$selectype;
		    if($selectype== $TypeCont)$fs="999";
	        for ($i=0;$i<$t;$i++){
				$s=mysql_result($all_num,$i,'TypeSort');
			    if($s==$fs){
					$so=mysql_result($all_num,$i,'NameSort');
					$DetailedName[$so]=mysql_result($all_num,$i,'Name');
					$DetailedLinkAddres[$so]=mysql_result($all_num,$i,'LinkAddres');
				}
		       }
			 for ($i=0;$i<count(  $DetailedName );$i++){
				  $BgColor="#656565";
			      $boxSize="width:280px; height:30px; top:".(100+$i*40)."px;left:200px;";
				  echo"<div  onclick=location.href='".$DetailedLinkAddres[$i]."';"."
			      style=' font-size:14px;
			      text-align:left;line-height:30px; font-weight:bolder ;font-family:Microsoft JhengHei;color:#ffffff;
			      background-color:".$BgColor."; position:absolute; ".$boxSize."
			      '>　".$DetailedName[$i] ."</div>";
			 }
	  }
	  
	  function DrawTitle(){
		    global  $startY,$colorCodes;
			DrawRect("","10","#ffffff", 10,$startY+30, "1400","2",$colorCodes[2][5]);
			DrawText("ArtWorks",120,($startY-10),200,60,10,"#aaaaaa");
			DrawText("TaipeiRD2",10,$startY-30,200,60,30,"#ffffff");
	
	  }
	  
	  function DrawType(){
		  global $types ,$typeColors,$selectype,$TypeCont;
		  global $colorCodes;
	      getTypes(); //紀錄分類數		
		  $x=190;
	      for ($i=0;$i<count( $types );$i++){
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
				$Link="index.php?selectype=".$i;
			   	DrawLinkRect($types[$i],"20","#ffffff",  $x,$y,$boxWidth, $boXheight,$color,$Link,"");
				$x+=$boxWidth+5;
		  }
	  }
       
	  function getTypes(){
		  global $types ,$typeColors,$TypeCont;
	      $all_num=getAll_num("artindextype");	
          $t=mysql_num_rows($all_num); 
		  $Lasts=0;
          for ($i=0;$i<$t;$i++){
		      $tmpSort= mysql_result($all_num,$i,'Sort');
		      if( $tmpSort!=999 && $tmpSort> $Lasts) $Lasts=$tmpSort;
	         }
		  $TypeCont=  $Lasts+1;
		  $so=0;
		  for ($i=0;$i<$t;$i++){
			     $tmpSort= mysql_result($all_num,$i,'Sort');
				 if( $tmpSort!=999 )$so= $tmpSort  ;
				 if( $tmpSort==999 )$so= $Lasts+1  ;
				 $types[$so]=mysql_result($all_num,$i,'Name');
				 $typeColors[$so]=mysql_result($all_num,$i,'color');
			  }				   	 
	  }
 
 ?>
					   
</body>