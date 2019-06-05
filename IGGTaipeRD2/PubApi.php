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

<?php //功能
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
	  function returnProjectColor($projects,$name){
			   $n=0;
			    for($i=0;$i<count($projects);$i++){
				 
				    if($projects[$i][0]==$name){
						$n=$projects[$i][1];
					}
			  }
			  return $n;
	  }
	  function sortArrays($BaseArray ,$ArrayNum ,$forwardBool){
  		  $newArray=array();
		  $lastSn=  getLastSN2($BaseArray,$ArrayNum);
		  if($forwardBool=="true"){//正向
		  	  for($i=0;$i< $lastSn;$i++){
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
			  if($BaseArray[$i][$Num]==$checkName){
			     array_push($data,$BaseArray[$i]);
			     }
			  }
	       return $data;
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
	   function  getMysqlDataArray($name){
 
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
<?php  //公用
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
	        $seletProject= "<select  style=width:100px; name=".$selectName."   >";
			for($i=0;$i<count($items);$i++){
			     $seletProject=$seletProject."<option value=".$items[$i];
				 if($items[$i]==$selectItem) $seletProject=$seletProject." selected=true ";
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
	   function DrawMembersDragArea( $StartX,$startY ){
		   
		  //   global    $CalendarWidth,$NowHeight,$members, $memberId ;
	       //  $members=getMysqlDataArray("members");
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
	   function DrawLinkRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Link,$border){
	          echo "<div onclick=location.href='".$Link."' style=' cursor:pointer ; color:".$fontColor."; " ;
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
?>

 