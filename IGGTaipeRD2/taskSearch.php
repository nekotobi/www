<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工單紀錄搜尋</title>
</head>
 <body bgcolor="#b5c4b1"> 
<?php //主控台
    include('PubApi.php');
    include('mysqlApi.php');
	include('CalendarApi.php');
	DefineDatas();
	DrawButtons();
	
	 ListRangeSC();
	 SortFinTask();
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
			  global  $TaskTitle;
			  $fpschedule=getMysqlDataArray("fpschedule");
			  $TaskTitle= filterArray($fpschedule,5,"工項");
			  $filterData=CollectRangeSchedule($fpschedule,2,$getDateRanges,$weekArray);
			  $RangeScData=$filterData;
			 // $RangeScData= CollectRangeSchedule($fpschedule,2, $typeVal[0][1], $typeVal[1][1], $typeVal[2][1]);
			   
			  
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
					//$msg=  $RangeScData[$i][0][0];
					$msg=$i."[".$weekArray[$i][0].">".$weekArray[$i][6];
				    DrawRect($msg,10,$fontColor,$x,$listY,$w,$h,"#000000"); 
			        DrawWeeks($RangeScData[$i],$color[$c],$i);
			   }
			 
	}
	function DrawWeeks($data,$BgColor,$n ){
		     global $listY;
		     $x=20;
			 $w=800;
			 $h=14;
		     for($i=1;$i<count($data);$i++){
				//   $listY+=16;
				   //$msg=$n.">".$data[$i][0][0];
				  // DrawRect($msg,10,$fontColor,$x,$listY,$w,$h,$BgColor);
				   if(count($data[$i])>1){
					   ListTasks($data[$i],$x);
				 }
			 }
	}
	function ListTasks($task,$x){
	         global $listY;
		     $x=20;
			 $w=500;
			 $h=14;
	         for($i=1;$i<count($task);$i++){
		         $listY+=16;
				 $BgColor="#cccccc";
				 if($task[$i][7]!="已完成")$BgColor="#ffcccc";
				 $msg="{".$task[$i][5]."}".$task[$i][21];
			
				 DrawRect($msg,10,$fontColor,$x,$listY,$w,$h,$BgColor);
				 $jila=$task[$i][12];
				 if($jila=="")$jila=$task[$i][22];
		         $Link="http://bzbfzjira.iggcn.com/browse/FP-".$jila;
				 DrawLinkRect($jila,10,"#ffffff",$x, $listY,50,12,"#ffaaaa",$Link,$border);
	           }
	}
?>

<?php //function
 
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