 <!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>????</title>
</head>
<body bgcolor="#999999">
<?php //主功能
	   $id=$_COOKIE['IGG_id'];
	   $rank=$_COOKIE['IGG_Rank'];
	   if($id=="")$id="guest";
	   $colorCodes= GetColorCode();
 
?>

<?php //Array功能
       function RemoveArray($BaseArray,$num,$RemoveStr){
	            $returnArray=array();
				 for($i=0;$i<count($BaseArray);$i++){
					 if($BaseArray[$i][$num]!=$RemoveStr)array_push($returnArray,$BaseArray[$i] );
				 }
	            return $returnArray;
	   }  
       function returnArrayNum($BaseArray,$string){
	        for($i=0;$i<count($BaseArray);$i++){
				if($BaseArray[$i]==$string)return $i;
			}
			return  0;
	 }
       function returnOrdersNumArray($BaseArray,$BaseNum,$SortArray,$sortNum){
	         $newArray= array();
			  for($i=0;$i<count($SortArray);$i++){
				  $num=returnnumDatas($BaseArray,$BaseNum,$SortArray[$i][$sortNum],$newArray);
				  $a=array( $SortArray[$i][$sortNum],$num);
				    array_push ( $newArray,$a);
			  }
			 return  $newArray;
	  }
	   function returnnumDatas($BaseArray,$BaseNum,$Name ){
		   $num=0;
		  for($i=0;$i<count($BaseArray);$i++){
			  $s=$BaseArray[$i][$BaseNum];
			  if($s==$Name){
			   $num+=1;
			  }
		  }
		  return  $num;
	  }
	   function sortArrays($BaseArray ,$ArrayNum ,$forwardBool){
  		  $newArray=array();
		  $lastSn=  getLastSN2($BaseArray,$ArrayNum);
      		 
		 if($forwardBool=="true"){//正向
		  	  for($i=0;$i<= $lastSn;$i++){
                 $tmpArray= GetArraySn($BaseArray, $ArrayNum ,$i);
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  } 
		  }
		  if($forwardBool=="false"){//逆向
		  	  for( $i=$lastSn;$i>0;$i--){
                 $tmpArray= GetArraySn($BaseArray, $ArrayNum ,$i );
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  }
		  }
	      return  $newArray;
	  }
	   function GetArraySn($BaseArray, $ArrayNum ,$sn ){
			  $newArray=array();
		      for($i=0;$i<count($BaseArray);$i++){
			     if($BaseArray[$i][ $ArrayNum]==$sn) {
					  array_push (  $newArray,$BaseArray[$i]);
				 }  
			  }
			  return $newArray;
	   }
	   function filterArray($BaseArray,$Num,$checkName){
                $data=array();		     
		  for($i=0;$i<count($BaseArray);$i++){
			  $strBase=trim($BaseArray[$i][$Num]);
			  $srtCheck=trim($checkName);
			 //   if($BaseArray[$i][$Num]==$checkName){
			  if($strBase==$srtCheck){
			     array_push($data,$BaseArray[$i]);
			     }
			  }
	       return $data;
	   }
	   function filterArraycontain($BaseArray,$Num,$containName){
                $data=array();		     
		  for($i=0;$i<count($BaseArray);$i++){
			  		  $strBase=trim($BaseArray[$i][$Num]);
			  $srtCheck=trim($containName);
			//  $strBase=$BaseArray[$i][$Num];
			//  $srtCheck=$containName;
			//  echo   "</br>".$strBase.">".$srtCheck;
		//	  if( $strBase!="" && $srtCheck!=""){
			      if(strpos($strBase,$srtCheck) !== false){ 
				//  echo "xxxxxxx";
			     array_push($data,$BaseArray[$i]);
			     }
			 // }
          
			  }
	       return $data;
	   }
?>

<?php //功能
	   function returnProjectColor($projects,$name){
			   $n=0;
			    for($i=0;$i<count($projects);$i++){
				    if($projects[$i][0]==$name){
						$n=$projects[$i][1];
					}
			  }
			  return $n;
	  }
       function getLastSN2($SQLData,$SnNum){
	      $lastSN=0;
		  for($i=0;$i<count($SQLData);$i++){
		 
		  if($SQLData[$i][$SnNum]>$lastSN)$lastSN=$SQLData[$i][$SnNum];
		  }
		  return $lastSN;
	   }
	   function SortOrders($BaseArray,$BaseNum,$SortArray,$sortNum){
	          $newArray= array();
			  for($i=0;$i<count($SortArray);$i++){
				  $newArray=returnSortOrders($BaseArray,$BaseNum,$SortArray[$i][$sortNum],$newArray);
			  }
			  $newArray=returnSortOrders($BaseArray,$BaseNum,"",$newArray);
			  
			  return $newArray ;
	  }
	   function returnSortOrders($BaseArray,$BaseNum,$Name,$newArray){
		  for($i=0;$i<count($BaseArray);$i++){
			  $s=$BaseArray[$i][$BaseNum];
			  	 // echo "<br>".$s;
			  if($s==$Name){
			   array_push ( $newArray,$BaseArray[$i]);
			  }
		  }
		  return  $newArray;
	  }
?>

<?php //MysQl資料
 	   function getAll_num($SElectTable){
		  $data_library="IGGTaipeRD2";
	      $db = mysql_connect("localhost","root","1406");
	      mysql_select_db( $data_library,$db);
          mysql_query("SET NAMES 'utf8'");
	      return  mysql_query("SELECT * FROM ".$SElectTable,$db);	  
	  }
       function getMysqlSortData($DataBase,$sort1,$Data1,$sort2,$Data2,$getDataSort){
		  // echo $sort1.">".$Data1.">".$sort2.">".$Data2.">".$getDataSort;
	          for($i=0;$i<count($DataBase);$i++){
				//  echo "</br>".$DataBase[$i][$sort1]."=".$Data1.">".$DataBase[$i][$sort2]."=".$DataBase[$i][$getDataSort] ;
			     if($DataBase[$i][$sort1]==$Data1 && $DataBase[$i][$sort2]==$Data2 ){
				  
				    return $DataBase[$i][$getDataSort];
				 }
			  }
	    return "";
	   }
       function getmemberID(){
		   $members=getMysqlDataArray("members");
		    $memberId=array();
		    for($i=0;$i<count($members);$i++){
				 $color="#000000";
				 $id=$members[$i][0] ;
			   
				 $memberId[$members[$i][0]]=$members[$i][1] ;
				 $x+=  64;
			}
			return $memberId;
	   }
	   function getmemberCID($id){
		  $memberTmp=getMysqlDataArray("members");
		  for($i=0;$i<count( $memberTmp);$i++){
			if($memberTmp[$i][0]==$id)return $memberTmp[$i][1];
			 }
			 return $id;
		}
	   function getMysQlDataNameArray($tableName,$nameArray){
		       $all_num= getAll_num( $tableName );
		       $returnData=array();
			   $t=mysql_num_rows($all_num); 
			    	for($i=0;$i<$t;$i++){ 
					    $data=array();
					    for ($x=0 ;$x<count($nameArray);$x++){  
						      $d=mysql_result(  $all_num,$i,$fName[$x]);
						       array_push($data,$d);
				         	}
   	                     array_push($returnData,$data);
					}
			   return $returnData;
	  }
	   function getMysqlDataArray($name){
	            $all_num= getAll_num( $name );
				$fieldnum=mysql_num_fields( $all_num);
				$fName=array();
				for ($x=0 ;$x<$fieldnum;$x++)	array_push($fName, mysql_field_name($all_num,$x));
				$returnData=array();
				 $t=mysql_num_rows($all_num); 
				for($i=0;$i<$t;$i++){
				    $data=array();
					for ($x=0 ;$x<count($fName);$x++){
						 $d=mysql_result(  $all_num,$i,$fName[$x]);
						 array_push($data,$d);
					}
					 array_push($returnData,$data);
				}
				return $returnData;
	  }
?>

<?php //會員相關資料
	  function DrawUserData($x,$startY){
	        global $id,$colorCodes,$startY;
	        DrawRect("" ,"12","#ffffff",$x+10,$startY+10,160,20, $colorCodes[0][0]);
	        DrawText("使用者",$x+30,$startY+12,100,20,12, $colorCodes[5][2]);
			DrawRect( $id,"12",$colorCodes[0][0]  ,$x+72,$startY+12,60,16, $colorCodes[5][2]);
			DrawLinkPic("Pics/home.png",$startY+12,$x+10,16,16,"index.php");
			if($id!="guest"){
			DrawLinkPic("Pics/Logout.png",$startY+12,$x+150,16,16,"Login.php?Logout=true");
			}
			if($id=="guest"){
			DrawLinkPic("Pics/Logout.png",$startY+12,$x+150,16,16,"Login.php");
			}
	  }
	  function DrawMembersLinkArea( $StartX,$startY ,$BaseURL){
		     global $memberId;
			 $memberTmp=getMysqlDataArray("members");
			 $members=filterArray($memberTmp,"3","Art");
			 $memberId=array();
			 $x= $StartX;
			 for($i=0;$i<count($members);$i++){
			  if($members[$i][4]!="Other"){  
				 $color="#000000";
				 $id=$members[$i][0] ;
				 $pic="Pics/Members/".$id.".png";
				 $Link=$BaseURL."?List=ArtWork&user=".$members[$i][0];
			     DrawMemberLinkRect($members[$i][1],"10","#cccccc", $x+2, $startY , "60","40",$color,$members[$i][4],$id, $Link,$pic);
				 $memberId[$members[$i][0]]=$members[$i][1] ;
				 $x+=  64;
			  }
		   }
	   } 
	  function DrawMembersLinkArea_Simple( $StartX,$startY ,$BaseURL){
		     global $memberId;
			 $memberTmp=getMysqlDataArray("members");
			 $members=filterArray($memberTmp,"3","Art");
			 $memberId=array();
			 $x= $StartX;
			// $startY=-2;
			 DrawRect("",10,$color, $StartX-10, $StartY,( 70*count($members)),"20","#000000");
			 DrawText("內部",$StartX-6 , $StartY+11,"100",20,9,"#dddddd");
			 $x+=24;
			 for($i=0;$i<count($members);$i++){
			  if($members[$i][4]!="Other"){  
				 $color="#000000";
				 $id=$members[$i][0] ;
				 $pic="Pics/Members/".$id.".png";
				 if( !file_exists( $pic)){
					  $pic="Outsourcing/pic/Defuse.png";
				 }
				 $Link=$BaseURL."?List=ArtWork&user=".$members[$i][0];
				 DrawLinkRect($members[$i][1],"9","#000000",  $x-5, $startY+5 ,60 ,11,"#dddddd",$Link,"");
			     DrawLinkPic($pic, $startY+2 , $x-5  ,12 ,12,$Link); 
			   //  DrawMemberLinkRect($members[$i][1],"10","#cccccc", $x+2, $startY , "60","40",$color,$members[$i][4],$id, $Link,$pic);
			     $memberId[$members[$i][0]]=$members[$i][1] ;
				 $x+=  64;
			  }
		   }
	   } 
	  function DrawMemberLinkRect($Name,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Job,$id,$Link,$pic){
		      //color
			  echo "<div  id=User-".$id." ";
	          echo "  style=' color:".$fontColor."; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute  ;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo  $Job;
	          echo "</div>";
			  DrawLinkRect("　".$Name,$fontSize,"#000000",$x,$y+20,$w-6,$h-42,"#ffffff",$Link,1);
			  DrawPosPic($pic,$y+20,$x,"14","14","absolute");
	   }
      function DrawOutLinkArea($StartX,$startY ,$BaseURL){
                global $colorCodes;
                $outTmp1=getMysqlDataArray("outsourcing");
			    $outTmp=filterArray($outTmp1,"0","data");
				$Out_Types=array("2D","3D","Fx&UI");
				$filter_addition=array(array("概念","角色","場景"),array( "3D"),array("特效","UI"));//包含 
				$filter_Except=array(array("3D"),array("概念"),array("xx"));//不包含 
				$x= $StartX;
				$y= $startY-22;
				for($i=0;$i<count($Out_Types);$i++){
				    $ListArray=filterArray_add_ExcArray($outTmp,$filter_addition[$i],$filter_Except[$i],7);
				    DrawOuts(array($x,$y,200,20),$ListArray,$BaseURL,$Out_Types[$i],$colorCodes[11][$i]);
					$y+=22;
				}
	   }
	  function DrawOuts($Rect,$outTmp,$BaseURL ,$TypeName,$color){
		        DrawRect("",10,$color,$Rect[0]-10,$Rect[1],120+70*count($outTmp),"20",$color);
			    DrawText($TypeName,$Rect[0]-7,$Rect[1]+3,"100",20,9,"#dddddd");
		        for($i=0;$i<count( $outTmp);$i++){
					$picLink=str_replace(' ','',$outTmp[$i][13]);
		            $Link=$BaseURL."?List=Out&user=".$outTmp[$i][1];
				    $pic="Outsourcing/pic/Defuse.png";
					if($picLink!=""){
					  if( file_exists("Outsourcing/pic/".$picLink)) $pic="Outsourcing/pic/".$picLink;
					}
				    
					$outName="___".$outTmp[$i][2];
					DrawLinkRectAutoLength($outName,"9","#000000", $Rect[0]+20 , $Rect[1]+5 ,60 ,10,"#dddddd",$Link,"");
					DrawLinkPic($pic,$Rect[1]+3,$Rect[0]+20  ,12 ,12,$Link); 
				    $Rect[0]+= 70;
				}
	   }
	  function filterArray_add_ExcArray($BaseArray,$filter_addition,$filter_Except,$num){ //分析陣列中含有字串
	            $finArray=array();
	 
	            for($i=0;$i<count($BaseArray);$i++){
				    if((isArrayContain($BaseArray[$i],$filter_addition,$num))=="true"){//含有
						 if((isArrayContain($BaseArray[$i],$filter_Except,$num))=="false"){//未含有
						 array_push($finArray,$BaseArray[$i]);
						  }
					}
				}
				return $finArray;
	   }
	  function isArrayContain($BaseArray_single, $filterArray,$num){
		        for($i=0;$i<count($filterArray);$i++){
	               if(strpos( $BaseArray_single[$num],$filterArray[$i] ) !== false )return "true";  
				}
				return "false";
	   }
?>

<?php  //公用
	  function GetColorCode(){
	          $all_num= getAll_num( "colorcodes");
	          $t=mysql_num_rows($all_num); 
			  $ColorCodes=array();
			  for($i=0;$i<$t;$i++){
			      $code =mysql_result(  $all_num,$i,'ColorKey');
			      $set =mysql_result(  $all_num,$i,'set');
			      $sn=mysql_result(  $all_num,$i,'sn');
				  $ColorCodes[$set][$sn]= $code ;
			  }
	          return $ColorCodes;
	  }	
	  
	  
?>

<?php   //php表格用
	   function MakeSelectionV2($items,$selectItem,$selectName,$size){
		    $selectItemf=trim(  $selectItem);
	        //$seletProject= "<select   style=width:100px; height:".$size."px;  name=".$selectName."    >";
			  $seletProject= "<select  class=form-control  style=font-size:".$size."px; color: red;  id=".$selectName."  name=".$selectName."  >";
			$seletProject=$seletProject."<option value=未定義 >未定義</option>";
			for($i=0;$i<count($items);$i++){
				//echo $items[$i];
				 $itemf=trim( $items[$i]);
			     $seletProject=$seletProject."<option value=".$items[$i];
				 if($itemf== $selectItemf){
					 $seletProject=$seletProject." selected=true ";
				 }
				 $seletProject=$seletProject.">".$items[$i]."</option>";
			}
			$seletProject=$seletProject."</select>";
	        return $seletProject;
	   }
	   function MakeSelection($items,$arrayNum,$selectItem,$selectName){
		  //  echo $selectName;
	        $seletProject= "<select  style=width:100px; name=".$selectName."   >";
			for($i=0;$i<count($items);$i++){
			     $seletProject=$seletProject."<option value=".$items[$i][$arrayNum];
				 if($items[$i][$arrayNum]==$selectItem) $seletProject=$seletProject." selected=true ";
				 $seletProject=$seletProject.">".$items[$i][$arrayNum]."</option>";
			}
			$seletProject=$seletProject."</select>";
			//echo "</br>".$seletProject;
	        return $seletProject;
	   }
       function DrawPopBG($x,$y,$w,$h,$title,$fontSize,$BackURL){
	       DrawPicBG("Pics/Black50Bg.png",$y-40,$x-40,$w+80,$h+80);
		   DrawLinkPic("Pics/Cancel.png",$y-50,$x+$w+20,32,32,$BackURL);
		   DrawRect($title,$fontSize,"#ffffff",$x ,$y ,$w,"20","#a27e7e");
	   }
	   function DrawHelp($pic,$x,$y,$w,$h,$Link,$title){
	        DrawPicBG("Pics/Black50Bg.png",$y-40,$x-40,$w+80,$h+80);
		    DrawLinkPic("Pics/Cancel.png",$y-50,$x+$w+20,32,32,$Link);
		    if($pic) DrawLinkPic($pic,$x,$y,$w,$h,$Link);
			DrawText($title,$x-20,$y-20,300,50,24,"#ffffff");
	   }
	   function getTypeColor($name){
		        global $colorCodes;
				$color="#000000";
	          //  if(strpos('$name','角色')==true   && !strpos('$name','3D') )$color=$colorCodes[10][1];
			     if(strpos( $name,'角色') !== false && strpos( $name,'3D') ==false){
				 	  $color=$colorCodes[11][1];
				 }
			     if(strpos( $name,'概念') !== false  ){
				 	  $color=$colorCodes[11][1];
				 }
			     if(strpos( $name,'3D') !== false  ){
				 	  $color=$colorCodes[11][2];
				 }
		         if(strpos( $name,'特效') !== false  ){
				 	  $color=$colorCodes[11][4];
				 }
				return $color;
	   }
	   function DrawMembersDragArea( $StartX,$startY ){
		     global $memberId;
			 $memberTmp=getMysqlDataArray("members");
			 $members=filterArray($memberTmp,"3","Art");
			 $memberId=array();
			 $x= $StartX;
			 for($i=0;$i<count($members);$i++){
			  if($members[$i][4]!="Other"){  
				 $color="#000000";
				 $id=$members[$i][0] ;
			     DrawMemberRect($members[$i][1],"11","#ffffff", $x+2, $startY , "60","40",$color,$members[$i][4],$id);
				 $memberId[$members[$i][0]]=$members[$i][1] ;
				 $x+=  64;
			  }
		   }
	   } 
	   function DrawMemberRect($Name,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Job,$id){
		      //color
			  echo "<div  id=User-".$id." ";
			  echo " ondrop='Drop(event)' ondragover='AllowDrop(event)' ondragleave ='DragLeave(event)' ";
	          echo "  style=' color:".$fontColor."; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:fixed;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo  $Job;
	          echo "</div>";
			  echo "<div  style=' color:#000000; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:fixed;  top:".($y+20)."px; left:". ($x+3) ."px;  width:".($w-6)."px;height:".($h-42)."px; background-color:#eeeeee; '>";
			  echo "　".$Name;
	          echo "</div>";
			  $pic="Pics/Members/".$id.".png";
			  DrawPosPic($pic,$y+20,$x,"14","14","fixed");
	   }
?>

<?php  //PHP區域
       function DrawIDRect($x,$y,$w,$h,$id,$info, $fontSize,$fontColor,$BgColor,$other){
	          echo "<div  id=".$id." ";
              echo " style='   " ;
			  echo " position:absolute;  top:".$y."px; z-index:-1; left:".$x."px;  width:".$w."px;height:".$h."px;";
			  echo  $other;
			  echo " font-size:".$fontSize."px; color:".$fontColor.";   background-color:".$BgColor.";  '  >";
	          echo $info;
			  echo "</div>";
	    }
       function DrawDragRect($x,$y,$w,$h,$BgColor,$id){
	          echo "<div  id=".$id." ";
			  echo " ondrop='Drop(event)' ondragover='AllowDrop(event)' ondragleave ='DragLeave(event)'   ";
              echo  " style='   " ;
			  echo "position:absolute;  top:".$y."px; z-index:-1; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor.";  '  >";
	          echo "</div>";
	    }
	   function DrawDragbox($x,$y,$w,$h,$BgColor,$id,$info,$fontSize){
	          echo "<div  id=".$id." ";
			  echo " draggable='true' ondragstart='Drag(event)' ";
              echo " style=' " ; //align=left
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute; top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
	          echo  $info;
			  echo  "</div>";
	   }
	   function DrawText($text,$x,$y,$width,$height,$Size,$Color){
	      echo"<div   style=' text-align:left  ;color:".$Color.";
			  font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$Size."px;
			  position:absolute; top:".$y."px; left:".$x."px ;width:".$width."px;height:".$height."px;
	          '>".$text."</div>";
	   }
	   function DrawText_Layer($text,$x,$y,$width,$height,$Size,$Color,$Layer){
	            echo"<div   style=' text-align:left  ;color:".$Color."; ";
			    echo " z-index:".$Layer ."; ";
			    echo " font-family:Microsoft JhengHei; font-size:".$Size."px;
			    position:absolute; top:".$y."px; left:".$x."px ;width:".$width."px;height:".$height."px;
	            '>".$text."</div>";
	   }
	   function DrawLinkText($text,$x,$y,$width,$height,$Size,$Color,$Link){
	      echo"<div  onclick=location.href='".$Link."' style=' text-align:left  ;color:".$Color.";
			  font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$Size."px;
			  position:absolute; top:".$y."px; left:".$x."px ;width:".$width."px;height:".$height."px;
	          '>".$text."</div>";
	   }
	   function DrawPosPic($pic,$x,$y,$w,$h,$posType ){
	    	echo "<div style='position:".$posType; 
			echo ";  top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				   '><img src=".$pic." width=".$w." height=".$h."></div>";
	   }
	   function DrawPicBGwithID($pic,$x,$y,$w,$h,$id){
		       echo "<div id=".$id." style='position:absolute; background-image:url(".$pic.");
				      top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '></div>";
	   }
	   function DrawPicBG($pic,$x,$y,$w,$h){
		        echo "<div style='position:absolute; background-image:url(".$pic.");
				      top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '></div>";
	   }
	   function DrawPic_Layer($pic,$x,$y,$w,$h,$Layer){
		        echo "<div    style='position:absolute;  ";
				  echo " z-index:".$Layer ."; ";
				echo "  top:".$y."px;Left:".$x."px; width:".$w."px;height:".$h."px;
				      '><img src=".$pic." width=".$w." height=".$h."></div>";
	   }
	   function DrawLinkPic($pic,$x,$y,$w,$h,$Link){
		     	echo "<div  onclick=location.href='".$Link."' style='position:absolute; 
				       top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '><img src=".$pic." width=".$w." height=".$h."></div>";
	   }
	   function DrawPicwithID($pic,$x,$y,$w,$h,$id){
		     	echo "<div id=".$id  ;
				echo " draggable='true' ondragstart='Drag(event)' ";
				echo "' style='position:absolute; 
				       top:".$x."px;Left:".$y."px; width:".$w."px;height:".$h."px;
				      '><img src=".$pic." width=".$w." height=".$h."></div>";
	   }
	   function DrawLinkRectAutoLength($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Link,$border){
	            $c=strlen($msg);
				if($c*$fontSize<$w){
				DrawLinkRect_Layer($msg,$fontSize,$fontColor,array($x,$y,$w,$h),$BgColor,$Link,$border,0);
				return;
				}
			 
				$w2=$c*($fontSize/2);
				DrawRect_Layer( "",$fontSize,$fontColor,array($x,$y,$w2,$h),"#aaaaaa",-1 );
			    DrawRect_Layer( "",$fontSize,$fontColor,array($x,$y,$w,$h),$BgColor ,0);
				// DrawRect( "",$fontSize,$fontColor,$x,$y,$w2,$h,"#aaaaaa" );
				// DrawRect( "",$fontSize,$fontColor,$x,$y,$w,$h,$BgColor );
				 DrawLinkText($msg,$x,$y,$c*$fontSize,$h,$fontSize,$fontColor,$Link);
	   }
	   function DrawLinkRect_newtab($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Link,$border){
	          echo "<div   style=' cursor:pointer ; color:".$fontColor."; " ;
			  echo $border;
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; ' "; 
			  echo " onClick=window.open('".$Link."','_newtab'); >";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawLinkRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Link,$border){
	          echo "<div onclick=location.href='".$Link."'  style=' cursor:pointer ; color:".$fontColor."; " ;
			  echo $border;
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor){ 
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:center ; line-height:".($h)."px ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawstickyInsideRect($msg ){
			  echo "<div  style=' position:absolute ;  top:20>" ;
			  echo   $msg;
	          echo "</div>";
	   }
	   function DrawabsoluteRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$pos,$Link ){
		      if($Link!="") echo "<div onclick=location.href='".$Link."' style=' ";
			  if($Link=="") echo "<div  style=' " ;
		      echo " color:".$fontColor."; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo  "position:".$pos." ; top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo  $msg;
	          echo "</div>";
	   }
 	   function DrawInputRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$WorldAlign,$input){ 
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:".$WorldAlign." ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px;  '>";
			  echo  $msg.$input ;
	          echo "</div>";
	   }
       function DrawInputRect_size($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$WorldAlign,$input){ 
	          echo "<div  style='font-size:".$fontSize."px; color:".$fontColor."; " ;
			  echo "text-align:".$WorldAlign." ; font-weight:bolder ;  ";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px;  '>";
			  echo  $msg.$input ;
	          echo "</div>";
	   }
	   function DrawLinkRect_Layer($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link,$border,$Layer){
	          echo "<div   style=' cursor:pointer ; color:".$fontColor."; " ;
			  echo $border;
			  echo " z-index:".$Layer ."; ";
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; ' "; 
			  echo " onclick=location.href='".$Link."'; >";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawLinkRect_Layer_Left($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link,$border,$Layer){
	          echo "<div   style=' cursor:pointer ; color:".$fontColor."; " ;
			  echo $border;
			  echo " z-index:".$Layer ."; ";
			  echo " text-align:left ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; ' "; 
			  echo " onclick=location.href='".$Link."'; >";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawLinkRect_LayerNew($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link,$border,$Layer){
	          echo "<div   style=' cursor:pointer ; color:".$fontColor."; " ;
			  echo $border;
			  echo " z-index:".$Layer ."; ";
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo " position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; ' "; 
			  echo " onClick=window.open('".$Link."','_newtab'); >";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawRect_Layer($msg,$fontSize,$fontColor,$Rect,$BgColor,$Layer){ 
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo " z-index:".$Layer ."; ";
			  echo "text-align:center ; line-height:".($h)."px ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  width:".$Rect[2]."px;height:".$Rect[3]."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
	   function DrawProgress($msg,$perc,$rect,$colors,$fontSize){
 
	            DrawRect("",11,"#ffffff",$rect[0],$rect[1],$rect[2],$rect[3],$colors[0]);
				$w=$rect[2]*$perc;
				DrawRect("",11,"#ffffff",$rect[0],$rect[1],$w,$rect[3],$colors[1]);
				DrawText($msg,$rect[0],$rect[1],$rect[2],$rect[3] ,$fontSize,$colors[2]);
	   }
?>

 