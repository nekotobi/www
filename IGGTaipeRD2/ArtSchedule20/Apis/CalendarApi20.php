<?php
      function  CAPI_DrawBaseCalendar($StartY,$StartM,$MRange,$LocX,$LocY,$wid,$h){
		        $BgColor="#222222";
			    $fontColor="#ffffff";
			    $fontSize=10;
				$y=$StartY;
				$m=$StartM;
				$LocX-=$wid;
	            for($i=0;$i<$MRange;$i++){
                   $days = cal_days_in_month(CAL_GREGORIAN, $m,$y); // 30
				   echo 
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
					  $cd=$y."_".$m."_".$i;
					  $n=   date("w",strtotime($y."-".$m."-".$i) );
					  $id="startDay=".$cd;
					  $BgColor="#aaaaaa";
					  if($n==0 or $n==6)$BgColor="#bbaaaa";
					  if($date== $cd)$BgColor="#aa7777";
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

?>