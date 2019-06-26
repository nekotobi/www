<?php
   //DrawLineTest();
   function DrawLineTest(){
            header("Content-type: image/png");
		    $myImage =  imagecreate( 200, 200 ); 
            $myGray = imagecolorallocate( $myImage, 204, 204, 204 ); 
            $myBlack = imagecolorallocate( $myImage, 0, 0, 0 ); 
            imageline( $myImage, 15, 35, 120, 60, $myBlack ); 
            imagepng( $myImage ); 
            imagedestroy( $myImage ); 
		    echo "xx";
	  }
   ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>美術總工程表</title>
</head>
<body bgcolor="#b5c4b1">
<?php //主控台
   $id=$_COOKIE['IGG_id'];
   include('PubApi.php');
   include('CalendarApi.php');  
   include('mysqlApi.php');
   include('scheduleApi.php');
   defineData_ST();   //定義基礎資料(scheduleApi)
   GetCalendarData(); //取得日曆資料(scheduleApi)
   DrawBaseCalendar_v2(); //列印基礎日期資料(scheduleAp
   DrawLists();
  // DrawLineNum( );
   CollectPlan();
   DrawGird();
  // DrawLine();
?>
<?php //定義基礎
	 function defineData_ST(){
		 //基礎數值
		 global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum,$LineHeight,$LineRec ;
		        $StartX=20;
	            $StartY=20;
	            $MonthWidth=200;
	            $OneDayWidth=4;
				$LineHeight=40;
	            $CurrentX= $StartX;
				$showMonthNum=8;
				$daysLoc=array();//(year,m,d,x軸位置)
                $monthLoc=array();//($y,m,x軸位置,Siz)
				$LineRec=array();//紀錄哪行有排列
		 //資料表
		 global $data_library,$tableName,$MainPlanData;
				$tableName="fpschedule";
			    $data_library="iggtaiperd2";
				$MainPlanDataT=getMysqlDataArray($tableName); 
				$MainPlanData=filterArray($MainPlanDataT,0,"data"); 
	     //分類
		  global $SelectType_2;
	            $sTypeTmp= getMysqlDataArray("scheduletype");	
	            $SelectType_2tmp= filterArray($sTypeTmp ,0,"data2");
				$SelectType_2=   returnArraybySort($SelectType_2tmp,2);  
          global $BaseURL,$BackURL, $Stype_1,$Stype_2;
                 $BaseURL="scheduleTotal.php";
      	  global $maxLine, $Line_Height;
		         $maxLine=30;
				 $Line_Height=10;
	 }
?>
<?php //收集資訊
      function   CollectPlan(){
		         global  $daysLoc;// (year,m,d,x軸位置)
		         global $data_library,$tableName,$MainPlanData;
	             global $TypeDayDatas;
				 global $VacationDays;
				 global $BaseURL,$BackURL,$SelectType_2, $Stype_1,$Stype_2;
				 global  $DaysData,$maxDay;
				 $typeName=$SelectType_2[$Stype_2];//"設定";
				 $typeData=filterArray($MainPlanData,5,$typeName); 
				 $DaysData= setDayDayZero($daysLoc);
	             for($i=0;$i<count($typeData);$i++){
			        $startDay=explode("_",$typeData[$i][2]); 
				   	$workingDays=$typeData[$i][6];
				    $realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2],$workingDays,$VacationDays);
					$st=getdayS( $DaysData,  $startDay);
				     for($j=0;$j<$realDays;$j++){
					    $DaysData[($st+$j)][3]+=1;  
				 	}
			     }
				 $maxDay=0;
                 for($i=0;$i<count($DaysData);$i++){
				 if($DaysData[$i][3]>$maxDay)$maxDay=$DaysData[$i][3];
				 }
				// echo $max;
	  }
	  
	  

      function    getdayS($DaysL, $startDay){
		        for($i=0;$i<count($DaysL);$i++){
					//  echo ">".$DaysL[$i][1].".".$startDay[1];
					if($DaysL[$i][0]==$startDay[0] and $DaysL[$i][1]==$startDay[1]  and $DaysL[$i][2]==$startDay[2]){
					return $i;
					}
				}
				return 0;
	  }
      function    setDayDayZero($daysLoc){
		          $dd=$daysLoc;
		          for($i=0;$i<count($daysLoc);$i++){
					 $dd[$i][3]=0;
				  }
				  return $dd;
		         /*
		         $dd=array();
	              for($i=0;$i<count($daysLoc);$i++){
					  $p=array($daysLoc[0],$daysLoc[1],$daysLoc[2],$daysLoc[3],0);
					  array_push($dd,$p);
					 
	             }
				 return $dd;
				 */
	  }
?>
<?php //繪製
         function DrawLists(){
		  DrawRect("產量熱度表","14","#ffffff","20","10","1100","20","#000000");
		 global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum,$LineHeight,$LineRec ;
	     global $SelectType_2;
		 global $colorCodes;
		 global $BaseURL,$BackURL, $Stype_1,$Stype_2;
		 $x=$StartX;
		 $y=$StartY+10;
		 for ($i=0;$i<count($SelectType_2);$i++){
			       $BackURL2=$BaseURL."?Stype_2=".$i;
				   $msg=" ".$SelectType_2[$i];
				   $c=$i%(count($colorCodes)-1);
				   $color= $colorCodes[9][$c];
				   if($Stype_2==$i and  $Stype_2!="")$color= "#cc2212";
			       DrawLinkRect($msg,"9","#000000",$x,$y,"40","14",$color,$BackURL2,1);
				   $x+=50;
			  }
	  }
	  function DrawGird(){
		       global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum,$LineHeight,$LineRec ;
               global $maxLine, $Line_Height;
			   $x=$StartX;
			   $y=$StartY+120+$Line_Height*$maxLine;
			   $w=1100;
			   for($i=0;$i<$maxLine;$i++){
				   $y-= $Line_Height;
			       DrawRect("",2,"#000000",$x,$y,$w,1,"#888888");
			   }
			   $y=$StartY+120+$Line_Height*$maxLine;
			   DrawLine($y);
			   $y=$StartY+120+$Line_Height*($maxLine+5);
			   for($i=0;$i<=$maxLine;$i+=5){
				   $y-= $Line_Height*5;
			       DrawRect("",2,"#000000",$x,$y,$w,1,"#000000");
				   $msg=$i;//$maxLine+( $i- $maxLine);
				   DrawRect($msg,12,"#ffffff",$x-20,$y-10,20,20,"#888888");
			   }
	  }
      function  DrawLine($sy){
		        global  $DaysData,$maxDay;
			    global $maxLine, $Line_Height;
	            global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum,$LineHeight,$LineRec ;
                $x=$StartX;
				$w=$OneDayWidth;
			
				for($i=0;$i<count($DaysData);$i++){
					    $x+=$OneDayWidth;
					   	$show="false";
					    if( $DaysData[$i][3]!=0){
						   $Les= (($DaysData[$i][3] )*$Line_Height);
					       $y= $sy- $Les;
					       $hi=(($DaysData[$i][3] )*$Line_Height);
					       DrawRect("",2,"#ffffff",$x,$y ,$w,$hi,"#ff2222");
				        
                           if($maxDay==$DaysData[$i][3] and  $show=="false"){
							   $show="true";
							   $msg=$DaysData[$i][1]."月".$DaysData[$i][2]."日";  
						       DrawRect($msg."最高".$maxDay."工單同時進行",10,"#ffffff",$x,$y-20,"200","15","#660000");
					
						   }
						}
				}
				 
	  }
	  function DrawLineNum( ){
		       global $BackURL,$ELine,$LineRec;
		       global $LineHeight; 
			   global $StartY,$startX;
		 	 		  $w= 20;//$OneDayWidth*count($daysLoc);
			          $y= $StartY+90;
				      $x=$startX ;
				      $h=10; 
					  
	           for($i=1;$i<$LineHeight;$i++){
				     $Link=$BackURL."&ELine=".$i;
				     DrawLinkRect_Layer($i,"10","#ffffff",array($x,$y,$w,$h),"#aaaaaa",$Link," ",-1);
                     $y+=20;
			   }
	 }
	
?>