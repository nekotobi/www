<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工單紀錄搜尋</title>
</head>
 <body bgcolor="#b5c4b1"> 
<?php //主控台
    require_once('PubApi.php');
    require_once('mysqlApi.php');
	require_once('CalendarApi.php');
    require_once('VTApi.php');
	 
	 DefineDatas();
	 checkSubmit();
	if($_POST["submit"]==""){
	    DrawButtons();
	    ListRangeSC();
        SortFinTask();
    }
	 fastTask();
?>
<?php //主控台
     function DefineDatas(){ 			  
	          global $URL;
			  $URL="taskSearch.php";
	          //選擇時間
		      global $typeArray,$typeRangeArray;
			  $typeArray =array("StartY","StartM","Range");
			  $typeRangeArray=array(array("2019","2020","2021"),
			                 array("1","2","3","4","5","6","7","8","9","10","11","12"),
							 array("1","2","3","4","5","6","7","8","9","10","11","12"));
			  $defuseData=array(date("Y"),date("n"),1);
			  global $typeVal;
			  for($i=0;$i<count($typeArray);$i++){
			      $typeVal[$i]=array($typeArray[$i],$_POST[$typeArray[$i]]);
				  if($_POST[$typeArray[$i]]=="")  $typeVal[$i]=array($typeArray[$i],$defuseData[$i]);
			  }
		      //取得工單區間
			  global $getDateRanges,$weekArray;
			  SortFinTask();
			  //取得工單資料
			  global $RangeScData;
			  global $TaskTitle;
              global $fpschedule;
		 	  $fpschedule=getVTSCData("mix");
			  $TaskTitle= filterArray($fpschedule,5,"工項");
			  $filterData=CollectRangeSchedule($fpschedule,2,$getDateRanges,$weekArray);
			  $RangeScData=$filterData;
			  //取得工做分類
			  global $WorkSort;
			  $WorkSort= getSCTypes("data");
			      echo count( $WorkSort);
	 }
     
?> 
<?php //search
     function fastTask(){
	          $x=20;
			  $y=10;
			  global $URL;
			  global $typeName,$typeArray;
			  $BgColor="#ffffff";
			  $fontColor="#000000";
			  $upFormVal=array("search","search",$URL);
			  $UpHidenVal=array(array("tablename","fpschedule"),
			                    array("data_type","data"),
							    );			
			  $UpHidenVal=		addArray( $UpHidenVal,$typeArray);			
			  $inputVal=array(array("text","search","",10,20,$y,120,20,$BgColor,$fontColor,"" ,30),
                              array("submit","submit","",10,200,$y,50,20,$BgColor,$fontColor,"搜尋" ,20),
							  array("submit","submit","",10,250,$y,50,20,$BgColor,$fontColor,"負責人" ,20),
							  array("submit","submit","",10,300,$y,100,20,$BgColor,$fontColor,"外包" ,20),
							  array("submit","submit","",10,350,$y,100,20,$BgColor,$fontColor,"Jila" ,20),
			                  );					  
			  upSubmitform($upFormVal,$UpHidenVal, $inputVal);
	 }
	 function checkSubmit(){
	          if($_POST["submit"]=="")return;
			  global $fpschedule;	 
			  $s=3;
			  if($_POST["submit"]=="負責人")$s=8;
			  if($_POST["submit"]=="外包")$s=9;
	          if($_POST["submit"]=="Jila")$s=12;
			  global $searchScs;
			  $searchScs=filterArrayContain($fpschedule,$s,$_POST["search"]);
			  echo "</br>";
			//  echo count($searchScs);
			//  echo $_POST["search"];
			  ListSCs();
	 }
	 function ListSCs(){
	          global $searchScs;
			  global $fpschedule;
		      echo "</br>";
			  $x=20;
			  global $gy;
			  $gy=100;
			  for($i=0;$i<count($searchScs);$i++){
				  if($searchScs[$i][5]=="工項"){
			      $arrays=filterArray($fpschedule,3,$searchScs[$i][1]);
				  DrawRect($searchScs[$i][3],10,"#ffffff",$x,$gy,500,20,"#000000");
				  $gy+=20;
				  ListSearchTasks($arrays,$x,$searchScs[$i][12]);
				  }else{
				   $name= getTaskTitle($searchScs[$i][3]);
				    DrawRect( $name,10,"#ffffff",$x,$gy,500,20,"#000000");
				  }
				 // ListTasks($arrays,$x,$y);
			  	 // echo "</br>";
			  }
	 }
     function ListSearchTasks($arrays,$x,$j ){
		      global $gy;
	          for($i=0;$i<count($arrays);$i++){
				//  $j=$arrays[$i][12];
				  if($arrays[$i][16]!="")$j=$arrays[$i][16];
				  $msg="[".$j."]"."[".$arrays[$i][5]."]"."[".$arrays[$i][2]."][".$arrays[$i][6]."]".$arrays[$i][8]."-".$arrays[$i][9];
		          DrawRect($msg,10,"#000000",$x,$gy,500,18,"#bbbbbb");
		     	  $gy+=20;
		       }
			
	 }
?>
<?php //UI
    function DrawButtons(){
             global $typeArray,$typeRangeArray; 
			 $y=40;
			 for($i=0;$i<count($typeArray)-1;$i++){
			      DrawButton($typeArray[$i],$i,$typeRangeArray[$i],20,$y+$i*20);
			 }				 
	}
	function DrawButton($typeName,$tn,$typeRangeArray,$x,$y){
			 $type=$_POST[$typeName];
			 global $URL;
		     global $typeVal;
			 $sendarr=$typeVal;
			 $fontColor="#ffffff";
	         for($i=0;$i<count($typeRangeArray) ;$i++){
			      $BgColor="#111111";
				  if($typeVal[$tn][1]==$typeRangeArray[$i])$BgColor="#aa1111";
				   $sendarr[$tn]= array($typeName,$typeRangeArray[$i])  ;
				   sendVal($URL, $sendarr,"change",$typeRangeArray[$i],array($x,$y,46,18),10,$BgColor, $fontColor);
				   $x+=50;
			 }				 
	}
	function ListRangeSC(){
		       global $RangeScData;
			   global $weekArray;
			   $y=100;
			   $fontColor="#ffffff";
			   $w=800;
			   $h=18;
		   	   $BgColor="#aaaaaa";
			   global $listY;
			   $listY=100;
			   $color=array("#aaaaaa","#bbbbbb","#aaaaaa");
			   for($i=0;$i<count($RangeScData);$i++){
		 
				    $c=$i%2;
				    $listY+=20;
					$msg=$i."[".$weekArray[$i][0].">".$weekArray[$i][6];
				    DrawRect($msg,10,$fontColor,$x,$listY,$w,$h,"#000000"); 
			        DrawWeeks_b($RangeScData[$i],$color[$c],$i);
			   }
			 
	}
	function DrawWeeks_b($data,$BgColor,$n ){
		     global $listY;
		     $x=20;
			 $w=800;
			 $h=14;
		     for($i=1;$i<count($data);$i++){
				   //$msg=$n.">".$data[$i][0][0];
				  // DrawRect($msg,10,$fontColor,$x,$listY,$w,$h,$BgColor);
				   if(count($data[$i])>1){
					   ListTasks($data[$i],$x);
				 }
			 }
	}
   function DrawWeeks($data,$BgColor,$n ){
		     global $listY;
		     $x=20;
			 $w=800;
			 $h=14;
			 $tasks=array();
			 echo ">".count($data);
		     for($i=1;$i<count($data);$i++){
				   if(count($data[$i])>1){
					  if( count($tasks)==0) $tasks=$data[$i];
					  if( count($tasks)>0)  addArray($tasks,$data[$i]);
				 }
			 }
		     $sortTask= SortWorks($tasks);
			 ListTasks( $sortTask,$x);
			// ListTasks_B($task,$x);
	}
	
	
	
	function ListTasks_B($task,$x){
	         global $listY;
		     $x=20;
			 $w=500;
			 $h=14;
	         for($i=1;$i<count($task);$i++){
		         $listY+=16;
				 $BgColor="#cccccc";
				 if($task[$i][7]!="已完成")$BgColor="#ffcccc";
				 $msg= "{".$task[$i][5]."}".$task[$i][21];
				 DrawRect($msg,10,$fontColor,$x,$listY,$w,$h,$BgColor);
				 $jila=$task[$i][12];
				 if($jila=="")$jila=$task[$i][22];
		         $Link="http://bzbfzjira.iggcn.com/browse/FP-".$jila;
				 DrawLinkRect($jila,10,"#ffffff",$x, $listY,50,12,"#ffaaaa",$Link,$border);
			     DrawRect( $task[$i][2]."[".$task[$i][6]."]",10,$fontColor,$x+500,$listY,60,$h,"#aaaaaa");
	           }
	}
	 function ListTasks( $sortTask,$x){
	         global $listY;
		     $x=20;
			 $w=500;
			 $h=14;
		   
			 for($i=0;$i<count($sortTask);$i++){
				 $listY+=16;
				 $BgColor="#cccccc";
				 $msg=$i.$sortTask[$i][0];
				 if(count($sortTask[$i])>2){
				    $msg= "{".$sortTask[$i][5]."}".$sortTask[$i][21];
				 }
				 DrawRect($msg,10,$fontColor,$x,$listY,$w,$h,$BgColor);
			 }
	     
	}
?>
<?php //function
     function SortWorks($task){
		      global $WorkSort;
			  $SArray=array(array("st",""));
			  for($i=0;$i<count($WorkSort);$i++){
				  array_push( $SArray,array($WorkSort[$i],"t"));
			      $SArray=  JoinTaskWorks($SArray ,$task,$WorkSort[$i]);
			     }
				 return $SArray;
	 }
	 function JoinTaskWorks($Base, $task,$type){
			  for($i=0;$i<count($task);$i++){
			      if($task[$i][10]==$type)
				  array_push($Base,$task[$i]);
			  }
			  return $Base;
	 }
     function SortFinTask(){
	          global $typeVal;
			  global $getDateRanges,$weekArray;
			  $sy=$typeVal[0][1];
			  $sm=$typeVal[1][1];
			  $swd= date("w",strtotime($sy."-".$sm."-1")) ;
			  $startdate=date("Y-m-d",strtotime("-". $swd." day",strtotime($sy."-".$sm."-1")));
			  $weekArray=array();
			  for($i=0;$i<5;$i++){
				  array_push($weekArray,returnweekArray($startdate,$i*7));
			  }
			  $getDateRanges=getDateRanges($sy,$sm);
	 }
	 function getDateRanges($sy,$sm){
		      $st=$sy."-".$sm."-1";
			  $sd=date("Y_m",strtotime("-1 month",strtotime($st)));
			  $ed=date("Y_m",strtotime("+1 month",strtotime($st)));
	          $dateRange=array($sd,$sy."_".$sm,$ed);
			  return $dateRange;
	 }
	 function returnweekArray($startdate,$ssd){
		      $Wa=array();
		      for($i=0;$i<7;$i++){
			  	  $d=date("Y_m_d",strtotime("+".$i+$ssd." day",strtotime($startdate)));
                   array_push( $Wa,$d);
			  }
			  return $Wa;
	 }
	 function CollectRangeSchedule($data,$num,$getDateRanges,$weekArray){
 
			  $filterData=array();
			  for($i=0;$i<count($data);$i++){
				  $strs=explode("_",$data[$i][$num]);
				  $str=$strs[0]."_".$strs[1];
				  if($data[$i][5]!="工項"){
			         if(in_array($str, $getDateRanges)){ 
				      $add=$data[$i];
					  //加入完成日
					  $d=  $strs[0]."-".$strs[1]."-".$strs[2];
					  $finday =date("Y_m_d",strtotime("+".$data[$i][6]."day",strtotime($d)));
				      array_push($add,$finday);
				      //取得工項
					  $MainTask= getTaskTitle($data[$i][3]);
				      array_push($add, $MainTask[3]);
					  array_push($add, $MainTask[12]);
				      array_push( $filterData,$add);
				  }
				  }
			  }
			  $sortData=SortRangeSchedules($filterData,$weekArray);
			  return $sortData;
			 
	 }
	 function getTaskTitle($code){
	          global  $TaskTitle;
			  foreach($TaskTitle as $t){
			  if($t[1]==$code)return $t ;
			  }
			  return "null";
	 }
	 function SortRangeSchedules($filterData,$weekArray){
	          $sortData=array();
			  for($i=0;$i<count($weekArray);$i++){
			       array_push(  $sortData , SortRangeSchedule($filterData,$weekArray[$i],$i));
			  }
			  return $sortData;
	 }
     function SortRangeSchedule($filterData,$weekArraysingle,$w){
		      $ar=array();
		      for($i=0;$i<count($weekArraysingle);$i++){
			       array_push( $ar , getTask($filterData,  $weekArraysingle[$i] ,$w));
		      }
			  return $ar;
	  }
	 function getTask($filterData,$date){
		      $a=array();
			  array_push($a,array( $date,$w ));
	          for($i=0;$i<count($filterData);$i++){
			       if($filterData[$i][20]==$date)array_push($a,$filterData[$i]);
			  }
			  return $a;
	 }
	 /*
     function CollectRangeSchedule($data,$num, $StartY,$StartM,$Range){
	          $checkArray=array();
			  for($i=0;$i<$Range;$i++){
			      array_push( $checkArray,$StartY."_".$StartM);
				  $StartM+=1 ;
				  if($StartM>12){
					  $StartM=1;
					  $StartY+=1;
				  }
			  }
		      $filterData=array();
			  for($i=0;$i<count($data);$i++){
				  $strs=explode("_",$data[$i][$num]);
				  $str=$strs[0]."_".$strs[1];
			      if(in_array($str,   $checkArray)){ 
				  array_push( $filterData,$data[$i]);
				  }
			  }
			  return $filterData;
	 }
	 */
?>
<?php //備份
/*
	 function CollectRangeSchedule($data,$num,$getDateRanges,$weekArray){
			  $filterData=array();
			  for($i=0;$i<count($data);$i++){
				  $strs=explode("_",$data[$i][$num]);
				  $str=$strs[0]."_".$strs[1];
				  if($data[$i][5]!="工項"){
			         if(in_array($str, $getDateRanges)){ 
				      $add=$data[$i];
					  //加入完成日
					  $d=  $strs[0]."-".$strs[1]."-".$strs[2];
					  $finday =date("Y_m_d",strtotime("+".$data[$i][6]."day",strtotime($d)));
				      array_push($add,$finday);
				      //取得工項
					  $MainTask= getTaskTitle($data[$i][3]);
				      array_push($add, $MainTask[3]);
					  array_push($add, $MainTask[12]);
				      array_push( $filterData,$add);
				  }
				  }
			  }
			  $sortData=SortRangeSchedules($filterData,$weekArray);
			  return $sortData;
	 }
	 */
?>