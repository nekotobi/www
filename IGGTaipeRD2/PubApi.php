<?php //主功能
	   $id=$_COOKIE['IGG_id'];
	   $rank=$_COOKIE['IGG_Rank'];
	   if($id=="")$id="guest";
	   $colorCodes= GetColorCode();
 
?>

<?php //Array功能
       function sortStringArray($array){ //排列 含有英文的數字陣列
	          $sorts=array();
			  $lastSn=0;
			  for($i=0;$i<count($array);$i++){
			      $int=(int) preg_replace('/[^\d]/','', $array[$i]);
				  $sorts[$int]=$array[$i];
			      if($int>$lastSn)$lastSn= $int;
			  }
			  $r=array(); 
			  for($i=0;$i<=$lastSn;$i++){
				   if( $sorts[$i]!="")array_push($r,$sorts[$i]);
				}
			  return $r;
	   }
       function returnArraybySort($BaseArray,$num){//回傳二維陣列中第幾個陣列的陣列
	           $Ar=array();
			   for($i=0;$i<count($BaseArray);$i++){
			     	 array_push($Ar,$BaseArray[$i][$num]);
			   }
			    return $Ar;
	 }
       function SearchArray($BaseArray,$searchSort,$searchString,$getsort){
		   for($i=0;$i<count($BaseArray);$i++){
		   if($BaseArray[$i][$searchSort]==$searchString)return $BaseArray[$i][$getsort];
		   }
	   }
       function RemoveArray($BaseArray,$num,$RemoveStr){
	            $returnArray=array();
				 for($i=0;$i<count($BaseArray);$i++){
			
					 $n=trim(  $BaseArray[$i][$num]);  
					 if( $n!=$RemoveStr){
						 array_push($returnArray,$BaseArray[$i] );
						 	//	 echo $BaseArray[$i][$num].">[".$RemoveStr."]";
					 }
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
 	   function sortGDCodeArrays($BaseArray ,$ArrayNum ,$forwardBool){ //排列GD流水號
  		  $newArray=array(array() );
		  $lastSn= getLastGDSN($BaseArray ,$ArrayNum ) ; 
		  $n=0;
		  if($forwardBool=="true"){//正向
		  	  for($i=0;$i<= $lastSn;$i++){
                 $tmpArray=  GetGDArraySn($BaseArray, $ArrayNum ,$i);
			 	 if(count($tmpArray)!=0){
				 	 $newArray[$n ]=  $tmpArray ; 
					 $n+=1;
				 }
				
				   // $newArray=  array_merge( $newArray,$tmpArray); 
			   
			  } 
		  }
		  if($forwardBool=="false"){//逆向
		  	  for( $i=$lastSn;$i>0;$i--){
                 $tmpArray=  GetGDArraySn($BaseArray, $ArrayNum ,$i );
				 if(count($tmpArray)>0)$newArray=  array_merge( $newArray,$tmpArray); 
			  }
		  }
	      return  $newArray;
	  }
	   function  GetGDArraySn($BaseArray, $ArrayNum ,$num){//取得數字的GD編碼
	         for($i=0;$i<count($BaseArray);$i++){
			     $n=  returnGDCode2Int($BaseArray[$i][$ArrayNum]);
				 if($n==$num)return $BaseArray[$i];
			 }
			 return null;
	   }
	  
	  //======================
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
					if($strBase!="" && $srtCheck!="" ){
			           if(strpos($strBase,$srtCheck) !== false) array_push($data,$BaseArray[$i]);
                       }
			    }
	       return $data;
	   }
	   function SortArraybyNameArray($baseArr,$SortNameArr,$Num){
		        $arr=array();
	            for($i=0;$i<count($SortNameArr);$i++){
					$ar = filterArray($baseArr,$Num,$SortNameArr[$i]);
				    $arr= addArray($arr,$ar);
				}
				return $arr;
	   }
	
	   
	   
	   function addArray($Base,$Add){
	         $a=$Base;
			 for($i=0;$i<count($Add);$i++){
			     array_push($Base,$Add[$i]);
			 }
			 return $Base;
	   }
	   function CollectArrayDifferent($baseArr,$num){
		     $arr=array();
			 for($i=0;$i<count($baseArr);$i++){
			     if (!in_array($baseArr[$i][$num], $arr)) {
                      array_push($arr,$baseArr[$i][$num]);
                     }
			 }
			 return $arr;
	   }
	   function SortArrayDate($BaseData,$num){
				$sy=2019;
				$sortDate=array();
			 
				for($i=0;$i<count($BaseData);$i++){
				    $date= $BaseData[$i][$num];
					$dates= explode("_", $date);
					$y=$dates[0]-$sy;
					$m=$dates[1];
					$d=$dates[2];
					$t=$y*365+$m*30+$d;
					$BaseData[$i]["days"]=$t;
					if(!in_array($t,$sortDate )	)array_push($sortDate,$t);
 
					 
				}
				 sort($sortDate);
				 $arr=array();
				 for($i=0;$i<count($sortDate);$i++){
				     foreach($BaseData as $t){
					      if($t["days"]==$sortDate[$i])array_push($arr,$t);
					}
				}
			//	print_r($BaseData );
				return $arr;
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
	  function getLastGDSN($Arr,$SnNum){//找回含有英文編碼的最後編碼
	      $lastSN=0;
		  for($i=0;$i<count($Arr);$i++){
		       $int= returnGDCode2Int( $Arr[$i][$SnNum]);
		        if($int>$lastSN)
				$lastSN=$int;
		      }
		  return $lastSN;
	   }
	   function returnGDCode2Int($GDcode){ // 將含有英文編碼轉成數字
			    $s=(int) preg_replace('/[^\d]/','',  $GDcode);
		        $int=intval($s);   
				return $int;
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

?>

<?php  //會員相關資料
	  function DrawUserData($x,$startY){
	        global $id,$colorCodes,$startY;
	        DrawRect("" ,"12","#ffffff",$x+10,$startY+10,160,20, $colorCodes[0][0]);
	        DrawText("使用者",$x+30,$startY+12,100,20,12, $colorCodes[5][2]);
			DrawRect( $id,"12",$colorCodes[0][0]  ,$x+72,$startY+12,60,16, $colorCodes[5][2]);
			DrawLinkPic("Pics/home.png",$startY+12,$x+10,16,16,"index.php");
			if($id!="guest"){
				$URL="Login.php";
				$pic="Pics/Logout.png";
				$Rect=array($x+150,$startY+12,16,16);
				$ValArray=array(array("Logout","true"));
			    sendValPic($URL,$pic,$Rect,$ValArray);
		       //	DrawLinkPic("Pics/Logout.png",$startY+12,$x+150,16,16,"Login.php?Logout=true");
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
	  function echoBr($count){
	    for($i=0;$i<$count;$i++)echo "</br>";
	  }
	  
?>

<?php  //phpinput表格用
       function sendInputHiddenVal($sendArrays){
		   for($i=0;$i<count($sendArrays);$i++){
		   echo "<input type=hidden name=".$sendArrays[$i][0]." value=".$sendArrays[$i][1]." >";
		   }
	   }
	   function MakeSelectionV2($items,$selectItem,$selectName,$size){
		    $selectItemf=trim(  $selectItem);
	        //$seletProject= "<select   style=width:100px; height:".$size."px;  name=".$selectName."    >";
			  $seletProject= "<select  class=form-control  style=font-size:".$size."px; color: red;  id=".$selectName."  name=".$selectName."  >";
			$seletProject=$seletProject."<option value=未定義 >未定義</option>";
			for($i=0;$i<count($items);$i++){
				//echo $items[$i];
				 $itemf=trim( $items[$i]);
			     $seletProject=$seletProject."<option value='".$items[$i];
				 if($itemf== $selectItemf){
					 $seletProject=$seletProject."' selected=true ";
				 }
				 $seletProject=$seletProject."'>".$items[$i]."</option>";
			}
			$seletProject="'".$seletProject."'</select>";
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
       function DrawPopBG($x,$y,$w,$h,$title,$fontSize,$BackURL,$PostArray=array()){
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

<?php //NewSend
       function  DrawPopBGsendVal($x,$y,$w,$h,$title,$fontSize,$BackURL,$ValArray){
	         DrawPicBG("Pics/Black50Bg.png",$y-40,$x-40,$w+80,$h+80);
		     //  DrawLinkPic("Pics/Cancel.png",$y-50,$x+$w+20,32,32,$BackURL);
		     $pic="Pics/Cancel.png";
		     
		     DrawRect($title,$fontSize,"#ffffff",$x ,$y ,$w,"20","#a27e7e");
		     $Rect=array($x+$w+20,$y-50,40,40);
		     sendValPic($BackURL,$pic,$Rect,$ValArray);
	   }
	   function  sendValPic($URL,$pic,$Rect,$ValArray){
	             echo "<form action=".$URL." method=post >";
	             for($i=0;$i<count($ValArray);$i++){
		             echo "<input type=hidden name='".$ValArray[$i][0]."' value='".$ValArray[$i][1]."' >";
		            } 
				    echo "<input type=hidden name=setCookie value=true >";
	                $submitP="<input type=image src=".$pic."   alt='Submit Form'  width=".$Rect[2]." height=".$Rect[3]." />";
		            echo "<div style= 'position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px; 
			                   width:".$Rect[2]."px;   height:".$Rect[3]."px;
		            	'>".$submitP."</div>";
			        echo "</form>";
	    }
       function  DrawLinkRect_Layer2sendVal($msg,$fontSize,$fontColor,$Rect,$BgColor,$Link,$border,$Layer){
		         $tmp=explode("?",$Link);
                 $ValArray=LinkURL2ValArray($Link);
				 $URL=$tmp[0];
				 $SubmitName="Linkdata";
				 $SubmitVal=$msg;
				 //$Rect=array($x,$y,$w,$h);
	             sendVal($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,$fontSize, $BgColor ,$fontColor ,"true");
	   }
       function  DrawLinkRect2sendVal($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$Link,$border){
		        $tmp=explode("?",$Link);
                 $ValArray=LinkURL2ValArray($Link);
				 $URL=$tmp[0];
				 $SubmitName="Linkdata";
				 $SubmitVal=$msg;
				 $Rect=array($x,$y,$w,$h);
	             sendVal($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,$fontSize, $BgColor ,$fontColor ,"true");
	   }
       function  LinkURL2ValArray($Link){
		   		  $tmp=explode("?",$Link);
				 $tmp2=explode("&",$tmp[1]);
	             $ValArray=array();
				 for($i=0;$i<count( $tmp2);$i++){
					  $tmp3=explode("=",$tmp2[$i]);
					  if(count($tmp3)==2)    array_push( $ValArray,array($tmp3[0],$tmp3[1]));
				 }
				 return $ValArray;
	   }
	   function  sendVal_v2($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,$size=12, $BgColor="#eeeeee",$fontColor="#ffffff",$setCookie=false){
		          echo "<form id=test action=".$URL." method=post >";
				  for($i=0;$i<count($ValArray);$i++){
			           echo "<input type=hidden name='".$ValArray[$i][0]."' value='".$ValArray[$i][1]."' >";
		             }
				  $submitP="<input type=submit name=submit2   value=".$SubmitVal." 
			           style = 'width:".$Rect[2]."px; height:".$Rect[3]."px; background-color:".$BgColor." ;
       	               font-size:".$size."px; font-weight:bold; border:0; color:".$fontColor.";  '/>";  
				 
		          echo "<div style= 'position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  '>".$submitP."</div>";
				  echo "</form>";
		}
       function  sendVal($URL,$ValArray,$SubmitName,$SubmitVal,$Rect,$size=12, $BgColor="#eeeeee",$fontColor="#ffffff",$setCookie=false){
		         echo "<form id=sendval action=".$URL." method=post >";
			      for($i=0;$i<count($ValArray);$i++){
			           echo "<input type=hidden name='".$ValArray[$i][0]."' value='".$ValArray[$i][1]."' >";
		             }
	             echo "<input type=hidden name=setCookie value=".$setCookie." >";
		         $submitP="<input type=submit name=".$SubmitName."   value=".$SubmitVal." 
			           style = 'width:".$Rect[2]."px; height:".$Rect[3]."px; background-color:".$BgColor." ; 
					    text-align:left;    
       	               font-size:".$size."px; font-weight:bold; border:0; color:".$fontColor.";  '/>";  
		         echo "<div style= 'position:absolute;  top:".$Rect[1]."px; left:".$Rect[0]."px;  '>".$submitP."</div>";
		         echo "</form>";
	   }  
	   function  setcookies($CookieArray,$BackURL){
	           if($_POST['setCookie']!="true") return;
		       for($i=0;$i<count($CookieArray);$i++){
			       $n=$CookieArray[$i];
				   if($_POST[$n]!=""){
		         //  echo "[".$n."=".$_POST[$n];
		            setcookie($n , $_POST[$n], time()+78000); 
				   }
		          }
      //  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";	 
        }
	   function  setcookiesForce($CookieArray,$BackURL){
		       for($i=0;$i<count($CookieArray);$i++){
			       $n=$CookieArray[$i][0];
				   $j=$CookieArray[$i][1];
	           	   echo "[".$n."=".$j;
		           setcookie($n , $j, time()+78000); 
		          }
			
      //  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";	 
        }
	   function  SetGlobalcookieData($cookieArray){
			  for($i=0;$i<count($cookieArray);$i++){
			      $n=$cookieArray[$i];
				  global $$n;
				  $$n=$_COOKIE[$n];
				  if($_POST[$n]!=""){
					  $$n= $_POST[$n];
					 // echo ">".$_POST[$n];
				  }
				//  echo $n."=".$$n."]";
			  }
	}
	   function  CheckCookie($CookieArray){
	          for($i=0;$i<count($CookieArray);$i++){
		     $n=$CookieArray[$i];
		     echo  $n."=".$_COOKIE[$n],"]";
	  }
	    }
	   function  JavasubmitForm($URL,$ValArray){
		         echo "<br>send";
	             echo "<form action=".$URL." enctype=multipart/form-data name=Javaform id=Javaform  >";
			     for($i=0;$i<count($ValArray);$i++){
		               echo "<input type=hidden name='".$ValArray[$i][0]."' value='".$ValArray[$i][1]."' >";
		            } 
			     echo "<input type=hidden name=setCookie value=true >";   
			     echo "</form>";
			     echo " <script language='JavaScript'>Javaform.submit()</script>";
			     echo "ss";
	   }
 
	 	   function upSubmitform($upFormVal,$UpHidenVal, $inputVal){
	          //  $upFormVal ==>0/id 1/name 2/URL 
			  //  $UpHidenVal=array 0/name,1/val
			  //  $inputVal=0/type 1/name 2/showname 3/fontsize 4/5/6/7rect  8/bgcolor 9/fontColor 10/val 11/size
			  echo  "<form id=".$upFormVal[0]."  name=".$upFormVal[1]." action=".$upFormVal[2]." method='post'>";
			  for($i=0;$i<count($UpHidenVal);$i++){
			       echo   "<input  type=hidden id=".$UpHidenVal[$i][0]."  name=".$UpHidenVal[$i][0]." value=".$UpHidenVal[$i][1].">"; 
				   //echo   "</br>".$UpHidenVal[$i][0]." value=".$UpHidenVal[$i][1];
			  }
			  for($i=0;$i<count($inputVal);$i++){
			      $input="<input  type=".$inputVal[$i][0]."  id=".$inputVal[$i][1]."  name=".$inputVal[$i][1]."
  				          style='font-size:".$inputVal[$i][3]."px'; value='".$inputVal[$i][10]."' size=".$inputVal[$i][11]."  >";
				  $x=$inputVal[$i][4];
				  $y=$inputVal[$i][5];
				  $w=$inputVal[$i][6];
				  $h=$inputVal[$i][7];
				  $msg=$inputVal[$i][2];
				  $WorldAlign="top";
				  $fontSize=$inputVal[$i][3];
				  $fontColor=$inputVal[$i][9];
				  $BgColor=$inputVal[$i][8];
				  DrawInputRect_size($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor,$WorldAlign,$input);
			  }
			  echo "<form>";
	 }
?> 
 
<?php //排序GD
     function SortList($base,$num){
	      $sorta=array();
		  $nums=array();
		  for($i=0;$i<count($base);$i++){
			 $base[$i][13]= codeRnum($base[$i][2]);
		  }
          $sorta=sortArrays( $base ,13 ,"true");
		  return $sorta;
	}
     function codeRnum($string){
	        $a= ereg_replace("[a-zA-Z]","",$string);
			return(int) $a;
	}
?> 
 
<?php //建立資料夾
     function MakeDir($path){
	        $str = explode("/",$path);
			$dir =$str[0];
			if(!file_exists($dir)) mkdir($dir, 0700);
			for($i=1;$i<count($str);$i++){
				$dir=$dir."/".$str[$i];
				if(!file_exists($dir)) mkdir($dir, 0700);
				// echo $dir."</br>";
			}
	 }
    
?>
<?php //table
     function DrawTable($tableArray,$x,$y,$fontColor,$fontSize,$bgcolor){
              echo "<div  style=' color:".$fontColor."; "  ;	
			  echo " text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
              echo " position:absolute;  top:".$y."px; left:".$x."px; width:".$w."px;height:".$hi."px; background-color:".$BgColor.";'>"; 	  
			  echo "<table>";
			  echo "<tr>";
			  for($i=0;$i<count($tableArray);$i++){
				  $bc=$tableArray[$i][2];
				  if($bc=="")$bc=$bgcolor;
				  $fc=$tableArray[$i][3];
				  if($fc=="")$fc=$fontColor;
			  	  echo "<td   bgcolor=".$bc." width=".$tableArray[$i][1]."px>
				        <font size=".$fontSize." color=".$fc."><font>";
			      echo $tableArray[$i][0];
			      echo "</td>";
			  }
              echo "</tr>";
              echo "</table>";
			  echo "</div>";
 }
?>


<?php //中文資料夾轉換相關
 function ReturnPhpDir($BasePath){
     $strs=explode("/",$BasePath);
	 $str="";
	 $dir=$strs[0]."/";
	 for($i=1;$i<count($strs);$i++){
		 $dirAss= GetDirCode($dir,$strs[$i]);
		 $dir= $dir."/".$dirAss;
	 }
	 return $dir;
 }
 function GetDirCode($dir,$name){
      $file= scandir($dir);
      for($i=0;$i<count($file);$i++){
	      $fn=iconv("BIG5", "UTF-8",$file[$i]);
          if($fn==$name)return $file[$i];
        }
 }

?>