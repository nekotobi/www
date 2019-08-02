<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>測試小月曆</title>
</head>
<body bgcolor="#b5c4b1">
<script>
function express(){
var value="abc";
location.href="point.php?value=" +value; //將一個value=abc的變數將值丟到point.php的網址上}
</script>
<?php //主控台
     include('CalendarApi.php');  
	 include('PubApi.php');
     DrawSmallCalendar($dateArray);

?>
<?php
     function DrawSmallCalendar($dateArray){
		     if($dateArray==""){
			 $dateArray=array( date("Y"),date("m"),date("d") );
			 } 
		     $startweekly=GetMonthFirstDay($dateArray[0],$dateArray[1]);
			 $MonthDay=getMonthDay($dateArray[1],$dateArray[0]);
			 $x=10;
			 $y=10;
			 $w=200;
			 $h=20;
			 $fontSize=10;
			 $fontColor="#ffffff";
			 $BgColor="#000000";
			 $msg=$dateArray[0]."-".$dateArray[1]."-".$dateArray[2];
		     DrawRect($msg,$fontSize,$fontColor,$x,$y,$h*7,$h,$BgColor);
			 $y+=20;
			 $x2=$x+1;
			 for($i=0;$i<7;$i++){
				 $BgColor="#444444";
				 $fontColor="#ffffff";
			     if($i==0 or $i==6) $fontColor="#ffaaaa";
			     DrawRect($i,$fontSize,$fontColor,$x2,$y,$h-1,$h-2,$BgColor);
				 $x2+= $h;
			 }
			 $y+=20;
			 $x2=$x+1+$startweekly*20;
			 $w=$startweekly;
			 for($i=1;$i<$MonthDay;$i++){
				 $BgColor="#dddddd";
				 $fontColor="#000000";
				 $x2=$w*20+$x;
			     if($w==0 or $w==6)$BgColor="#ffaaaa";
				 if($i==(int)$dateArray[2])$BgColor="#aaffaa";
			     DrawRect($i,$fontSize,$fontColor,$x2,$y,$h-2,$h-2,$BgColor);
			     $w+=1;
				 if($w>6){
					$w=0;
				    $y+=20;
				   }
			 }
	 }

?>