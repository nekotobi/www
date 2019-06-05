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
      $CalendarWidth=1400;
      $NowHeight=122;
	  $Bwidth=0;
	  $StartX=30;
      $VacationDays=array();
	  SetCalendarRange();
      DrawCalendar();
     
	   function DrawCalendar(){
		      global $YearRange,$MonthRange,$showMonthNum;
			  global $CalendarWidth,$NowHeight,$StartX, $colorSet_Green,$Bwidth;
			  $upY=0;
			  for($i=0;$i<count($MonthRange);$i++){
			      if($upY!=$YearRange[$i]){
				     DrawRect($YearRange[$i],"14","#ffffff",$StartX, $NowHeight, $CalendarWidth,"20", $colorSet_Green[0]);
				  	 $NowHeight+=25;
					 $upY=$YearRange[$i];
				   }
				    DrawMonth($YearRange[$i],$MonthRange[$i]);
					DrawUsers($YearRange[$i],$MonthRange[$i]);
	 	       }
	   }
	   function DrawMonth($y,$m){
		        global  $CalendarWidth,$NowHeight,$StartX,$YearStartDay, $colorSet_Green,$Bwidth;
				global  $VacationDays;
	            $weekName=array("","一","二","三","四","五","六","日");
				DrawRect("","12","#999999",$StartX ,$NowHeight,  $CalendarWidth,"54",$colorSet_Green[1]);
			    DrawRect($m."月","14","#E3D4c3",$StartX,$NowHeight, $CalendarWidth,"20",$colorSet_Green[1]);
				$NowHeight+=22;
				$MonthEnd= getMonthDay($m);
				$WeekStart=GetMonthFirstDay($y,$m);
				$Bwidth=($CalendarWidth-80)/$MonthEnd ;
				for($i=1;$i<=$MonthEnd;$i++){
					  $x=$StartX+80+($i-1)*$Bwidth;
					  DrawRect($i,"10","#E3D4c3", $x, $NowHeight,  ($Bwidth-2),"15",$colorSet_Green[2]);
					  $color=getColor($m,$i, $WeekStart);
					  if($color!=$colorSet_Green[4]){
						  array_push($VacationDays,array($m,$i));
					  }
					  DrawRect($weekName[$WeekStart],"12","#000000", $x, $NowHeight+14,  ($Bwidth-2),"15",  $color);
                      $WeekStart+=1;
					  if($WeekStart>7)  $WeekStart=1;
			     }
				$NowHeight+=35;
	   }
	 
	
	   function DrawUsers($y,$m){
                global  $YearRange,$MonthRange   ;
			    global $CalendarWidth,$NowHeight,$StartX, $colorSet_Green,$Bwidth;
		        $user=getMonthUser($y,$m);//array("kou","neko");
		     	for($i=0;$i<count($user);$i++){
				    DrawRect($user[$i],"12","#ffffff",($StartX), $NowHeight,  "80","19",$colorSet_Green[0]);
				    DrawUsersWorks($y,$m,$user[$i]);
				    $NowHeight+=20;
				}
	   }
	
	   function DrawUsersWorks($y,$m,$user){
		        global  $YearRange,$MonthRange , $CrossMonth;
				global $CalendarWidth,$NowHeight,$StartX, $colorSet_Green ,$Bwidth; 
		        $Data_Struct= getUserWorks($y,$m,$user);
				$MonthEnd= getMonthDay($m);
				unset( $CrossMonth);
		    	for ($i=0;$i<count( $Data_Struct);$i++){
					 $d= $Data_Struct[$i][0];
					 $dw= $Data_Struct[$i][1];
					 $info= $Data_Struct[$i][2];
                     $x= $StartX+($d-1)*$Bwidth+80;
					 
					 //檢查跨月
					 $warkDays= ReturnWorkDays($m,$d, $dw);
					 $finDay=$d+$warkDays;
					 $fw=$warkDays;
					 
					 if( ($finDay)>$MonthEnd){
						  $w=$MonthEnd-$d;
						  $CrossDays=$finDay-$MonthEnd;
						  $nextM=$m+1;
						  if($nextM>12)$nextM=1;
						  $CrossFinDays=ReturnWorkDays($nextM,1, $w);
					      array_push(array($CrossFinDays, $info  ),   $CrossMonth)  
					 }
					 $wid=  $fw*$Bwidth;
			         DrawRect($info,"12","#ffffff",$x, $NowHeight,  $wid,"19","#a27e7e");//"#7b8b6f"
			     }
	   }
	 
	   function ReturnWorkDays($m,$sd,$workd){
		  		global  $VacationDays;
		        $d=0;
				$i=$sd;
				while($i<($sd+$workd)){
					 if(in_array(array($m,$i),$VacationDays ))  $d+=1;
				        $i+=1;
				     $d+=1;
				}
				return $d; 
	   }
	   function getUserWorks($y,$m,$user){
		       $all_num= getAll_num("calendardata");
			   $t=mysql_num_rows($all_num); 
			   $Data_Struct=array();
			   for ($i=0;$i<$t;$i++){
				    $dy=mysql_result($all_num,$i,'Year');
				    $dm=mysql_result($all_num,$i,'Month');
					$dd=mysql_result($all_num,$i,'Day');
					$duser=mysql_result($all_num,$i,'User');
					$dw=mysql_result($all_num,$i,'WorkDay');
					$dinfo=mysql_result($all_num,$i,'info');
					$dt=mysql_result($all_num,$i,'Type');
				    if($dy==$y   And $dm==$m  And $duser==$user){
						$datas=array($dd,$dw,$dinfo,$dt );
					     array_push($Data_Struct,$datas);
					}
				  }
			    return  $Data_Struct;
	   }
	   
       function getMonthUser($y,$m){
		       $all_num= getAll_num("calendardata");
			   $t=mysql_num_rows($all_num); 
			   $users=array();
               for ($i=0;$i<$t;$i++){
					  if(mysql_result($all_num,$i,'Year')==$y  And  mysql_result($all_num,$i,'Month')==$m){
						  $u=mysql_result($all_num,$i,'User');
					      if(!in_array($u,$users ))  array_push($users,$u);
					  }
				 }
               return  $users;
	   }
	 
 
	   function getColor($M,$D,$w){
		     global  $colorSet_Green;
		     $BgColor= $colorSet_Green[4];
	         if($w==7 or $w==6)$BgColor="#f0c9ca"; 
			 if($M==date("m") and $D==date("d"))$BgColor="#c39393";
			 return  $BgColor;
	   }
 
	   function DrawRect($msg,$fontSize,$fontColor,$x,$y,$w,$h,$BgColor){
	          echo "<div  style=' color:".$fontColor."; " ;
			  echo "text-align:center ; font-weight:bolder ;font-family:Microsoft JhengHei; font-size:".$fontSize."px;";
			  echo "position:absolute;  top:".$y."px; left:".$x."px;  width:".$w."px;height:".$h."px; background-color:".$BgColor."; '>";
			  echo $msg;
	          echo "</div>";
	   }
       function GetMonthFirstDay($year,$month){
	       $start=2010;//2010年開始
	       $base_newday=5;//2010年的元旦
	       if ( $year<$start) $year=$start; 
	       $days=0; 
	       if ($year>=$start){
	           for ($y=$start;$y<$year;$y++){
				     $days+=365;
					 if ($y%4==0)$days+=1;
			        } 
	           }
	      $new_day=$days%7+$base_newday;
		  if ($new_day>6)$new_day-=7; 
		  for($i=1;$i<$month;$i++){
		      $new_day+=getMonthDay($i);
		  }
		  return  $new_day%7;
	   }
	    function getMonthDay($m){
		   global $TargetYear;
		   $m_data=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	       $d_end=$m_data[$m];
		   if (($TargetYear+$y)%4==0 and $m==2) $d_end=29 ;
		   return $d_end;
	   }
	   function SetCalendarRange(){
	            global $TargetYear,$TargetMonth,$YearRange,$MonthRange,$showMonthNum;
				if( $TargetYear=="") $TargetYear=  date("Y");
				if( $TargetMonth=="") $TargetMonth=  date("m");
				if($showMonthNum==0)$showMonthNum=5;
				$m=$TargetMonth;
				$y=$TargetYear;
				$m-=1;
				if($m==0){
					$m=12;
					$y-=1;
				}
				for($i=0;$i<=$showMonthNum;$i++){
				 	 $MonthRange[$i]=$m;
					 $YearRange[$i]=$y;
					 $m+=1;
					 if($m>12){
					 $m=1;
					 $y+=1;
					 }
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