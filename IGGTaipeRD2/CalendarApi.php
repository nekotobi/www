<?php
       function getPassDaysDay($dateData,$Workday){//給予開始日 工作天,取得結束日 陣列
	          $y=$dateData[0];
			  $m=$dateData[1];
			  $d=$dateData[2];
			  $p=$Workday;
			  $mDay=getMonthDay($m,$TargetYear);
			  while($p>0){
				    $d+=1;
					$p-=1;
				  	if($d>$mDay){
				       $d=1;
					   $m+=1;
					   if($m>12){
						  $y+=1;
						  $m=1;
				          $mDay=getMonthDay($m,$TargetYear);
					     }
					}
			  }
			  return Array($y,$m,$d);
	  }
       function getPassDays($startDayArray,$nowDayArray){ //從開始結束 計算經過日期 2019_9_12  >>> 2019_9_14
			  $y=$startDayArray[0];
			  $m=$startDayArray[1];
			  $d=$startDayArray[2];
			  $ny= $nowDayArray[0];
			  $nm= $nowDayArray[1];
			  $nd= $nowDayArray[2];
			  $td=0;//總日
			  if($ny==$y){//同一年
			      if($nowDayArray[1]>$startDayArray[1]){//跨月
				     $td+=getMonthDay($m,$y)-$d;
					 $m+=1;
				     while($m<$nm){
			            $td+=getMonthDay($m,$y);
				        $m+=1;
				       }
				       $td+=$nd;
					   return $td;
				    }
		          if($nm==$startDayArray[1]){//同月
				    $td=$nd-$d;
					    return $td;
				  }
			  }
             return $td;
	  }
       function getVacationDays($YearRange,$MonthRange){
	          $VacationDays=array();
			  for($i=0;$i<count($YearRange);$i++){
			     $VacationDays=  getMonthVacationDays($YearRange[$i],$MonthRange[$i],$VacationDays);
			  }
	          return  $VacationDays;
	  }
       function getMonthVacationDays($y,$m,$VacationDays){
	              $monthEnd= getMonthDay($m,$y);
				  $weekStart=GetMonthFirstDay($y,$m);
				  for($i=1;$i<=$monthEnd;$i++){
				         if($weekStart==6 or  $weekStart==7){
						   array_push( $VacationDays,array($y,$m,$i));
						 }
					  $weekStart+=1;
					  if( $weekStart>7) $weekStart=1;
				  }
				  return $VacationDays;
	  }
       function ReturnWorkDaysV2($y,$m,$startday,$workd,$VacationDays){
				$AccumulatekDays=1;
				$CurrentDay=$startday;
				//echo $CurrentDay.">";
				while($AccumulatekDays<=$workd){ 
			       if(!in_array(array($y,$m,$CurrentDay),$VacationDays )){
					
					 $AccumulatekDays+=1;
				   }
			       $CurrentDay+=1;
				}
				return  $CurrentDay-$startday ;
	   } 
 	   function SetCalendarRange( $TargetYear,$TargetMonth){
	            global $YearRange,$MonthRange,$showMonthNum,$UpMonth;
				if( $TargetYear=="") $TargetYear=  date("Y");
				if( $TargetMonth!="") $m=$TargetMonth;
				if( $TargetMonth=="")
				  {
					if($UpMonth=="")$UpMonth=-1;
				    $TargetMonth=  date("m");
					$m=$TargetMonth+$UpMonth;
				  }
				if($showMonthNum==0)$showMonthNum=5;
				$y=$TargetYear;
				if($m==0){$m=12; $y-=1;}
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
	   function getMonthDay($m,$TargetYear){
		//   global $TargetYear;
		$m=(int)$m;
		 
		   $m_data=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	       $d_end=$m_data[$m];
		   if (($TargetYear+$y)%4==0 and $m==2) $d_end=29 ;
		   return $d_end;
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
		      $new_day+=getMonthDay($i,$year);
		  }
		  return  $new_day%7;
	   }
   
?>

 <?php //上傳
      function AddTypeData( ){
		       global $data_library,$tableName;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			   global $year,$month,$day;
			       $p=$tableName;
				   $tables=returnTables($data_library,$p);
	               $t= count( $tables);
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<$t;$i++){
	       	            global $$tables[$i];
					    $startDay=$year."_".$month."_".$day;
				        array_push($WHEREtable,$tables[$i]);
					    array_push($WHEREData,$$tables[$i]);
		              }
					$stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				    SendCommand($stmt,$data_library);
			 // echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
		      echo $stmt;
	 }
 ?>