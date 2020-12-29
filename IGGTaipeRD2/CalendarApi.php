<?php
       function returnPassDate($date, $passday){
			    $ps=$date.$passday." day";
	            $PassDate= date("Y-m-d",strtotime($ps ));   
	            return $PassDate ;
			
	   }
	   function getPassDays_array($startDate,$TargetDate){// $time1="2015-11-18";
			    $sd=$startDate[0]."-".$startDate[1]."-1";
				$ed=$TargetDate[0]."-".$TargetDate[1]."-".$TargetDate[2];
		        $d= (strtotime($ed) - strtotime($sd))/ (60*60*24);
			    return $d;
	   }

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
			    
			  if($y==$ny && $nm==$m)return  $nd-$d;
			  if($y>$ny){
				  return 0;
			  }
			  if($y==$ny && $nm<$m )return 0;
			  $td=getMonthDay($m,$y)-$d;//第一月已過天數
			  $m+=1;
			  $isJumpY=false;
			  while ($y<$ny){
				     if($m>=12){
						$y+=1;
					    $m=1;
						break;
					 }
					 if($m<12){
						$td+=getMonthDay($m,$y);
					    $m+=1;
					 }
			  }
		      echo "[".$m.">".$td;
		 	  if($startDayArray[0]!=$ny && $nm!=1)$nm+=1;
			  while ($m<$nm){
				     $td+=getMonthDay($m,$y);
					 $m+=1;
			  }
			  $td+=$nd;
			  return $td;
			
	  }
       function getVacationDays($YearRange,$MonthRange){
	          $VacationDays=array();
			  for($i=0;$i<count($YearRange);$i++){
			     $VacationDays=  getMonthVacationDays($YearRange[$i],$MonthRange[$i],$VacationDays);
			  }
	          return  $VacationDays;
	  }
	   function ReturnVacationDays($y,$m,$Vacationdays){
	              $monthEnd= getMonthDay($m,$y);
				  $weekStart=GetMonthFirstDay($y,$m);
				  $arr=array();
				  for($i=1;$i<=$monthEnd;$i++){
				 
					     $arr[$i]=0;
				         if($weekStart==6 or  $weekStart==7  or  $weekStart==0){
						 $arr[$i]=1;
						 }
					  $weekStart+=1;
					  if( $weekStart>7) $weekStart=1;
					  $f= Returnv($y,$m,$i ,$Vacationdays);
					 
					  
					  if($f!=0)  $arr[$i]=$f;
					  if($y==date("Y") and $m==date("n") and  $i==date("j"))$arr[$i]=2;
				  }
				  return $arr;
	  }
	   function Returnv($y,$m,$d ,$VacationDays){
		         for($i=1;$i<count($VacationDays);$i++){
			        if($y==$VacationDays[$i][0]){
					 if($m==$VacationDays[$i][1]){
					   if($d==$VacationDays[$i][2]){
						   $r=$VacationDays[$i][4];
						  if($r=="")$r=1;
						 
						   return $r;
					   }
					 }
					}
			     }
				 return 0;
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
				if($UpMonth=="")$UpMonth=-1;
			    if($showMonthNum==0)$showMonthNum=5;
				if( $TargetYear=="") $TargetYear=  date("Y");
				if( $TargetMonth!="") $m=$TargetMonth;
				if( $TargetMonth=="")
				  {
				    $TargetMonth=date("m");
					$m=$TargetMonth+$UpMonth;
				  }
				  
				$y=$TargetYear;
				if($m<=0){
					$y-=1;
					$m=12+$m; 
					
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
 	   function SetCalendarRange_bak( $TargetYear,$TargetMonth){
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
       function getDateRange($taskArr,$dateNum,$workingDayNum=6){  //判斷陣列2019_x_x 時間範圍
	            $arr= array(array(2019,1),array(2019,1));//開始 結束
				$yarr=array();
				for($i=0;$i<count($taskArr);$i++){ //收集年
					$date=explode("_",$taskArr[$i][$dateNum]);
					$y=$date[0];
				    array_push($yarr,$y);
				}
			 
		    	sort($yarr);
				$sy=$yarr[0];
				$ey=$yarr[count($yarr)-1];
				 $sm=12;
		 
				 $em=1;
				for($i=0;$i<count($taskArr);$i++){ //整理月
					$date=explode("_",$taskArr[$i][$dateNum]);
					$y=$date[0];
					$m=$date[1];
				
					if($sy==$ey){//都在同一年
					      if($m<$sm)$sm=$m;
					      if($m>$em)$em=$m;
					}
					if($sy!=$ey){//不在同一年
					   if($y==$sy){
					     if($m<$sm)$sm=$m;
					   }
					   if($y==$ey){
					     if($m>$em)$em=$m;
					   }
					}
					 
					//判斷工作日
					$workdays=$taskArr[$i][$workingDayNum];
					$d=$date[2]+$workdays;
					//echo $d.">";
					if($d>30){
					   $upm=intval( $d /30);
					   //echo "=".$upm."]";
					   $m+=$upm;
				       if($m>12){
						   $y+=1;
					   }
					   if($ey<$y){
					      $ey=$y;
				      	}
				         $em=$m;
					}
					 
				}
				$em+=1;
				if($em>12){
				$ey+=1;
				$em=1;
				}
	            if($sy==""){
		          $sy=date("Y");
				  $ey=date("Y");
	            }
				if($sm==""){
				   $sm=date("n");
				   $em=date("n");
				}
				return array($sy,$sm,$ey,$em);
	   }
	   function returnUpYM($y,$m,$d){
	      
				if($d<=0){
				   $m-=1;
				   if($m<=0){
					  $m=12;
					  $y-=1;
					 }
				  $d=getMonthDay($m,$y);
				  }
				return array($y,$m,$d);
	   }
	   function ReturnFinDay($startDay,$workingDays){
	        	$d= explode("_",$startDay); 
	            $y=$d[0];
			    $m=$d[1];
			    $d=$d[2];
				$ed=$d+$workingDays;
				$monthEnd=getMonthDay($m,$y);
				while ($ed>$monthEnd){
				       $ed-=$monthEnd;
					   $m+=1;
					   if($m>12){
						   $y+=1;
						   $m=1;
					   }
					   	$monthEnd=getMonthDay($m,$y);
				}
				return $y."_".$m."_".$ed;
	   } 
	   function ReturnDateRange($WeekDateEnd){
		      $arr=array();
			  $d=  explode("_",$WeekDateEnd);
			  $y=$d[0];
			  $m=$d[1];
			  $d=$d[2];
			  array_Push($arr,$WeekDateEnd);
			  for($i=0;$i<6;$i++){
				  $d=$d-1;
				  $a=returnUpYM($y,$m,$d);
			      $ar=$a[0]."_".$a[1]."_".$a[2];
				  	      array_Push($arr,$ar);
			  }
			  return $arr;
	 }
 	   function ReturnMonthRange($WeekDateEnd){
		      $d=explode("_",$WeekDateEnd);
			  $arr=array();
			  array_Push($arr, $d[0]."_".$d[1]);
			  $sd=$d[2]-6;
			  $ar= returnUpYM($d[0],$d[1],$sd);
			  if($ar[1]!=$d[1]){
			     array_Push($arr, $ar[0]."_".$ar[1] );
			  }
			  return $arr;
	 }
?>
<?php //行程表用
	 function  returnTaskInRang($tasks,$Range){
		 	   $arr=array();
	            for($i=0;$i<count($tasks);$i++){
				   $endDay=ReturnFinDay($tasks[$i][2],$tasks[$i][6]);
				    if (in_array($endDay,$Range))  array_push( $arr,$tasks[$i]);
			    }
			    return $arr;
			  
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
			   echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
		       echo $stmt;
	 }
 ?>