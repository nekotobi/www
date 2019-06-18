<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引</title>
</head>

<?php
////main
   include('PubApi.php');
   defineData();
   DrawMainUI();
   DrawResource();
   function defineData(){
           global $StartX, $StartY ;
		   global $BackURL,$Rtype;
		   global $tableName;
           $StartX=20;
		   $StartY=60;
		   $BackURL="ResourceData.php?Rtype=".$Rtype;
	       $tableName="rpgcharacters";
   }
   function DrawMainUI(){
	        global $StartX, $StartY ;
		    global $BackURL,$Rtype,$Utype;
            DrawRect("FP資源索引","22","#ffffff","20","20","1280","30","#000000");
			$ResourceType=array("角色","怪物");
			$UseType=array("採用","捨棄");
   	          for ($i=0;$i<count( $ResourceType);$i++){
		 		   $x=$StartX+ $i*220;
				   $y=$StartY;
				   $color= "#222222";
				   $Link="ResourceData.php?Rtype=".$i."&Utype=".$Utype;
				   if($Rtype==$i)$color="#FF5555";
			       DrawLinkRect($ResourceType[$i],"12","#ffffff",$x,$y,"200","20",$color,$Link,1);
			  }
            for ($i=0;$i<count( $UseType);$i++){
			      $Link="ResourceData.php?Rtype=".$Rtype."&Utype=".$i;
				   $color= "#222222";
				   if($Utype==$i)$color="#FF5555";
				  DrawLinkRect($UseType[$i],"10","#ffffff",$StartX+$i*100,$y+30,"90","16",$color,$Link,1);
			}
   }
   function DrawResource(){
		    global $StartX, $StartY ;
			global $tableName;
		    global $BackURL,$Rtype,$Utype;
		    $List=array("GD編碼","名稱","企劃文案","2D設定","3D檔案","狀態");
			$ListSize=array(100,100,100,60,100,100,100);
			$datasTmp= getMysqlDataArray($tableName);//0sn  1type 	2gdcode 3name  4gdfile  5desing  6 3ddata  7state
            $x=$StartX;
			for ($i=0;$i<count($List);$i++){
				 DrawRect($List[$i],"12","#dddddd",$x ,$StartY+80,$ListSize[$i],"20","#000000");
			     $x+=$ListSize[$i]+10;
			}
		    $y=$StartY +100;
		    
		   $LinkURL="ResourceData.php?Rtype=".$Rtype."&Utype=".$Utype ;
            for ($i=1;$i<count($datasTmp);$i++){
				if($Edit==$i){
				 $x=$StartX;
				 DrawRect($datasTmp[$i][2],"12","#000000",$x ,$y,$ListSize[$i],"40","#aaaaaa"); 
				 $x+=$ListSize[0]+10;
				 DrawRect($datasTmp[$i][3],"12","#000000",$x ,$y,$ListSize[$i],"40","#dddddd"); 
				 $x+=$ListSize[1]+10;
				 DrawRect($datasTmp[$i][4],"12","#000000",$x ,$y,$ListSize[$i],"40","#dddddd");
			     $x+=$ListSize[2]+10;
				 $pic="ResourceData\Spic\Spic00001.png";
				 $Link="";
				 DrawLinkPic($pic,$y,$x,"40","40",$Link);
				 $x+=$ListSize[3]+10;
				 DrawRect($datasTmp[$i][4],"12","#000000",$x ,$y,$ListSize[$i],"40","#dddddd");
			     $x+=$ListSize[4]+10;
				 DrawRect($datasTmp[$i][4],"12","#000000",$x ,$y,$ListSize[$i],"40","#dddddd");
				 $Link= $LinkURL."&Edit=".$i;
			   	 $x+=$ListSize[4]+10;
				 DrawLinkRect("Edit","12","#ffffff",$x ,$y ,"30","20","#000000",$Link,1);
                 $y+=50;
				}
  
			}
		    $Link=$LinkURL."&Up=AddRes"; 
		    DrawLinkRect("+資源","12","#ffffff",$StartX ,$y ,"600","20","#000000",$Link,1);
   }
   

?>
<?php


?>
 </body>
 </html>