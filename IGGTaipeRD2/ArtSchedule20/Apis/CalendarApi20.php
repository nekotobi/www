<?php
     // function  CAPI_DrawBaseCalendar($StartY,$StartM,$MRange,$LocX,$LocY,$wid,$h){
	  function  CAPI_DrawBaseCalendar($startDate,$DateRange,$LocX,$LocY,$wid,$h){
			      if( $startDate=="")$startDate=date("Y-n-1");  
			      if($DateRange=="")$DateRange=2;  
		          $BgColor="#222222";
			      $fontColor="#ffffff";
			      $fontSize=10;
				  $str=  explode("-",$startDate);
				  $y=$str[0];//$StartY;
				  $m=$str[1];//$StartM;
				  $LocX-=$wid;
	              for($i=0;$i<$DateRange;$i++){
                     $days = cal_days_in_month(CAL_GREGORIAN, $m,$y); // 30
				     DrawRect($m,$fontSize,$fontColor,array($LocX,$LocY,$wid*$days-2,18),$BgColor);
				     CAPI_DrawCalDays($LocX,$LocY,$wid,$days, $h,$y,$m);
			         $m+=1;
				     if($m>12){$m=1;$y+=1;}
				     $LocX+=$wid* $days;
				  }
	   }
      function  CAPI_DrawCalDays($LocX,$LocY,$wid,$days, $h,$y,$m){
		     	  $BgColor="#aaaaaa";
			      $fontColor="#ffffff";
			      $fontSize=6;
				  $date=date("Y_n_j");
			      for($i=1;$i<=$days;$i++){
					  $cd=$y."_".((int)$m)."_".$i;
					  $n=   date("w",strtotime($y."-".$m."-".$i) );
					  $id="startDay=".$cd;
					  $BgColor="#aaaaaa";
					  if($n==0 or $n==6)$BgColor="#bbaaaa";
					  if($date== $cd){
						  $BgColor="#aa7777";
					  }
					  DrawRect($i,$fontSize,"#eeeeee",array($LocX+($i-1)*$wid ,$LocY+20,$wid-1,10),"#777777");
			          JAPI_DrawJavaDragArea("",$LocX+($i-1)*$wid ,$LocY+30,$wid-1,$h,$BgColor,$fontColor,$id,$fontSize );
				  }
		}
	  function  CAPI_DrawMuiltCalendarLines($StartY,$StartM,$MRange,$LocX,$LocY,$wid,$h,$LineNum,$type=""){
	    	    $BgColor="#222222";
			    $fontColor="#ffffff";
			    $fontSize=10;
				$y=$StartY;
				$m=$StartM;
				$LocX-=$wid;
	            for($i=0;$i<$MRange;$i++){
                   $days = cal_days_in_month(CAL_GREGORIAN, $m,$y); // 30
				   DrawRect($m,$fontSize,$fontColor,array($LocX,$LocY-20,$wid*$days-2,18),$BgColor);
				   CAPI_DrawMuiltDays($LocX,$LocY ,$wid,$days, $h,$y,$m,$LineNum,$type);
			       $m+=1;
				   if($m>12){$m=1;$y+=1;}
				   $LocX+=$wid* $days;
				}
				   
	   }
      function  CAPI_DrawMuiltDays($LocX,$LocY,$wid,$days, $h,$y,$m,$LineNum,$type=""){
		     	  $BgColor="#aaaaaa";
			      $fontColor="#ffffff";
			      $fontSize=10;
				  $date=date("Y_n_j");
			      for($i=1;$i<=$days;$i++){
					  $cd=$y."_".$m."_".$i;
					  $n=   date("w",strtotime($y."-".$m."-".$i) );
					  $BgColor="#aaaaaa";
					  if($n==0 or $n==6)$BgColor="#bbaaaa";
					  if($date== $cd)$BgColor="#aa7777";
					  for($j=0;$j<=$LineNum;$j++){
					     $id="startDay=".$cd."=".$j."=".$type;
			             JAPI_DrawJavaDragArea("",$LocX+($i-1)*$wid ,$LocY+$h*$j,$wid-1,$h-1,$BgColor,$fontColor,$id,$fontSize );
					  }
					
				  }
		}
	  function  CAPI_returnLocX($date,$startDate ){
		             $checkDay=strtr($date,"_","-");
			         $n= (strtotime( $checkDay)-strtotime($startDate))/86400;
			         return $n; 
	   }
	  function  CAPI_getDateRange($taskArr,$dateNum,$workingDayNum){  //判斷陣列2019_x_x 時間範圍 date陣列位置 工作陣列位置
	            $arr= array(array(2019,1),array(2019,1));//開始 結束 	return array($sy,$sm,$ey,$em);
				$yarr=array();
	            for($i=0;$i<count($taskArr);$i++){ //收集年
					$date=explode("_",$taskArr[$i][$dateNum]);
					$y=$date[0];
				    array_push($yarr,$y);
				}
			    sort($yarr); //排序年
				$sy=$yarr[0]; //開始年
				$ey=$yarr[count($yarr)-1];//結束年
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
      function  CAPI_setDateRange($URL,$LocX,$LocY,$startDate,$DateRange){
	            $BgColor="#442222";
			    $SubmitName="submit";
				if($startDate=="--" or $startDate=="" )$startDate=date("Y-m-1");
				//前一月
			    $Rect=array($LocX,$LocY,19,12);
				$targetDate=date("Y-n-1", strtotime("-1 month", strtotime($startDate)));
			    $ArrayVal=array(array("startDate",$targetDate));
			    sendVal($URL,$ArrayVal,$SubmitName,"<",$Rect,10, $BgColor , "#ffffff","true");
				//預設
				$LocX+=20;
			    $Rect=array($LocX,$LocY,59,12);
				$ArrayVal=array(array("startDate",date("Y-n-1")));
			    sendVal($URL,$ArrayVal,$SubmitName,$startDate,$Rect,8, $BgColor , "#ffffff","true");
				//後一月
				 $LocX+=60;
				$Rect=array($LocX,$LocY,19,12);
				$targetDate=date("Y-n-1", strtotime("+1 month", strtotime($startDate)));
			    $ArrayVal=array(array("startDate",$targetDate));
			    sendVal($URL,$ArrayVal,$SubmitName,"'>'",$Rect,10, $BgColor , "#ffffff","true");
				//加一月
				//減一月
				//預設   
	  }
	   function CAPI_setDateRangeButtom($URL,$LocX,$LocY,$startDate,$DateRange,$WebSendVal,$CookieName){
	            $BgColor="#442222";
			    $SubmitName="submit";
				if($startDate=="--" or $startDate=="" )$startDate=date("Y-m-1");
				//前一月
			    $Rect=array($LocX,$LocY,19,12);
				$targetDate=date("Y-n-1", strtotime("-1 month", strtotime($startDate)));
				$ArrayVal=$WebSendVal;
			    array_push( $ArrayVal, array("startDate_".$CookieName,$targetDate));
			    sendVal($URL,$ArrayVal,$SubmitName,"<",$Rect,10, $BgColor , "#ffffff","true");
				//預設
				$LocX+=20;
			    $Rect=array($LocX,$LocY,59,12);
			    $ArrayVal=$WebSendVal;
				array_push( $ArrayVal, array("startDate_".$CookieName,date("Y-n-1")));
			    sendVal($URL,$ArrayVal,$SubmitName,$startDate,$Rect,8, $BgColor , "#ffffff","true");
				//後一月
				 $LocX+=60;
				$Rect=array($LocX,$LocY,19,12);
				$targetDate=date("Y-n-1", strtotime("+1 month", strtotime($startDate)));
				  $ArrayVal=$WebSendVal;
				 array_push( $ArrayVal, array("startDate_".$CookieName,$targetDate));
			    sendVal($URL,$ArrayVal,$SubmitName,"'>'",$Rect,10, $BgColor , "#ffffff","true");
				//加一月
				//減一月
				//預設   
	  }
	  function  CAPI_fillterDateRange($taskArr,$startDate,$DateRange,$dateNum,$WorkDaysNum){ 
				$d="+ ".$DateRange." month" ;
				$end=date("Y-n-1", strtotime(	$d, strtotime($startDate)));
				$NewTasks=array();
				for($i=0;$i<count($taskArr);$i++){
					//開始時間
					$checkTime= CAPI_ChangeTimeFormat($taskArr[$i][$dateNum]);
					$inTime=CAPI_checkIsBetweenTime($startDate,$end,$checkTime);
				    if($inTime=="in"){
					   array_push( $NewTasks,$taskArr[$i]);
					}
					//結束時間
					if($inTime=="out"){
					  $d1=CAPI_GetPassDays($checkTime,$startDate);  //離開始1號過幾天
					  $d= $taskArr[$i][$WorkDaysNum]+  $d1;
                      if($d>0){
					     $taskfix=$taskArr[$i];
						 $taskfix[$WorkDaysNum]=$d;
					     $taskfix[2]=$taskfix[2]."[".$checkTime."][".$taskArr[$i][$WorkDaysNum]."]";
						 $taskfix[$dateNum]= CAPI_ChangeTimeFormat2Base( $startDate);
					     array_push( $NewTasks,$taskfix);
					  }						  
					}
					
				}
	           return $NewTasks;
	  }
	  //取得s>e過幾天
	   function CAPI_GetPassDays($s,$e){
		   echo $s.">";
		        $s= str_replace("_","-",$s);
		 
			    $e= str_replace("_","-",$e);
		        $st= strtotime($s);
				$et=strtotime($e);
			    $ds=  $st-$et ;
				return $ds/3600/24;
	   }
	 

	  function CAPI_GetAfterDate($date,$days){
		       $d="+".$days." day";
	           return   date("Y-n-1", strtotime($d, strtotime($date))) ;
	  } 
    //取得幾天後
	  function CAPI_GetAfterDays($date,$days){
		       $d="+".$days." day";
	           return   date("Y-n-j", strtotime($d, strtotime($date)));
	  }
	  //取得幾月後
	  function CAPI_GetAfterMonths($date,$Months){
		       $d="+".$Months." month";
	           return   date("Y-n-j", strtotime($d, strtotime($date))) ;
	  }
	  function CAPI_ChangeTimeFormat($baseStr){ //轉換日期格式 "_" > "-"
		       $str=str_replace("_","-",$baseStr);
			   return $str;
	  }
      function CAPI_ChangeTimeFormat2Base($baseStr){ //轉換日期格式 "_" > "-"
		       $str=str_replace("-","_",$baseStr);
			   return $str;
	  }
	  function CAPI_checkIsBetweenTime($start,$end,$checkTime){ //$start
               $curTime = strtotime($checkTime);//当前时分
               $assignTime1 = strtotime($start);//获得指定分钟时间戳，00:00
               $assignTime2 = strtotime($end);//获得指定分钟时间戳，01:00
               $result = "out";
			   if( $curTime >$assignTime2 )$result = "out2";
               if($curTime>$assignTime1&&$curTime<$assignTime2){
                  $result = "in";
                  }
		 
			   return $result;
      }
	  function  CAPI_boolInDataRange($TargetStartTime,$TargetDays, $startDate,$DateRange){
		      //  echo $DateRange;
				$ta=str_replace("_","-",$TargetStartTime);
	            $end= CAPI_GetAfterMonths(  $startDate,$DateRange);
			    $Bool=false;
				//判斷開始
				$result=CAPI_checkIsBetweenTime($startDate ,$end,$ta);
			    if($result=="in")return true;
				//判斷結束
				$TE=CAPI_GetAfterDays($ta,$TargetDays);
				$result=CAPI_checkIsBetweenTime($startDate ,$end,$TE);
				if($result=="in")return true;
				return false;
	  }
?>