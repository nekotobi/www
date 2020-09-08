<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引v2</title>
</head>
 <body bgcolor="#b5c4b1"> 
<?php //主控台
    include('PubApi.php');
	include('mysqlApi.php');
    include('scheduleApi.php');
	include('CalendarApi.php'); 
	include('VTApi.php'); 
    test1();

?>
<?php
 
    function test1(){
         $v= 	getLaterEventData();
		 $h= GetEventRes($v,"h");		 
		// for($i=0;$i<count($h);$i++){
		     //  echo "</br>".$h[$i][0].">".$h[$i][1];
		// }
		$LocArray=array(20,200,200,19);
		 DrawTittle($LocArray,$h);
	}
	function DrawTittle($LocArray,$TitleDatas){
		     $x=$LocArray[0];
		     $y=$LocArray[1];
			 $w=$LocArray[2];
			 $h=$LocArray[3];
		     $BgColor="#555555";
			 $fontColor="#ffffff";
		     for($i=0;$i<count($TitleDatas);$i++){
				 $str=$TitleDatas[$i][0];
			     DrawRect($str,10,$fontColor,$x,$y,$w,$h,$BgColor);
				 $y+=$h+1;
			 }
	}
	  function DrawCallendarRanges($LocationArray,$ListDatas){//LocationArray=array(x,y,datewid)
		       $BgColor="#555555";
			   $fontColor="#ffffff";
			   
			   
	  }
?>