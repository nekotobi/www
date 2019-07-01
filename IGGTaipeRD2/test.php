<?php
   include('PubApi.php');
   include('CalendarApi.php');  
   include('mysqlApi.php');
   include('scheduleApi.php');
     testWarring();
   
  // $realDays=ReturnWorkDaysV2(2019, 6,5,"6",$VacationDays);
  // echo getPassDays($startDayArray,$nowDayArray);
?>


<?php
     function    testWarring(){
		          global $VacationDays; //年 月 日
	              $startDay=array(2019,6,19);
				  $nowDayArray=array(2019,7,1);
				  $passDays= getPassDays($startDay,$nowDayArray);
				  echo   $passDays;
				  
				  
				  
				  /*
				  
				  $realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2],$plansArray[6],$VacationDays);
				  if($realDays<1)$realDays=1;
				 
	              if($passDays>=$realDays && $plansArray[7]!="已完成") return "true";
				  echo $startDay[1]."-".$startDay[2]."=".$passDays.">".$realDays."]";
				  
	              return "false";
				  */
	 }
?>