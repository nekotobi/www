<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
   <title>VT資源索引</title>
</head>
<body bgcolor="#b5c4b1"> 
<?php //主控台
     require_once('PubApi.php');
	 require_once('mysqlApi.php');
	 require_once('scheduleApi.php');
     require_once('VTApi.php');
	 require_once('ResChecker2JavaApi.php');
     DefineBaseData();
	 DrawCalendar();
	 DrawDragArea2();
	 ListRes( );
     CheckDrag();
	
	// saveUpdateTime("",);
   //  ListRes("hero");
	// gettaskName( );
?>

<?php
     function DefineBaseData(){
			  DefineVTTableName();
	          DefineArrayDatas();
     }
	 function DefineArrayDatas(){
	          global $data_library;
	          global $SC_tableName;
	          global $Res_tableName;
			  global $URL;
			  $URL="ResChecker2.php";
			  global $SC_tableName_now,$SC_tableName_old,$SC_tableName_Merge;
			  global $SC_nowArray;
			  global $Restype;
			  
			  $Restype="hero";
			  $SC_nowArray= getVTSCData("mix");
			  global $Res_Array;
			  $Res_Array=getMysqlDataArray($Res_tableName);
			  global $FocusRes;
			  $FocusRes= getFocusRes();
			  global $startX,$startRX ,$startY,$wid;
			  $startX=300;
			  $startY=100;
			  $startRX=60;
			  $wid=6;
			  global $ResTypes;
			  $ResTypes= getResSorType($Res_Array,$Restype); 
			  VTCreatJavaForm( $URL,$tableName);
	 }
	 function  DrawDragArea2(){
		        global $startY;
				$x=20;
				$y=$startY-20;
			    $BgColor="#224444";
			    $fontColor="#ffffff";
			    $Typestmp=getMysqlDataArray("scheduletype"); 
	            $arrT=filterArray( $Typestmp,0,"data3");//  array("進行中","已排程","驗證中","已完成");
			    $arr=returnArraybySort($arrT,2);
				array_Push( $arr,"刪除");
			    for($i=0;$i<count($arr);$i++){
				    $id="state=".$arr[$i];
				    VTDrawJavaDragArea($arr[$i],$x,$y,34,18,$BgColor,$fontColor,$id,9);
					$x+=35;
				}
	  }	 
?>

<?php //List
     function DrawCalendar(){
			        global $startX ,$startY,$wid;
			        global $FocusRes;
			        DrawBaseCalendar(date('Y'),date('n'),6, $startX,$startY-20,$wid,count($FocusRes)*40);
				
            }
     function ListRes(){
	          global $FocusRes;
			  global $startY;
			  global $Restype;
			  $BgColor="#222222";
			  $fontColor="#ffffff";
			  $fontSize=10;
			  for($i=0;$i<count($FocusRes);$i++){
			      $msg=$FocusRes[$i][0]."[".$FocusRes[$i][1] ;
				  $pic= returnPic($Restype,$FocusRes[$i][0]);
				  DrawRect($msg,$fontSize,$fontColor,60,$startY,230,18,$BgColor);
				  DrawLinkPic($pic,$startY,20,38,38,$pic);
			      DrawTasks($FocusRes[$i][2],$FocusRes[$i] );
                  $startY+=40; 
				  
			  }
	 }
	 function DrawTasks($TaskCode ,$ResCode){
	          global $SC_nowArray;
			  global  $startX ,$startRX, $startY;
			  global $wid;
			  global $colorCodes;
	 	      $tasksT=filterArray($SC_nowArray,3,$TaskCode);
			  $tasks=sortTask($tasksT);
			  $BgColor="#88aa88";
			  $BgColor2="#995555";
			  $fontColor="#ffffff";
			  $fontSize=9;
			  $x=60;
		      $startDate=date("Y-m-1");
			  $yadd=0;
			  for($i=0;$i<count($tasks);$i++){
				  $type=$tasks[$i][5];
				  $WorkDays= $tasks[$i][6];
				  if($WorkDays<=0)$WorkDays=2;
				  $show=  substr($type,0, $WorkDays);
				  $BgColor=$colorCodes[11][$i];
				  $c=ColorDarker( $BgColor,122);
			       if(count($tasks[$i])<2  ){ //未登錄
				  	 $id= "N=".$tasks[$i][0]."=".$ResCode[2] ;
					 VTDrawJavaDragbox($tasks[$i][0] ,$startRX+$i*30,$startY+20,28,18,9, $BgColor, $fontColor,$id);
			         }else{
				  if(count($tasks[$i])>2  ){
			         if($tasks[$i][7]=="已完成") { 
					 $c="#aaaaaa";
					 DrawRect($type,$fontSize,$fontColor,$startRX+$i*30,$startY+20,28,18,$c);
					 }else{
					 $date=$tasks[$i][2];
					 $xAdd=returnLocX($date,$startDate);
					 if($xAdd<0){
						// $xAdd=1;
						  $BgColor="#ccaaaa";
						// $WorkDays=4;
					 }
				     $ds=$startX+ 	 $xAdd*$wid ;
					 $id= "S=".$tasks[$i][1]."=".$tasks[$i][6]."=".$wid."=".$tasks[$i][7];
					 $yy=$startY+  $yadd;
					 VTDrawJavaDragbox( $show ,$ds,$yy,$wid*$WorkDays,15,10,  $BgColor, $fontColor,$id);
					 $id= "E=".$tasks[$i][1]."=".$tasks[$i][6]."=".$wid."=".$tasks[$i][7];
				     $BgColor3="#888888";
					 $x=$ds+$wid*($tasks[$i][6] );
				     VTDrawJavaDragbox( "" ,$x,$yy,5,15,5, $BgColor3, $fontColor,$id);
					 $f=2;
					 if(  $yadd==0){
						 $yadd=20;
					 }else{
					  $yadd=0;
					 }
					 }
				  }
				  }
				  $x+=30;
			  }
			  $id="DateInfo";
			  VTDrawJavaDragbox( "info"  ,2,2,100,12,9,"#333333", $fontColor,$id);
	 }
?>
<?php //資源排序
     function sortTask($taskArray){
		      global $ResTypes;
			  $a=array();
			  for($i=0;$i<count($ResTypes);$i++){
				  $ty=$ResTypes[$i];
			      $t=filterArraycontain($taskArray,5,$ty);
				  if(count($t)>0){
				     array_push( $a,$t[0]);
				  }
				  if(count($t)==0){
				     array_push($a,array($ty));
				  }
			  }
			  return $a;
	 }
     function returnLocX($date,$startDate ){
		       $checkDay=strtr($date,"_","-");
			   $n= (strtotime( $checkDay)-strtotime($startDate))/86400;
			   return $n; 
	 }
     function getResSCData($FocusRes){
	          $a=array();
			  for ($i=0;$i<count($FocusRes);$i++){
			       $a= getResSCDataArray($FocusRes[$i][0]);
			  }
	 }
     function getResSCDataArray($code){
	          global $SC_nowArray;
			 
			  $taskCode= returnTaskMainCode($SC_nowArray,$code);
	           echo  "</br>".$taskCode.">".$code;
	 
	 }
     function getFocusRes(){
	          $Ev=  getLaterEventData();
			  global $Restype;
			  $res=array();
	          for ($i=0;$i<count($Ev);$i++){
				   $e= returnRequestRes($Ev[$i][6],$Ev[$i][10],$Restype);
				   $res=addArray($res,$e);
	          }
			 // print_r ($res);
			  return $res;
	 }   
	 function returnRequestRes($ResStr,$days,$Type){
		      global $SC_nowArray;
		      $filterStr="h";
			  if($Restype=="mob")$filterStr="m";
			  if($Restype=="Escene")$filterStr="e";
		      $str=explode("_",$ResStr);
			  $ResArray=array();
			  for ($i=0;$i<count($str);$i++){
				  if( strpos($str[$i], $filterStr) !== false){
					 $taskCode= returnTaskMainCode($SC_nowArray,$str[$i]);
				     Array_Push($ResArray,array($str[$i],$days,$taskCode));
				  }
	          }
			  
			  return $ResArray;
	 }
 

?>


