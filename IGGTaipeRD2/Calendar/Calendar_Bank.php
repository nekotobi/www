<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作行事曆</title>
</head>
<body bgcolor="#B6AEAB">
<?php
      //UI
      $colorSet_Green=array("#103025","#2E4E43","#718878","#C4D0CE","#DED6D3","#B6AEAB"); //綠色系
      $CalendarWidth=1200;
      $NowHeight=20;
	  $StartX=20;
	  $YearStartDay= GetYearFirstDay();
	  //Range
	  SetCalendarRange();
	  
      DrawCalendar();
     
	   function DrawCalendar(){
		      global $TargetYear,$CalendarWidth,$NowHeight,$StartX, $colorSet_Green;
			  DrawRect($TargetYear,"14","#ffffff",$StartX, $NowHeight,  $CalendarWidth,"20", $colorSet_Green[0]);
			  $NowHeight+=25;
 	           for($i=1;$i<=12;$i++){
			      DrawMonth($i);
	 	       }
	   }
	   function DrawMonth($m){
	   		    global $TargetYear,$CalendarWidth,$NowHeight,$StartX,$YearStartDay, $colorSet_Green;
				
				$weekName=array("","一","二","三","四","五","六","七");
				DrawRect("","12","#999999",$StartX ,$NowHeight,  $CalendarWidth,"54",$colorSet_Green[1]);
			    DrawRect($m."月","14","#E3D4c3",$StartX,$NowHeight,  $CalendarWidth,"20",$colorSet_Green[1]);
			    $NowHeight+=22;
				$MonthEnd= getMonthDay($m);
				$Bwidth=($CalendarWidth-80)/$MonthEnd ;
			  	for($i=1;$i<=$MonthEnd;$i++){
					  DrawRect($i,"10","#E3D4c3",($StartX+80+($i-1)*$Bwidth), $NowHeight,  ($Bwidth-2),"15",$colorSet_Green[2]);
					  DrawRect($weekName[$YearStartDay],"12","#999999",($StartX+80+($i-1)*$Bwidth), $NowHeight+14,  ($Bwidth-2),"15",getColor($m,$i, $YearStartDay));
					  $YearStartDay+=1;
					  if($YearStartDay>7)  $YearStartDay=1;
			  	}
				$NowHeight+=35;
				DrawUsers($m);
	   }  
	   function DrawUsers($m){
                global  $colorSet_Green,$NowHeight, $StartX,$TargetYear;
				$All_num= getAll_num("calendardata");
				
			 
				
		        $user=array("kou","neko");
		     	for($i=0;$i<count($user);$i++){
				   DrawRect($user[$i],"12","#ffffff",($StartX), $NowHeight,  "80","19",$colorSet_Green[0]);
				   $NowHeight+=20;
				}
	   }
	   function SortCalendarData(){
	   
	   
	   
	   
	   
	   }
	   function getColor($M,$D,$w){
		     global  $colorSet_Green;
		     $BgColor= $colorSet_Green[4];
	         if($w==7 or $w==6)$BgColor="#D19379";// $colorSet_Green[3];
				 return  $BgColor;
	   }
	   function getMonthDay($m){
		   global $TargetYear;
		   $m_data=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	       $d_end=$m_data[$m];
		   if (($TargetYear+$y)%4==0 and $m==2) $d_end=29 ;
		   return $d_end;
	   }
	   function DrawRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor){
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
       function GetYearFirstDay(){
		   global $TargetYear;
	       $start=2010;//2010年開始
	       $base_newday=5;//2010年的元旦
		   if( $TargetYear=="") $TargetYear=  date("Y");
	       if ( $TargetYear<$start) $TargetYear=$start; 
	       $days=0; 
	       if ($TargetYear>=$start){
	           for ($y=$start;$y<$TargetYear;$y++){
				     $days+=365;
					 if ($y%4==0)$days+=1;
			        } 
	           }
	      $new_day=$days%7+$base_newday;
		  if ($new_day>6)$new_day-=7;
		  return  $new_day;
	   }
	   function SetCalendarRange(){
	            global $TargetYear,$TargetMonth,$YearRange,$MonthRange,$showMonthNum;
				if($showMonthNum==0)$showMonthNum=3;
	            $MonthRange[0]=$TargetMonth-1;
				$MonthRange[1]=$TargetMonth+$showMonthNum;
				$YearRange[0]=$TargetYear;
				$YearRange[1]=$TargetYear;
				if($MonthRange[0]==0){
					$MonthRange[0]=12;
				    $YearRange[0]=$TargetYear-1;
				}
			    if($MonthRange[1]>12){
					$MonthRange[1]=$MonthRange[1]-12;
				    $YearRange[1]=$TargetYear+1;
				} 
	   }
	   function getAll_num($SElectTable){
		  $data_library="IGGTaipeRD2";
	      $db = mysql_connect("localhost","root","1406");
	      mysql_select_db( $data_library,$db);
          mysql_query("SET NAMES 'utf8'");
	      return  mysql_query("SELECT * FROM ".$SElectTable,$db);	  
	   }
?>
</body>