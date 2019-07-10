
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作分類排程區</title>
</head>
 
<body bgcolor="#b5c4b1">
<?php  //主控台
   $id=$_COOKIE['IGG_id'];
   include('PubApi.php');
   include('CalendarApi.php');  
   include('mysqlApi.php');
   include('scheduleApi.php');
     defineData_schedule();   //定義基礎資料(scheduleApi)
     GetCalendarData(); //取得日曆資料(scheduleApi)
     DrawBaseCalendar_v2(); //列印基礎日期資料(scheduleApi)
	 DrawWarring();
     DrawType_v2();//進度表類型
	 DrawTypeCont();//判斷印出內容
	 CheckinputType_v2();//判斷輸入
	 global   $BaseURL;
    DrawMembersLinkArea_Simple( 30, 6,  $BaseURL); 
    DrawOutLinkArea(30,52,$BaseURL);
	 DrawUserData( 1120, 5);   //使用者資料(PubApi)
	 DrawMemo();//臨時紀錄
     DrawInsertLine( );//
?>
 
<?php  //主要資料
 	 function  defineData_schedule(){
		 //基礎數值
		 global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum,$LineHeight,$LineRec ;
	     global $UpMonth;
		        $StartX=20;
	            $StartY=90;
	            $MonthWidth=200;
	            $OneDayWidth=15;
				$LineHeight=40;
	            $CurrentX= $StartX;
				$showMonthNum=8;
				$UpMonth=-2;
				$daysLoc=array();//(year,m,d,x軸位置)
                $monthLoc=array();//($y,m,x軸位置,Siz)
				$LineRec=array();//紀錄哪行有排列
		 //資料表
		 global $data_library,$tableName,$MainPlanData;
				$tableName="fpschedule";
			    $data_library="iggtaiperd2";
				$MainPlanDataT=getMysqlDataArray($tableName); 
				$MainPlanData=filterArray($MainPlanDataT,0,"data"); 
		 //共用資料表
	     global $OutsData,$memberData;
		 global $WarringDatas;
			    $WarringDatas= array();
                $OutsData=getMysqlDataArray("outsourcing");	 
      	        $memberData=getMysqlDataArray("members");
	            defineTypeData_v2();
	 }
	 function  defineTypeData_v2(){ //類別資料
	 		    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
			    global $stateType;
		        $BaseURL="schedule.php";
                $BackURL= $BaseURL."?Stype_1=".$Stype_1."&Stype_2=".$Stype_2;
				$sTypeTmp= getMysqlDataArray("scheduletype");	
				$SelectType_1tmp= filterArray($sTypeTmp ,0,"data");
			    $SelectType_1=   returnArraybySort($SelectType_1tmp,2);
			    $SelectType_2tmp= filterArray($sTypeTmp ,0,"data2");
				$SelectType_2=   returnArraybySort($SelectType_2tmp,2);
				$stateTypetmp= filterArray($sTypeTmp ,0,"data3");
				$stateType=   returnArraybySort($stateTypetmp,2);
				if($Stype_1=="")$Stype_1=0;
	 }
     function  DrawType_v2(){ //類別
		 	  global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum ;
	    	  global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
			  global $colorCodes;
			  global $CheckState;
			  $y=$StartY+10;
			  $x=120;
	          for ($i=0;$i<count( $SelectType_1);$i++){
                   $BackURL2= $BaseURL."?Stype_1=".$i."&Stype_2=".$Stype_2;
				   $msg=" ".$SelectType_1[$i];
				   $color= "#222222";
				   if($Stype_1==$i and $Stype_1!="")$color= "#dd2212";
			       DrawLinkRect($msg,"11","#ffffff",$x,$y,"50","16",$color,$BackURL2,1);
				   $x += 60;
			  }
			  DrawState();
			  global $stateType;
			  $x+=100;
			  for ($i=0;$i<count($SelectType_2);$i++){
			       $BackURL2= $BaseURL."?List=CheckState&Stype_2=".$i;
				   $msg=" ".$SelectType_2[$i];
				   $color= "#222222";
				   if($Stype_2==$i and  $Stype_2!="")$color= "#cc2212";
			       DrawLinkRect($msg,"10","#ffffff",$x,$y,"40","14",$color,$BackURL2,1);
				   $x+=50;
			  }
	 }
	 function  DrawDragHorArea($height ){//橫排區
	         global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX  ; 
			 	 $w= 40;//$OneDayWidth*count($daysLoc);
			     $y= $StartY+90;
				 $x=$startX+310;
				 $h=16;
			 for($i=0;$i<$height;$i++){
				 $BGColor="#555555";
				 $id="Line-".$i;
				 $y+=20;
				 DrawRect($i+1,12,"#ffffff",$x,$y,$w,$h,$BGColor); 
			    // DrawDragRect($x,$y,$w,$h,$BGColor,$id);
			    }
		}
	 function  DrawWarring(){ //收集錯誤
		 	   global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum ;
		       global $WarringDatas;
			   global $BaseURL;
			   CollectWarring();
	           if(count($WarringDatas)==0)return;
			   $pic="Pics/warring.gif";
			   $Link=$BackURL."?List=Warring";
			   $msg="　進度問題x".count($WarringDatas);
			   DrawRect($msg,"10","#ffffff",$StartX+10,$StartY+10,80,14,"#ee3333");
			   DrawLinkPic($pic,$StartY+10,$StartX+10,16,16 ,$Link);
			   
	 }
	 function  CollectWarring(){
	           global $WarringDatas;
	           global $MainPlanData;
		 
			   for( $i=0;$i<count($MainPlanData);$i++){
			       if(isPlaneWarring($MainPlanData[$i])=="true"){
				      array_push($WarringDatas,$MainPlanData[$i]);
				   }
			   }
	 }
	 function  isPlaneWarring($plansArray){
		         global $VacationDays; //年 月 日
		          if($plansArray[5]=="工項" or $plansArray[5]=="目標") return "false";
	              $startDay=explode("_",$plansArray[2]);
				  $nowDayArray=array(date(Y),date(m),date(d));
				  $passDays= getPassDays($startDay,$nowDayArray);
				  $realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2],$plansArray[6],$VacationDays);
				  if($realDays<1)$realDays=1;
				 
	              if($passDays>=$realDays && $plansArray[7]!="已完成") return "true";
				//  echo $startDay[1]."-".$startDay[2]."=".$passDays.">".$realDays."]";
				  
	              return "false";
	 }
	 function  DrawMemo(){
		       $Rect=array(1024,10,50,12);
	           $Link= "https://docs.google.com/document/d/1B8UBHJAsMGcSxbgN5yHyWQn4KkLwlfKz_tAOU2lS44E/edit?usp=sharing";
		       DrawLinkRect_newtab("memo","10","#ffffff",$Rect[0],$Rect[1],$Rect[2],$Rect[3],"#000000",$Link,"1");
			 
	 }
	 function  DrawInsertLine( ){
		       global $BackURL,$ELine,$LineRec;
		       global $LineHeight; 
			   global $StartY,$startX;
		 	 		  $w= 20;//$OneDayWidth*count($daysLoc);
			          $y= $StartY+90;
				      $x=$startX ;
				      $h=16; 
	           for($i=1;$i<$LineHeight;$i++){
				    $Link=$BackURL."&ELine=".$i;
					$y+=20;
					if($i==$ELine and $ELine!=""){
						$xAdd=0;
						 DrawLinkRect(" ","10","#ffffff",$x+40,$y+10,$w+1200,"6","#ff8888",$Link2,"1");
					    if(!in_array($i,$LineRec)){
							$xAdd=50;
						    $Link3=$BackURL."&PhpInputType=DeleteLine&DeletNum=".$i;
						    DrawLinkRect("刪除一行","10","#ffffff",$x+40,$y ,"45","20","#ff8888",$Link3,"1");
					    }
						$Link2=$BackURL."&PhpInputType=Insert&insertNum=".$i;
						DrawLinkRect("插入一行","10","#ffffff",$x+40+$xAdd,$y ,"45","20","#8888ff",$Link2,"1");
						DrawLinkPic($pic,$y,$x+130 ,"16","16",$BackURL);
					}
					 DrawLinkRect_Layer($i,"10","#ffffff",array($x,$y,$w,$h),"#aaaaaa",$Link," ",-1);
					$pic="Pics/Cancel.png";
			   }
	 }

	  
?>
<?php //新版摺疊
	  function DrawPlan_v3( ){//整理資料
	           	 global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
		         global $data_library,$tableName,$MainPlanData;
		         $type1=$SelectType_1[$Stype_1];
		         $plansTmp2 =  filterArray( $MainPlanData ,10, $type1);
		         $plansLine= filterArray($plansTmp2,5,"工項");
	             DrawMainPlan($plansLine);
	  }
	  function DrawMainPlan($plansLine){
		       global  $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
			   global  $MainPlanData;
			   global  $Expand ,$SLine,$colNum;
			   global  $colorCodes;
		       $BgColor="#444444";
	           for($i=0;$i<count($plansLine);$i++){
				   $plansLinet= filterArray($MainPlanData,3,$plansLine[$i][1]);
				   $Lines=count($plansLinet);
				   $Rect= returnRect($plansLine[$i],$yAddLnie,$yAddStartLine);
				   $line=$plansLine[$i][4];
				   $info=$plansLine[$i][3];//."[".count($plansLinet)."]";
				   $pic="Pics/triangleLeft.png";
				   $ExLink=$BackURL."&Expand=".$plansLine[$i][1]."&SLine=".$plansLine[$i][4]."&colNum=".$Lines ;
				   $ELink=$BackURL."&PhpInputType=EditPlan&Ecode=".$plansLine[$i][1];
				   //展開
				   $Exp="false";
				   if($Expand!=""){
				      if($line>$SLine)$Rect[1]+=20*$colNum;
					  if($line==$SLine){
						  $Exp="true";
						  $pic="Pics/triangleDown.png";
						  $ExLink=$BackURL ;
					  }
				   }
				   DrawLinkRect_Layer($info,10,"#ffffff",$Rect,$BgColor,$ELink,"",0);
				   if( $Lines>1) DrawLinkPic($pic,$Rect[1],($Rect[0]+$Rect[2]  ),14,14,$ExLink);
				   //jilar
				   $Link=$BackURL."&PhpInputType=AddPlanType&Ecode=".$plansLine[$i][1];
					if( $plansLine[$i][12]!=""){
				        $JilaLink="http://bzbfzjira.iggcn.com/browse/FP-".$plansLine[$i][12]  ;
					    DrawLinkRect_newtab($plansLine[$i][12],"9","#000000", $Rect[0],$Rect[1]+2,"22" ,"11", $colorCodes[0][3],$JilaLink,"1" );
					}   //jilar
					DrawLinkRect("+","10","#ffffff",$Rect[0]+$Rect[2]-12,$Rect[1]+2,"12" ,"12", "#555555",$Link,"1");
				    DrawWorks($plansLine[$i][1],$Rect[0]+$Rect[2],$Rect[1],$Exp );
			   }
	  }
	  function DrawWorks($Code,$x,$y, $Exp ){
		       global  $MainPlanData,$BackURL;
		       $plansLinet= filterArray($MainPlanData,3,$Code);
			   $plansLine= SortbyDate($plansLinet);
               if( count($plansLine)==0)return;
               $length=$plansLine[count($plansLine)-1]['DayLoc']-$x;
			   if($Exp=="false")  
				   DrawRect_Layer("",1,"#000000",array($x,$y+10,$length,1),"#444444",-12);
			   if($Exp=="true") {
				   $pic="Pics/Black20Bg.png";
				 //  $l=
				   DrawPic_Layer($pic,$x,$y+16,$length+200,count($plansLine)*20+5,-10);
				   $x+=6;
			       DrawRect_Layer("",1,"#000000",array($x,$y+10,2,count($plansLine)*20),"#444444",-11);
			   }
			   for($i=0;$i<count($plansLine);$i++){
				    $BgColor= GetBarColor($plansLine[$i][5],$plansLine[$i][7]);
				    $Rect=returnRect($plansLine[$i] ,$yAddLnie,$yAddStartLine);
					$Rect[1]=$y;
					if($Exp=="true")$Rect[1]+=($i+1)*20;
					$p=$plansLine[$i][9];
					if($p=="" or $p=="未定義")$p=$plansLine[$i][8];
					$info= $plansLine[$i][5] ."[".$plansLine[$i][6]."]".$p;
				    $Layer= $i-count($plansLine);
					$Link=$BackURL."&PhpInputType=DrawEditPlanType&Ecode=".$plansLine[$i][1];
					if($Exp=="true") 
					   	DrawRect_Layer("",1,"#000000",array($x,$Rect[1]+10,$length,1),"#444444",-12);
				    DrawRect_Layer( "",10,"#000000",$Rect,$BgColor ,$Layer);
                    $w=311;
					 DrawText_Layer( $info,$Rect[0],$Rect[1],$w ,11,11,"#000000",$Layer);
				    DrawLinkRect_Layer_Left( "",10,"#000000",$Rect,"",$Link,"",$Layer);
					$realDays=getRealDay($plansLine[$i]);
				    DrawStatePics($plansLine[$i],$Rect[0],$Rect[1],$realDays,$Link);
			   }
	  }
      function getRealDay($SinglePlanData){
	  	       global $VacationDays;
			   $startDay=explode("_",$SinglePlanData[2]);
			   $workDays=$SinglePlanData[6] ;
			   $realDays=$workDays;
               if($workDays>=1)	{
				  $realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2], $workDays ,$VacationDays);
		      }
			  return  $realDays;
	  }
      function returnRect($SinglePlanData,$yAddLnie,$yAddStartLine){
	          global $StartX, $StartY,$OneDayWidth,$daysLoc,$MainPlanData,$OneDayWidth;
	          global $VacationDays;
			  $startDay=explode("_",$SinglePlanData[2]);
			  $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			  $x=RetrunXpos($daysLoc,$d);
			  $y= $StartY+90+($SinglePlanData[4]+1)*20;
			  if($SinglePlanData[5]=="工項"){
				 $t=strlen($SinglePlanData[3]);
				 $w= 6*$t +20 ;
				 $sx=$x-$w-20 ;
				 return array($sx,$y,$w,16);
				}
			    $realDays= getRealDay($SinglePlanData);
			    $w= $OneDayWidth*$realDays;
				if($realDays<1)$w=8;
			    return array($x,$y,$w,16); 
	  }		  
      function GetBarColor($plan_type,$State){
		       global  $colorCodes,$SelectType_2;
	           $color=$colorCodes[9][0];
			    for($i=0;$i<count($SelectType_2);$i++){
					if($SelectType_2[$i]==$plan_type){
						$c=$i%8;
						$color=$colorCodes[9][$c];
						   if($State=="已完成")	$color=$colorCodes[10][$i];
					}
				}
				return $color;
	  }
?>
<?php  //繪製計畫 

      function DrawTypeCont(){
		       global $List,$SelectType_1,$Stype_1,$Stype_2 ;
			    $type1=$SelectType_1[$Stype_1];
			   if($List!=""){
				   DrawListWorks();
			       return;
			   }
			   if($type1=="總規劃"){
			     DrawPlan_v2();
			     return;
			   }
			   DrawPlan_v3();
      }
      function DrawListWorks(){
	  	      global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
		      global $data_library,$tableName;
			  global $user,$List;
			  global $WarringDatas;
			  //global $memberId;
			  global $OutsData,$memberData;
			  $plansTmp=getMysqlDataArray($tableName); 
			  switch ($List){
				  case "ArtWork":
				       $idtmp=returnDataArray( $memberData,0,$user);
				       $id=$idtmp[1];
				       $plans= filterArray($plansTmp,8,$id);
				  break;
				  case "Out":
				        $idtmp=returnDataArray( $OutsData,1,$user);
				        $id=$idtmp[2];
				        $plans= filterArray($plansTmp,9,$id);
				  break;
				  case "Warring";
				        $plans= $WarringDatas;
				  break;
				  case "CheckState";
				        $plans=  filterArray($plansTmp,5,$SelectType_2[$Stype_2]);
				  break;
		  	  }
			  $plans= RemoveArray($plans,7,"已完成");
		   	  $plans= SortbyDate($plans);
		 
              $JobsArray=array( );
		      for($i=0;$i<count($plans);$i++){
				   DrawListBar($plans[$i],$i);
			       $color_num+=1;
			       if( $color_num>7)$color_num=3;
				   $codeA=returnDataArray( $plansTmp,1,$plans[$i][3]);//取得主資料array
				   $job=$codeA[3]."[".$plans[$i][5]."][".$plans[$i][7]."]".$plans[$i][6]."天";
				   $color="#aaaaaa";
				   if ($plans[$i][7]=="已完成")$color="#777777";
				   if ($plans[$i][7]=="進行中")$color="#ffccff";
				   array_push($JobsArray,array($job,$color));
		          }
			
			  DrawListInfo( $idtmp,$JobsArray);
	  }
	  function DrawListBar($plansArray,$i){
		       global $colorCodes;
			   global $VacationDays;
			   global $StartX, $StartY,$OneDayWidth,$daysLoc,$MainPlanData,$OneDayWidth;
			   global $BaseURL,$BackURL;
		       $startDay=explode("_",$plansArray[2]);
			   $realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2],$plansArray[6],$VacationDays);
		       $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			   $x=RetrunXpos($daysLoc,$d);
	           $y= $StartY+90+($i+1)*20;
			   $codeA=returnDataArray( $MainPlanData,1,$plansArray[3] );//取得主資料array
			   $msg="[".$plansArray[10]."]".$plansArray[12]."_".$codeA[3].">".$plansArray[5] ;
			   $color=$colorCodes[6][2];
			   //$w=10* ((strlen($msg)/2));
		       $w= $OneDayWidth*$realDays;
			   $Link=$BaseURL."?PhpInputType=DrawEditPlanType&Ecode=".$plansArray[1];
			   DrawLinkRectAutoLength( $msg,"10","#000000",$x, $y,$w ,"16", $color,$Link,"1");
				//狀態圖
			   DrawStatePics($plansArray,$x,$y,$realDays,$Link);
	  }
      function DrawListInfo($UserArray,$JobsArray ){
		       global $List; 
			   global $BackURL;
		       $ex=20;
	           $ey=240;
			   $w=230;
			   $h=count( $JobsArray)*30;
			   $title="";
			   if( $List=="ArtWork"){
			       $title=$UserArray[1]."排程";
			   }
		       if( $List=="Out"){
			       $title=$UserArray[2]."排程";
			   }
			   DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
			   
	           for($i=0;$i<count( $JobsArray);$i++){
				    $ey+=20;
					$info=$i.".". $JobsArray[$i][0];
					$color=$JobsArray[$i][1];
				    DrawRect($info,"11","#322222",$ex-10,$ey,240 ,"20",$color);
			   }
	  }
      function DrawPlan_v2(){
	       global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
		   global $data_library,$tableName,$MainPlanData;
		   $type1=$SelectType_1[$Stype_1];
		   $plansTmp2 =  filterArray( $MainPlanData ,10, $type1);
	       $plans= filterArray($plansTmp2,0,"data");
		   $plansLine= filterArray($plansTmp2,5,"工項");
		   $color_num=3;
           DrawBgLine($plansLine);
		   if($type1=="總規劃"){
		      $plansLine2= filterArray($plansTmp2,5,"目標");
			  for($i=0;$i<count($plansLine2);$i++){
				  DrawTargetLine($plansLine2[$i]);
			  }
		   }
		   for($i=0;$i<count($plans);$i++){
              DrawPlanBar($color_num,$y,$plans[$i] );
			  $color_num+=1;
			  if( $color_num>7)$color_num=3;
		   }
	  }
	  function DrawTargetLine($plansLine2){
		  	   global $daysLoc, $StartY;
		       $startDay=explode("_",$plansLine2[2]);
		       $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			   $x=RetrunXpos($daysLoc,$d);
			   $y= $StartY+90+$plansLine2[4]*20;
	           $color="#772233";
			   $fontColor="#eeeeee";
			   $line=$plansLine2[4];
			   DrawabsoluteRect("","0",$fontColor, $x, $y-20*$line,"2" ,$line*20 , $color,  "absolute", $Link );
			   DrawLinkRect($info,"10",$fontColor,$x,$y,$w ,"16", $color,$Link,"1");
	  }
	  function DrawBgLine($plansLine){
		  	   global $daysLoc, $StartY;
	  		   for($i=0;$i<count( $plansLine);$i++){
			    if($plansLine[$i][10]!="總規劃"){
				   $startDay=explode("_",$plansLine[$i][2]);
		           $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			       $x=RetrunXpos($daysLoc,$d);
				   $y= $StartY+90+$plansLine[$i][4]*20;
				   DrawabsoluteRect("","0","", $x-20, $y+9,"300" ,1 ,"#666666",  "absolute", "" );
				}
		   }
	  }
      function DrawPlanBar( $color_num,$y,$plansArray ){
		       global $StartX, $StartY,$OneDayWidth,$daysLoc, $CurrentX,$monthLoc,$showMonthNum ,$LineRec ;
		       global $colorCodes;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1, $SelectType_2;
			   global $user,$List;
			   global $data_library,$tableName,$MainPlanData;
			   global $VacationDays; //年 月 日
		       $fontColor="#222222";
			   $startDay=explode("_",$plansArray[2]);
		       $d=returnDateString($startDay[0],$startDay[1],$startDay[2]);
			   $x=RetrunXpos($daysLoc,$d);
			   $y= $StartY+90+$plansArray[4]*20;
			   $line=$plansArray[4];
			   $w= $OneDayWidth*$plansArray[6];
			   $color= $colorCodes[$color_num][1];
			   $info=$plansArray[3] ;
			   $plan_type=$plansArray[5];//工項/目標
			   $type=$plansArray[10];//總規劃//腳色分類
			   $Link=$BackURL."&PhpInputType=EditPlan&Ecode=".$plansArray[1];
			   if($type=="總規劃"){
			      switch($plan_type){
				         case "工項":
						 DrawLinkRect($info,"10",$fontColor,$x,$y,$w ,"16", $color,$Link,"1");
				         break;
						 case "目標":
						 $color="#772233";
				         $fontColor="#eeeeee";
						 DrawLinkRect($info,"10",$fontColor,$x,$y,$w ,"16", $color,$Link,"1");
						 break; 
				  }
				  return;
			   }
			   //細部規劃
			   if ($plan_type=="工項"){
				    Array_Push($LineRec,$plansArray[4]);
				    $t=strlen($info);
					$add="";
			        if($plansArray[12]!=""){   $add="　　　"; $t+=5;}
				    $w= 6*$t ;
				    $sx=$x-$w-20;
					DrawLinkRect(  $add . $info,"10","#ffffff",$sx,$y,$w ,"16", "#666666",$Link,"1");
					$Link=$BackURL."&PhpInputType=AddPlanType&Ecode=".$plansArray[1];
					if($plansArray[12]!=""){
				        $JilaLink="http://bzbfzjira.iggcn.com/browse/FP-".$plansArray[12]  ;
					    DrawLinkRect_newtab($plansArray[12],"9","#000000",$sx+2,$y+2,"25" ,"11", $colorCodes[0][3],$JilaLink,"1" );
					}
					DrawLinkRect("+","10","#ffffff",$x-20,$y+2,"12" ,"12", "#555555",$Link,"1");
					return;
				}
				/*
			   //分工
                $codeA=returnDataArray( $MainPlanData,1,$plansArray[3] );//取得主資料array
			    if($codeA==null)return;
				$y=($StartY+90+$codeA[4]*20);
				$Link=$BackURL."&PhpInputType=DrawEditPlanType&Ecode=".$plansArray[1];
				$color=$colorCodes[9][0];
			    for($i=0;$i<count($SelectType_2);$i++){
				 
					if($SelectType_2[$i]==$plan_type){
						$c=$i%8;
						$color=$colorCodes[9][$c];
						   if($plansArray[7]=="已完成")	$color=$colorCodes[10][$i];
					}
				}
				$realDays= $workDays;
				$workDays=$plansArray[6] ;
                if($workDays>=1)	{
				$realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2],$plansArray[6],$VacationDays);
				}
			    $w= $OneDayWidth*$realDays;
				if($realDays<1)$w=8;
				$NameBackAdd="[".$workDays."][".$plansArray[9]."]";
				if($plansArray[9]=="" or $plansArray[9]=="未定義"){
							$NameBackAdd="[".$workDays."][".$plansArray[8]."]";
							if($plansArray[7]=="" or $plansArray[7]=="未定義") 
								$NameBackAdd="[?][".$plansArray[8]."]";
				}
                DrawLinkRectAutoLength($NameAdd.">".$plan_type.$NameBackAdd,"10","#000000",$x, $y,$w ,"16", $color,$Link,"1");
				//jilar
				if($plansArray[12]!=$codeA[12] && $plansArray[12]!=""){
				        $JilaLink="http://bzbfzjira.iggcn.com/browse/FP-".$plansArray[12]  ;
					    DrawLinkRect_newtab($plansArray[12],"8","#000000",$x+5, $y+10,"20" ,"8", $colorCodes[0][3],$JilaLink,"0" );
					}
				
				//狀態圖
			    DrawStatePics($plansArray,$x,$y,$realDays);
				*/
	  }
	  function DrawStatePics($plansArray,$x,$y,$realDays,$Link){
		  		 global $OutsData,$memberData;
				 $pic="";
			     if($plansArray[7]=="" or $plansArray[7]=="未定義")$pic="Pics/question";
				 if($plansArray[7]=="已完成")$pic="Pics/finish";
				 if($plansArray[7]=="進行中")$pic="Pics/construction";
				 //狀態問題
				  $startDayArray=explode("_",$plansArray[2]);
				  $nowDayArray=array(date(Y),date(m),date(d));
				  if($realDays<1)$realDays=1;
				  $passDays= getPassDays($startDayArray,$nowDayArray);
	              if($passDays>=$realDays && $plansArray[7]!="已完成"){
					 $pic="Pics/warring.gif";
				  }
				  if( $pic!="")
			      //DrawPosPic($pic, $y,$x-6,16,16,"absolute" );
			       DrawLinkPic($pic,$y,$x-6,16,16,$Link);
	  }  
	  function DrawWorkDetail($ex,$ey,$w,$h){
	          global $data_library,$tableName;   
	          global $colorCodes;
		      global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$stateType;
			  global $Ecode;
			  $planstmp=getMysqlDataArray($tableName);
			  $plansArray=returnDataArray($planstmp,1,$Ecode);
			  $rootName= returnDataArray($planstmp,1,$plansArray[3]);
	  		  $title="[".$rootName[3]."] [".$plansArray[5]."]開始日:".$plansArray[2].">".$plansArray[6]."人天";
		      DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
			  $Link=$BackURL."&PhpInputType=EditPlanType&Ecode=".$plansArray[1];
			  DrawLinkRect("　Edit","10","#ffffff",$ex+333,$ey+2,42 ,"16","#000000",$Link,"1");
			  $fontColor="#000000";
			  $BgColor="#eeeeee";
			  $out="外包負責人:".$plansArray[9];
			  $ey+=30;
			  DrawRect($out,12,$fontColor,$ex,$ey,200,16,$BgColor);
			  $out="內部負責人:".$plansArray[8];
			  $ey+=30;
			  DrawRect($out,12,$fontColor,$ex,$ey,200,16,$BgColor);
			  $info="狀態".$plansArray[7];
			  $ey+=30;
			  DrawRect($info,12,$fontColor,$ex,$ey,200,16,$BgColor);
			  //連結
			  $paths= getResfilePath($rootName[3],$plansArray[5] );
 
			   if( file_exists( $paths[0]) or file_exists( $paths[1])){
				   if( file_exists( $paths[2]) ){
					   $pic=$paths[2];
				       $Link=  $paths[1];
			           DrawLinkPic($pic,$ey-60,$ex+220 ,128,128,$Link);
			         } 
					 $pic="Pics/file.png";
				     $Link=  $paths[0];
				     DrawLinkPic($pic,$ey+28,$ex +165  ,20,20,$Link);	 
			  }
		       
			  
			  if($plansArray[14]=="")return;
			  $ey+=30;
			  $info="完成連結 " ;
			  $Link=$plansArray[14];
			  DrawLinkRect_newtab( $info,12,$fontColor,$ex,$ey,160,16,$BgColor,$Link,"");
 
		
			  
			//  DrawRect($info,12,$fontColor,$ex,$ey,200,16,$BgColor);
	  }
?>

<?php  //輸入
      function CheckinputType_v2(){
	       global $epy,$epm,$epd,$epLine,$epDay,$eptype,$LineHeight;
	       global $ed,$em,$ey,$dx,$dy;
		   global $PhpInputType;
		   if($PhpInputType=="")return;
		   include('scheduleOrder.php');
		   switch ($PhpInputType){
			    case $PhpInputType=="AddPlanType":
			         AddPlanTypeEditor_v2("400","260","400","120",$ey,$em,$ed); 
			    break;
		        case $PhpInputType=="AddPlan":
			     	 DrawDragHorArea(25 );
			         AddPlanEditor_v2("400","260","400","120",$ey,$em,$ed); 
			    break;
		        case $PhpInputType=="EditPlan":
					 DrawDragHorArea(25 );
			          EditPlan_v2("400","260","400","120" );
			    break;
			    case $PhpInputType=="upAdd":
			    	 AddData( );
			    break;
			    case $PhpInputType=="upEdit":
			    	 UpEditData( );
			    break;
				case $PhpInputType=="upAddType":
			    	 AddTypeData( );
			    break;
				case $PhpInputType=="EditPlanType":
			    	 EditPlanTypeEditor_v2("400","260","400","170");
			    break;
			    case $PhpInputType=="upEditPlanType":
			    	 UpEditData( );
			    break;
				case $PhpInputType=="DrawEditPlanType":
			         DrawWorkDetail("400","260","400","170");
			    break;
				case $PhpInputType=="Insert":
				     MoveLines("Insert");
				break;
				case $PhpInputType=="DeleteLine":
				      MoveLines("Delete");
				break;
		   }
	 
	 }
?>

<?php  //Updata
     function MoveLines($MoveType){
	          global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
			  global $LineHeight,$insertNum,$DeletNum;
              global $data_library,$tableName,$MainPlanData;
			  $sT=$SelectType_1[$Stype_1];
              $WorkOrderstmp=filterArray($MainPlanData,10,$sT); 
			  $WorkOrders=filterArray($WorkOrderstmp,5,"工項"); 
			  for($i=1;$i<count($WorkOrders);$i++){
				  $L=$WorkOrders[$i][4];//行數
				  switch($MoveType){
					  case $MoveType=="Insert":
				  	       if($L>=$insertNum){
					         $Move2Line=$L+1;
				             MoveLine($WorkOrders[$i][1],$Move2Line);
						   }
					    break;
				   	   case $MoveType=="Delete":
				  	       if($L>=$DeletNum){
					         $Move2Line=$L-1;
				             MoveLine($WorkOrders[$i][1],$Move2Line);
						   }
					   break; 
				  }
			  }
		     echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
	 function MoveLine($code,$Move2Line){
		      global $data_library,$tableName;
			  $WHEREtable=array( "data_type", "code" );
		      $WHEREData=array( "data",$code );
			  $Base=array("line");
			  $up=array($Move2Line);
			  $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
              SendCommand($stmt,$data_library);			   
	 }
     function UpEditData( ){
		       global $data_library,$tableName,$MainPlanData;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			   global $year,$month,$day;
			   global $submit;
			   global $del;
			   $p=$tableName;
			   $tables=returnTables($data_library,$p);
			   $plansArray=returnDataArray($MainPlanData,1,$Ecode);
	           $t= count( $tables);
			   $Base=array();
			   $up=array();
			   
		       for($i=0;$i<$t;$i++){
	       	       global $$tables[$i];
				   		  $startDay=$year."_".$month."_".$day;
				          array_push($Base,$tables[$i]);
                          array_push($up,$$tables[$i]);
				        //  echo  "</br>".$tables[$i].">".$$tables[$i];
		       }
			   $WHEREtable=array( "data_type", "code" );
		       $WHEREData=array( "data",$code );
			   if($submit=="修改計畫"){
			    $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
                SendCommand($stmt,$data_library);			   
			   }
		       if($submit=="送出修改"){
				   $stmt= MakeUpdateStmt(  $data_library,$tableName,$Base,$up,$WHEREtable,$WHEREData);
                    SendCommand($stmt,$data_library);			
				   //上傳檔案
				   $plansArray=returnDataArray($MainPlanData,1,$up[3]);
				   UpFiles($up,$plansArray[3]);
 
			      
			   }   
			   if($submit=="刪除計畫"){
			      if($del!="") $stmt= MakeDeleteStmt($tableName,$WHEREtable,$WHEREData); 
				     SendCommand($stmt,$data_library);
			   }
	         echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
	 }
     function AddData( ){
		       global $data_library,$tableName;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			       $p=$tableName;
				   $tables=returnTables($data_library,$p);
	               $t= count( $tables);
			      
				   $WHEREtable=array();
				   $WHEREData=array();
		           for($i=0;$i<$t;$i++){
	       	            global $$tables[$i];
				        array_push($WHEREtable,$tables[$i]);
					    array_push($WHEREData,$$tables[$i]);
					 //   echo  "</br>".$tables[$i].">".$$tables[$i];
		              }
					$stmt=   MakeNewStmtv2($tableName,$WHEREtable,$WHEREData);
				    SendCommand($stmt,$data_library);
				    echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
		      	// echo $stmt;
	 }
	 function UpFiles($datas,$gdnamet){
			   $gdname=trim($gdnamet);
			   $typepath=returnResDirbyGDname($gdname);
		       if($typepath=="")return;
			   $Gd=substr($gdname, 0, 5);
			   $temp = explode(".", $_FILES["file"]["name"]);
			   if($temp[1]=="")return;
			   $dirs=returntypeDir($datas[5]);
			   if($dirs=="")return;
			   for($i=0;$i<count($dirs);$i++){
				   $ex=$temp[1];
				   if($i>0)$ex="png";
				   $path[$i]="ResourceData/". $typepath."/".$dirs[$i]."/".$Gd.".".$ex;
				   if($i==0){
				     move_uploaded_file($_FILES["file"]["tmp_name"], $path[0]);  
				   }
				   if($i==1){
				     $cmd="convert      $path[0]    -flatten   $path[1] ";
					   exec($cmd);
				   }
				   if($i==2){
				     $cmd="convert      $path[1]    -flatten -resize 128  $path[2] ";
					   exec($cmd);
				   }
			   }
 
			 
	 }
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

<?php  //Oder
     function EditPlanTypeEditor_v2($ex,$ey,$w,$h){
            global $data_library,$tableName;   
	        global $colorCodes;
		    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$stateType;
			global $Ecode;
            //echo  ">>>>>>>>>>>>".$Ecode;
	 
			$planstmp=getMysqlDataArray($tableName);
			$plansArray=returnDataArray($planstmp,1,$Ecode);
			$rootName= returnDataArray($planstmp,1,$plansArray[3]);
			$title="修改 [". $rootName[3]."] [".$plansArray[5]."]內容";
		    DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
		    $startDay=explode("_",$plansArray[2]);
			//From
		    echo   "<form id='AddPlan'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
			//基礎資料
			$p=$tableName;
			$tables=returnTables($data_library,$p);
			for($i=0;$i<count($tables);$i++){
			    echo   "<input type=hidden name=".$tables[$i]." value=".$plansArray[$i].">";
			}
 
			echo   "<input type=hidden name=code value=".$Ecode.">";
		   	echo   "<input type=hidden name=PhpInputType value=upEditPlanType >";	
			$lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		    echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
			$ey+=20;
			 //年
	         $input="<input type=text name=year value='".$startDay[0]."'  size=4>年";
	         DrawInputRect("開始","12","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top",$input);
	         //月
	         $input="<input type=text name=month value='".$startDay[1]."'  size=2>月";
	         DrawInputRect("","12","#ffffff",($ex+80),$ey ,120,16, $colorCodes[4][2],"top",$input);
		     //日
	         $input="<input type=text name=day value='".$startDay[2]."'  size=2>日";
	         DrawInputRect("","14","#ffffff",($ex+130),$ey ,220,16, $colorCodes[4][2],"top",$input);
			 //天數
			 $workDayinput="<input type=text name=workingDays  value='".$plansArray[6]."'  size=2   >";
	         DrawInputRect("天數","12","#ffffff",($ex+190),$ey ,120,18, $colorCodes[4][2],"top",$workDayinput);
	        //JilaLink
		     $jirainput="<input type=text name=remark  value='".$plansArray[12]."'  size=4   >";
	         DrawInputRect("副jila單","12","#ffffff",($ex+280),$ey ,120,18, $colorCodes[4][2],"top",$jirainput);			 
			 $ey+=40;
			 //外包負責
			 $OutsDatatmp=getMysqlDataArray("outsourcing");
	         $OutsDatatmp2=filterArray($OutsDatatmp,0,"data");
	         $OutsData=returnArraybySort( $OutsDatatmp2,2);
			 $selectTable= MakeSelectionV2($OutsData,$plansArray[9] ,"outsourcing",10);
		     DrawInputRect( "選擇負責外包","10","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top", $selectTable);
			 //負責人
			 $principaltmp=getMysqlDataArray("members");
			 $principalData=returnArraybySort( $principaltmp,1);
			 $selectTable= MakeSelectionV2( $principalData,$plansArray[8],"principal" ,10);
			 DrawInputRect( "選擇內部負責","10","#ffffff",($ex+120),$ey ,120,16, $colorCodes[4][2],"top", $selectTable);
			 //狀態
			 $selectTable= MakeSelectionV2( $stateType,$plansArray[7],"state" ,10);
			 DrawInputRect( "目前狀態","10","#ffffff",($ex+250),$ey ,120,16, $colorCodes[4][2],"top", $selectTable);
		
			 //送出
		     $submitP="<input type=submit name=submit value=送出修改>";
	         DrawInputRect("",$ey-120 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
			 //圖檔
			 $ey+=50;
			 $input="<input type=file name=file 	id=file    size=60   >";
		     DrawInputRect("上傳完成檔案","12","#ffffff", ($ex ),$ey ,320,16, $colorCodes[4][2],"top", $input);
			 		 $ey+=30;
			 $fininput="<input type=text name=finLink  value='".$plansArray[14]."'  size=50   >";

	
	         DrawInputRect("完成連結","12","#ffffff",($ex ),$ey  ,420,18, $colorCodes[4][2],"top",$fininput);
			 
			 //刪除
	         $input="<input type=text name=del value=''  size=3>";
	         DrawInputRect("輸入刪除碼","12","#ffffff",($ex+200),$ey+30,220,16, $colorCodes[4][2],"top",$input);	
		     $submitP="<input type=submit name=submit value=刪除計畫>";
	         DrawInputRect("",$ey-40 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
   }
     function AddPlanTypeEditor_v2($ex,$ey,$w,$h,$y,$m,$d){
         global $data_library,$tableName;   
	     global $colorCodes;
		 global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2;
		 global $Ecode;
         DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
		 $planstmp=getMysqlDataArray($tableName);
		 $plansArray=returnDataArray($planstmp,1,$Ecode);
		 $startDay=explode("_",$plansArray[2]);
		 //From
		 echo   "<form id='AddPlan'  name='Show' action='".$BackURL."' method='post'>";
		 $code=returnDataCode( );
		 echo   "<input type=hidden name=code value=".$code.">";
	 	 echo   "<input type=hidden name=plan value=".$Ecode.">"; 		 
		 echo   "<input type=hidden name=PhpInputType value=upAddType >";
		 echo   "<input type=hidden name=line value=".$plansArray[4]." >";
		// echo   "<input type=hidden name=plan value=".$plansArray[3]." >";
		 echo   "<input type=hidden name=data_type value=data>"; 
		  $selecttype= $SelectType_1[$Stype_1];
		 echo   "<input type=hidden name=selecttype value=".$selecttype.">"; 
		  $lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		 echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
		 // echo   "<input type=hidden name=type value=細分>"; 
		 //年
	     $input="<input type=text name=year value='".$startDay[0]."'  size=4>年";
	     DrawInputRect("新增","12","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top",$input);
	     //月
	     $input="<input type=text name=month value='".$startDay[1]."'  size=2>月";
	     DrawInputRect("","12","#ffffff",($ex+80),$ey ,120,16, $colorCodes[4][2],"top",$input);
		 //日
	     $input="<input type=text name=day value='".$startDay[2]."'  size=2>日".$plansArray[3];
	     DrawInputRect("","14","#ffffff",($ex+130),$ey ,220,16, $colorCodes[4][2],"top",$input);
		 //類別計畫
	     $select=MakeSelectionV2($SelectType_2,"設定","type",140);
	     DrawInputRect("","14","#ffffff",($ex+310 ),$ey,140,18, $colorCodes[4][2],"top",  $select."計畫");
	     $workDayinput="<input type=text name=workingDays  value='5'  size=2   >";
	     DrawInputRect("天數","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$workDayinput);

		 $submitP="<input type=submit name=submit value=新增規畫>";
	     DrawInputRect("",$ey-22 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
 
 }
     function AddPlanEditor_v2($ex,$ey,$w,$h,$y,$m,$d){
	     global $data_library,$tableName;   
	     global $colorCodes;
		 global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
	     if($Stype_1=="")$Stype_1=0;
  	     $title="新增".$y."年".$m."月".$d."日" .$SelectType_1[$Stype_1]."計畫";
		//  echo $BackURL;
         DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
		 //From
		 echo   "<form id='AddPlan'  name='Show' action='".$BackURL."' method='post'>";
		 //Hides
		// echo   "<input type=hidden name=BackURL  value=".$BackURL.">"; 
		 echo   "<input type=hidden name=tablename value=".$tableName.">"; 
		 echo   "<input type=hidden name=data_type value=data>"; 
	  	 echo   "<input type=hidden name=PhpInputType value=upAdd >"; 
		 $startDay=$y."_".$m."_".$d;
		 echo   "<input type=hidden name=startDay value=".$startDay.">"; 
		 $lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		 echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
		 $code=returnDataCode( );
		 echo   "<input type=hidden name=code value=".$code.">"; 
		 $selecttype= $SelectType_1[$Stype_1];
		 echo   "<input type=hidden name=selecttype value=".$selecttype.">"; 
		 $plan="新計畫";
		 //input
	     $Planinput="<input type=text name=plan value='".$plan."'  size=30 >";
	     DrawInputRect("計畫","12","#ffffff",($ex),$ey+40,300,18, $colorCodes[4][2],"top",$Planinput);
		 //
	 
		 if($Stype_1==0 or  $Stype_1==""){
		      $workDayinput="<input type=text name=workingDays  value='5'  size=2   >";
	          DrawInputRect("天數","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$workDayinput);
		 }else{
		    	$jirainput="<input type=text name=remark  value='".$plansArray[12]."'  size=4   >";
	        DrawInputRect("jila單","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$jirainput);
		 }
		 
		 $Lineinput="<input type=text name=line value='1'  size=2   >";
     	 DrawInputRect("行數","12","#ffffff",($ex+240),$ey+70,120,18, $colorCodes[4][2],"top", $Lineinput);
	
		 $types=array("工項","目標","Sprint");
	     $select=MakeSelectionV2($types,"工項","type",160);
	     DrawInputRect("類型","10","#ffffff",($ex ),$ey+70,120,18, $colorCodes[4][2],"top",  $select);
 	 
		 $submitP="<input type=submit name=submit value=新增計畫>";
	     DrawInputRect("",$ey-20 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);

  }
     function EditPlan_v2($ex,$ey,$w,$h){
	        global $data_library,$tableName;   
			global $Ecode;
		    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
						
			$planstmp=getMysqlDataArray($tableName);
			$plansArray=returnDataArray($planstmp,1,$Ecode);
		    echo   "<form id='EditPlan'  name='Show' action='".$BackURL."' method='post'>";
			echo   "<input type=hidden name=data_type value=data>"; 
		    echo   "<input type=hidden name=tablename value=".$tablename.">"; 
	  	    echo   "<input type=hidden name=PhpInputType value=upEdit >"; 
		    $lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		    echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
		    echo   "<input type=hidden name=code value=".$Ecode.">"; 
		    $selecttype= $SelectType_1[$Stype_1];
		    echo   "<input type=hidden name=selecttype value=".$selecttype.">"; 
		    DrawPopBG($ex,$ey,$w,$h,"" ,"12",$BackURL);
			//年
			$startDay=explode("_",$plansArray[2]);
			$input="<input type=text name=year value='".$startDay[0]."'  size=4>年";
	        DrawInputRect("修改","12","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top",$input);
			//月
		    $input="<input type=text name=month value='".$startDay[1]."'  size=2>月";
	        DrawInputRect("","12","#ffffff",($ex+80),$ey ,120,16, $colorCodes[4][2],"top",$input);
			//日
		    $input="<input type=text name=day value='".$startDay[2]."'  size=2>日計畫";
	        DrawInputRect("","12","#ffffff",($ex+130),$ey ,120,16, $colorCodes[4][2],"top",$input);
			
	        $Planinput="<input type=text name=plan value='".$plansArray[3]."'  size=30 >";
	        DrawInputRect("計畫","12","#ffffff",($ex),$ey+40,300,18, $colorCodes[4][2],"top",$Planinput);
			 if($Stype_1==0 or  $Stype_1==""){
		        $workDayinput="<input type=text name=workingDays  value='5'  size=2   >";
	            DrawInputRect("天數","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$workDayinput);
		     }else{
		    	$jirainput="<input type=text name=remark  value='".$plansArray[12]."'  size=4   >";
	            DrawInputRect("jila單","12","#ffffff",($ex+240),$ey+40,120,18, $colorCodes[4][2],"top",$jirainput);
		     }
 
		 
		    $Lineinput="<input type=text name=line value='".$plansArray[4]."'  size=2   >";
     	    DrawInputRect("行數","12","#ffffff",($ex+240),$ey+70,120,18, $colorCodes[4][2],"top", $Lineinput);
	        
		    $types=array("工項","目標","Sprint");
	        $select=MakeSelectionV2($types,$plansArray[5],"type",160);
	        DrawInputRect("類型","10","#ffffff",($ex ),$ey+70,120,18, $colorCodes[4][2],"top",  $select);
 	 
		    $submitP="<input type=submit name=submit value=修改計畫>";
	        DrawInputRect("",$ey-60 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
				
             
			//刪除
	        $input="<input type=text name=del value=''  size=3>";
	        DrawInputRect("輸入刪除碼","12","#ffffff",($ex+222),$ey+130 ,220,16, $colorCodes[4][2],"top",$input);	
			
		    $submitP="<input type=submit name=submit value=刪除計畫>";
	        DrawInputRect("",$ey+60 ,"#ffffff",($ex+320),60,120,18, $colorCodes[4][2],"top",$submitP);
	 }
?>

