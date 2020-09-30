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
	  checkSubmit();
     DefineBaseData();
	 DrawCalendar();
	 DrawDragUpAreas();
	 ListRes();
 
 
?>

<?php
     function checkSubmit(){
	   if ($_POST["Restype2"]){
		    CheckDrag();
	   }
	 }
     function DefineBaseData(){
 
			  DefineVTTableName();
	          DefineArrayDatas();
	 
     }
	 function DrawButtoms($typeArray,$typeVal){
			  global $URL;
			  $x=20;
			  $y=0;
			  for($i=0;$i<count( $typeArray);$i++){
				 $BgColor="#111111";
				 if($typeVal==$typeArray[$i][1])$BgColor="#aa1111";
			       $sendarr =array( array("Restype",$typeArray[$i][1]))  ;
				   sendVal_v2($URL, $sendarr,"check",$typeArray[$i][0],array($x+$i*50,$y,46,18),10, $BgColor );
			  }
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
			  $Restype=$_POST["Restype"];
			  if($Restype=="")$Restype="hero";
			  $typeArray=array(array("英雄","hero"),array("覺醒","awake"),array("怪物","mob"),array("Boss","boss"));
			  $SC_nowArray= getVTSCData("mix");
			  global $Res_Array;
			  $Res_Array=getMysqlDataArray($Res_tableName);
			  global $FocusRes;
			  $FocusRes= getFocusRes();  //0GDcode 1剩幾天 2主工項code,3主工項名
			  global $startX,$startRX ,$startY,$wid;
			  $startX=300;
			  $startY=100;
			  $startRX=60;
			  $wid=6;
			  global $ResTypes;
			  $ResTypes= getResSorType($Res_Array,$Restype); 
			  VTCreatJavaForm( $URL,$tableName);
			  DrawButtoms( $typeArray, $Restype);
	 }
     function DrawDragUpAreas(){ //
	          global $startY;
			  $startX=20;
			  $wid=35;
			  //  array("進行中","已排程","驗證中","已完成");
           	  $Typestmp=getMysqlDataArray("scheduletype"); 
		      $arrT=filterArray( $Typestmp,0,"data3");
			  $arr=returnArraybySort($arrT,2);
			  DrawDragUpArea($arr,$startX,$startY-20,$wid,"state");
			  // 內部
			  $membersT=getMysqlDataArray("members"); 
			  $membersT2=filterArray($membersT,3,"Art");
			  $members=returnArraybySort( $membersT2,1);
			  array_Push($members,"--");
			  DrawDragUpArea($members,$startX,$startY-60,$wid,"principal");
			  //外部
			  $OutsT=getMysqlDataArray("outsourcing"); 
			  $OutsT2=filterArray($OutsT,35,"true");
			  $Outs=returnArraybySort( $OutsT2,2);
			  array_Push( $Outs,"--");
		      DrawDragUpArea($Outs,$startX,$startY-40,$wid,"outsourcing");
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
		      global $startX,$startRX ,$startY,$wid;
			  global $Restype;
			  $BgColor="#222222";
			  $fontColor="#ffffff";
			  $fontSize=10;
			  $dir=$Restype;
 
			  for($i=0;$i<count($FocusRes);$i++){
			      $msg= $FocusRes[$i][3]."[".$FocusRes[$i][1]."]" ;
				  $pic= returnPic($Restype,$FocusRes[$i][0] );
				  
				  DrawRect( "",10,"#ffffff",$startX ,$startY,$wid*($FocusRes[$i][1]+date("j")),6,"#aa8888");//死線
				  DrawRect( "",10,"#ffffff",$startX ,$startY,1200,1,"#777777");//分隔線
				  
				  DrawRect($msg,$fontSize,$fontColor,60,$startY,230,18,$BgColor);
				  DrawLinkPic($pic,$startY,20,38,38,$pic);
			      DrawTasks($FocusRes[$i][2],$FocusRes[$i] );
				  $startY+=40; 
			  }
	 }
	 function DrawTasks($TaskCode ,$ResCode){
	          global $SC_nowArray;
			  global $startX ,$startRX, $startY;
			  global $wid;
			  global $colorCodes;
	 	      $tasksT=filterArray($SC_nowArray,3,$TaskCode);
		      global  $Restype;	
			  if($Restype=="awake") $tasksT=filterArray($tasksT,13,"awake");
			  $tasks=sortTask($tasksT);
			  $BgColor="#88aa88";
			  $BgColor2="#995555";
			  $fontColor="#ffffff";
			  $fontSize=9;
			  $x=60;
		      $startDate=date("Y-m-1");
			  $yadd=0;
			  $upf=0;
			  $pic="pics/warring.gif";
			  for($i=0;$i<count($tasks);$i++){
				  $type=$tasks[$i][5];
				  $WorkDays= $tasks[$i][6];
				  if($WorkDays<=0)$WorkDays=2;
				  $show=  substr($type,0, $WorkDays+2);
				  $BgColor=$colorCodes[11][$i];
				  $c=ColorDarker( $BgColor,122);
			       if(count($tasks[$i])<2  ){ //未登錄
				  	 $id= "N=".$tasks[$i][0]."=".$ResCode[2]  ;
					 VTDrawJavaDragbox( $tasks[$i][0] ,$startRX+$i*30,$startY+20,28,18,9, $BgColor, $fontColor,$id);
					 if($upf==1){//前項目已完成
					  DrawPosPic($pic,$startY+20,$startRX+$i*30,12,12,"fixed" );
					  $upf=0;
					 }
			         }else{
				  if(count($tasks[$i])>2  ){
			         if($tasks[$i][7]=="已完成") { 
					 $c="#aaaaaa";
					 DrawRect($tasks[$i][5],$fontSize,$fontColor,$startRX+$i*30,$startY+20,28,18,$c);
					 $upf=1;
					 }else{
						   $upf=0;
					 $date=$tasks[$i][2];
					 $xAdd=returnLocX($date,$startDate);
					 if($xAdd<0){
						// $xAdd=1;
						  $BgColor="#ccaaaa";
						// $WorkDays=4;
					 }
				     $ds=$startX+ 	 $xAdd*$wid ;
					 $id= "S=".$tasks[$i][1]."=".$tasks[$i][6]."=".$wid."=".$tasks[$i][7]."=".$tasks[$i][5];//1工單code.1人天.2寬.3狀態.5類別
					 $yy=$startY+  $yadd;
					 VTDrawJavaDragbox( $show ,$ds,$yy+4,$wid*$WorkDays,14,10,  $BgColor, $fontColor,$id);
					 $id= "E=".$tasks[$i][1]."=".$tasks[$i][6]."=".$wid."=".$tasks[$i][7]."=".$tasks[$i][5]; 
				     $BgColor3="#888888";
			
					 $x=$ds+$wid*($tasks[$i][6] );
				     VTDrawJavaDragbox( "" ,$x,$yy+4,5,15,5, $BgColor3, $fontColor,$id);
					  DrawTaskDetial($tasks[$i],$x ,$yy+4);
					 $f=2;
					 if(  $yadd==0){
						 $yadd=18;
					 }else{
					  $yadd=0;
					 }
					 }
				  }
				  }
				  $x+=30;
			  }
			  $id="DateInfo";
			  VTDrawJavaDragbox( "info"  ,1024,0,100,12,9,"#333333", $fontColor,$id);
	 }
	 function DrawTaskDetial($task,$x,$y){
		      $msg=$task[2]."+".$task[6]."[".$task[5]."]".$task[8]."-".$task[9];
			  $l=strlen(  $msg );
			  DrawRect( "",10,"#ffffff",$x ,$y+8,100,2,"#999999");
		      DrawRect( $msg,8,"#ffffff",$x+100,$y,$l*8,15,"#999999");
	 }
?>
<?php //資源排序
     function sortTask($taskArray){
		      global $ResTypes; 
			  $a=array();
			  for($i=0;$i<count($ResTypes);$i++){  // 5工項  //用13來判斷awake
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
 
     function getFocusRes(){
	          $Ev=  getLaterEventData();
			  global $Restype;
		 	  $type=$Restype;
			  $dataNum=6;
			  if($Restype=="awake"){
			      $dataNum=7;
				   $type="hero";
			  }
			  $res=array();
	          for ($i=0;$i<count($Ev);$i++){
				   $e= returnRequestRes($Ev[$i][$dataNum],$Ev[$i][10],$type);
				   $res=addArray($res,$e);
	          }
			  return $res;
	 }   
	 function returnRequestRes($ResStr,$days,$Type){
		      global $SC_nowArray;
			  global $Restype;
		      $filterStr="h";
			  if($Restype=="mob")$filterStr="m";
			  if($Restype=="boss")$filterStr="b";
			  if($Restype=="Escene")$filterStr="e";
		      $str=explode("_",$ResStr);
			  $ResArray=array();
			  for ($i=0;$i<count($str);$i++){
				  if( strpos($str[$i], $filterStr) !== false){
					 $Maintask=returnMainTask($SC_nowArray,$str[$i]);
					// $taskCode= returnTaskMainCode($SC_nowArray,$str[$i]);
				     Array_Push($ResArray,array($str[$i],$days, $Maintask[1],$Maintask[3]));
					 //0GDcode 1剩幾天 2主工項code,3主工項名
				  }
	          }
			  
			  return $ResArray;
	 }
 

?>


