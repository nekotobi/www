<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>工作行事曆</title>
</head>
<body bgcolor="#B6AEAB">
<?php
	  include('PubApi.php');
	  include('CalendarApi.php');
	  $colorCodes= GetColorCode();
      $colorSet_Green=array("#103025","#2E4E43","#718878","#C4D0CE","#DED6D3","#B6AEAB"); //綠色系
      $CalendarWidth=1400;
      $NowHeight=70;
	  $Bwidth=0;
	  $StartX=10;
      $VacationDays=array();
	  $CrossMonthData=array();
      DrawProject();
	  SetCalendarRange("","");
      $VacationDays=getVacationDays($YearRange,$MonthRange);
	  GetMembersData();
      DrawCalendar();
      DrawUserData(10,10);
	  DrawCalendarHelp();
	  DrawMembers();
      DrawEdit();
       function DrawCalendarHelp(){
		     global $Show;
	         if($Show!="Help")return;
			 DrawHelp("Help/CalendarHelp.png",100,100,1000,500,"Calendar.php","Help");
	   }
	   function DrawProject(){
		     global $CalendarWidth,$NowHeight, $StartX;
			 global $colorCodes,$Projects;
			 $y=45;
		     $Projects=getMysqlDataArray("projectdata");
		     DrawRect(" ","10","#ffffff", $StartX ,$y,( $StartX-20+( count($Projects)+1)*60+40),"20","#000000");
			 $sx= $StartX;
		   	 for($i=0;$i<count($Projects);$i++){
		      //   $x=  $StartX+ ($i+1)*60 ;
			     if( $Projects[$i][3]==""){
					$sx+=60 ; 
				 $color=$colorCodes[  $Projects[$i][1]][0];
				 DrawRect( $Projects[$i][0],"10","#ffffff", $sx,$y+5, "58","12",$color);
				 }
			 }
			 DrawText("Projects",$StartX+5,$y+5,$width,$height,10,"#ffffff");
			 DrawLinkPic("Pics/help.png",$y+5,( $StartX-20+( count($Projects)+1)*60+20),16,16,"Calendar.php?Show=Help");
	   }
       function GetMembersData(){
		     global    $CalendarWidth,$NowHeight,$members, $memberId ;
	         $memberTmp=getMysqlDataArray("members");
			 $members=filterArray($memberTmp,"3","Art");
			 $memberId=array();
			 for($i=0;$i<count($members);$i++){
				 $memberId[$members[$i][0]]=$members[$i][1] ;
			 }

	   }
	   function DrawMembers(){
		     global    $CalendarWidth,$NowHeight,$members, $memberId ;
	   		 for($i=0;$i<count($members);$i++){
				 $x= $CalendarWidth-120- ($i)*60;
				 $color="#000000";
			     DrawMemberRect($members[$i][1],"11","#ffffff", $x, "25", "60","40",$color,$members[$i][4],$members[$i][0]);
			 }
			 $x=$CalendarWidth-120- (count($members))*60;
			 $man=count($members);
			    DrawMemberRect($man."人","11","#ffffff", $x, "25", "60","40",$color,"美術總人數",$members[0][0]);
	   }
	   function DrawCalendar(){
		      global $YearRange,$MonthRange,$showMonthNum,$VacationDays;
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
				global  $VacationDays,$id,$CWorkDays;	
	            $weekName=array("","一","二","三","四","五","六","日");
				DrawRect("","12","#999999",$StartX ,$NowHeight,  $CalendarWidth,"54",$colorSet_Green[1]);
			    DrawRect($m."月","14","#E3D4c3",$StartX,$NowHeight, $CalendarWidth,"20",$colorSet_Green[1]);
				$NowHeight+=22;
				$MonthEnd= getMonthDay($m,$y);
				$WeekStart=GetMonthFirstDay($y,$m);
				$Bwidth=($CalendarWidth-80)/$MonthEnd ;
				for($i=1;$i<=$MonthEnd;$i++){
					  $x=$StartX+80+($i-1)*$Bwidth;
					  DrawRect($i,"10","#E3D4c3", $x, $NowHeight,  ($Bwidth-2),"15",$colorSet_Green[2]);
					  $color=getColor($y,$m,$i);
					  if($id!="guest"){
					  		  $Link="Calendar.php?edit=new&ID=".$id."&Year=".$y."&CMonth=".$m."&CstartDay=".$i."&CworkDays=1&Cwork=work";
					          if($id=="guest")$Link="";
					          DrawLinkRect($weekName[$WeekStart],"12","#000000",$x, $NowHeight+14,   ($Bwidth-2),"15", $color, $Link,"");
					  }
					  if($id=="guest"){
						DrawRect($weekName[$WeekStart],"12","#000000", $x, $NowHeight+14,  ($Bwidth-2),"15",  $color);
				      }
                      $WeekStart+=1;
					  if($WeekStart>7)  $WeekStart=1;
			     }
				$NowHeight+=35;
	   }
	 
	
	   function DrawUsers($y,$m){
                global $YearRange,$MonthRange  , $memberId ;
			    global $CalendarWidth,$NowHeight,$StartX, $colorSet_Green,$Bwidth;
				global $addDivHeight ;
		        $user=getMonthUser($y,$m); 
		     	for($i=0;$i<count($user);$i++){
					$addDivHeight=1;
				    DrawUsersWorks($y,$m,$user[$i]);
					DrawRect( $memberId[$user[$i]],"12","#ffffff",($StartX), $NowHeight,  "80",19*$addDivHeight,$colorSet_Green[0]);
				    $NowHeight+=22*$addDivHeight;
				}
	   }
	    function DrawEdit(){
		    global $edit,$CstartDay,$CworkDays,$CMonth,$Cwork,$CProject,$Csn,$ID,$Year;
			global $CalendarWidth ,$colorCodes,$Projects;
			global $members, $memberId,$rank;
	        $madd=array("schedule","行事","","Other","Other","","");
	        array_push(  $members,$madd);
		    $memberId["schedule"]="行事";
			if($edit=="")return;
			$x=$CalendarWidth/2-400;
			$y=120;
        	DrawPicBG("Pics/Black50Bg.png",$y,$x,800,200 ); 
			$Title="修改工作";
			if($edit=="new"){
				 $ProjectE=$_COOKIE['IGG_Project'];
				 $Title=$Year."新增工作";
			     $CProject=$ProjectE;
			}
		    DrawRect($Title,"22","#ffffff",$x+100,$y+40,600,120, $colorCodes[1][2]);
		    echo "<form id=form name=form method=post action=CalendarUp.php >"; 
			echo "<input type=hidden name=ID value=".$ID.">"; 
			echo "<input type=hidden name=Csn value=".$Csn.">"; 
			echo "<input type=hidden name=Year value=".$Year.">"; 
			echo "<input type=hidden name=Month value=".$CMonth.">"; 
			$inputday="<input type=text name=CstartDay value=".$CstartDay." size=2    >";
			DrawInputRect("從".$CMonth."月","14","#ffffff" ,$x+280,$y+80,140,20, $colorCodes[4][2],"top", $inputday);
	        $inputworkdays="<input type=text name=CworkDays value=".$CworkDays."   size=2   >";
			DrawInputRect( "日起  工作日","14","#ffffff" ,$x+355,$y+80,120,20, $colorCodes[4][2],"top", $inputworkdays);
		    $inputWork="<input type=text name=CWork  value=".$Cwork."  size=32   >";
			DrawInputRect( "工作內容","14","#ffffff" ,$x+250,$y+110,320,20, $colorCodes[4][2],"top", $inputWork);
		    DrawText("*字串中請勿留空格",$x+520,$y+110,320,20,14,"#dddddd");
		    $seletProject=MakeSelection($Projects,0,"RPG","Project");
		    DrawInputRect( "專案","14","#ffffff" ,$x+460,$y+80,240,20, $colorCodes[4][2],"top", $seletProject);
			if($rank==1){ 
			$WorkMembers=MakeSelection($members,1,$memberId[$ID],"UserName");
		    DrawInputRect( "使用者","14","#ffffff" ,$x+580,$y+80,120,20, $colorCodes[4][2],"top", $WorkMembers);
            }
			if($rank>1){ 
			echo "<input type=hidden name=UserName value=".$memberId[$ID].">"; 
			DrawText("使用者[".$memberId[$ID]."]",$x+580,$y+80,120,20,14,"#ffffff");
			}
			if($edit=="edit"){
					  $submit="<input type=submit name=submit value=修改 >";
		              DrawInputRect("","12","#ffffff",$x+340,$y+135,120,20, $colorCodes[4][2],"top", $submit);
					  $delete="<input type=submit name=submit value=刪除 >";
		              DrawInputRect("","12","#ffffff",$x+420,$y+135,120,20, $colorCodes[4][2],"top", $delete);
			}
		    if($edit=="new"){
			          $submit="<input type=submit name=submit value=新增 >";
		              DrawInputRect("","12","#ffffff",$x+340,$y+135,120,20, $colorCodes[4][2],"top", $submit);
			}
			
            echo "</form>";
		    DrawLinkPic("Pics/Cancel.png",$y+24,$x+680,32,32,"Calendar.php");
	  }
	  
	  

	   function getDivHeight($Data_Struct){
		        $h=1;
		   	for ($i=0;$i<count( $Data_Struct);$i++){
			     $info=  $Data_Struct[$i][0] ;
				 $workDays= $Data_Struct[$i][3];
				 if($workDays==0)$workDays=1;
			     $str= floor((strlen( $info )/5)/ $workDays) ;
				 if( $str>$h)$h=$str;
			}
			return $h;
	   }
	   function DrawUsersWorks($y,$m,$ID){
		       	global $addDivHeight ,$rank;
		        global $YearRange,$MonthRange,$id  ;
				global $CalendarWidth,$NowHeight,$StartX, $colorSet_Green ,$Bwidth; 
				global $CrossMonthData;
		        $Data_Struct= getUserWorks($y,$m,$ID);
			    $addDivHeight=getDivHeight($Data_Struct);
		    	for ($i=0;$i<count( $Data_Struct);$i++){
					 $dd= $Data_Struct[$i][1];
					 $finDay= $Data_Struct[$i][2];
					 $Project=$Data_Struct[$i][4];
					// $info= $Project."=".$Data_Struct[$i][0]."[".$Data_Struct[$i][3]."]";
					 $info=  $Data_Struct[$i][0] ;
                     $x= $StartX+($dd-1)*$Bwidth+80;
					 $wid= $finDay*$Bwidth;
					 $colorb=GetProjectColor( $Project ,0);
					 $color=GetProjectColor( $Project ,1);
					 $Csn=$Data_Struct[$i][5];
					 $border=" border-width:2px; border-style:solid ;border-color:".$colorb.";padding:0px ;";
					 if($id==$ID or $rank==1){ 
					 $Link="Calendar.php?edit=edit&ID=".$ID."&Csn=".$Csn.
					 "&CProject=".$Project."&Year=".$y."&CMonth=".$m."&CstartDay=".$dd."&CworkDays=".$Data_Struct[$i][3]."&Cwork=".$Data_Struct[$i][0];
					  DrawLinkRect(  $info ,"12","#000000",$x, $NowHeight,  $wid,19*$addDivHeight, $color, $Link,$border); 
					 }else{
				          DrawRect($info,"12","#000000", $x, $NowHeight,  $wid,19*$addDivHeight, $color);
					  }
			     }
				 //溢月資料
				 for ($i=0;$i<count($CrossMonthData);$i++){
					 if($ID==$CrossMonthData[$i][0] and $m==$CrossMonthData[$i][1] ){
						   $d= $CrossMonthData[$i][2];
						   $wid=$d*$Bwidth;
						   $x= $StartX +80;
						   $info= $CrossMonthData[$i][3];//."[".$CrossMonthData[$i][4]."]";
						   $Project=$CrossMonthData[$i][5];
						   $colorb=GetProjectColor($Project ,0);
				           $color=GetProjectColor( $Project ,1);
					       $border=" border-width:2px; border-style:solid ;border-color:".$colorb.";padding:0px ;";	 
					 $Link="";
			         DrawLinkRect(   $info,"12","#000000",$x, $NowHeight,  $wid,"19", $color, $Link,$border);  
					 }
					 
				 }
	   }
       function GetProjectColor($name,$sn){
		   	 global $colorCodes,$Projects;
			 $color=$colorCodes[7][0];
			 for($i=0;$i<count($Projects);$i++){
			     if($Projects[$i][0]==$name){
					 $c=$Projects[$i][1];
					  $color= $colorCodes[$c][$sn];
				 }
			 }
			 return $color;
	   }
       function testarray($VacationDays){
		   echo ">>>>>>>>>>".count($VacationDays);
	          for ($i=0;$i<count($VacationDays);$i++){
			       echo "[".$VacationDays[$i][0],"-".$VacationDays[$i][1]."-".$VacationDays[$i][2]."]";
			  }
	   }
	   function getUserWorks($y,$m,$user){
		       global  $CrossMonthData,$VacationDays,$YearRange,$MonthRange;
			   // testarray($VacationDays);
 
		       $all_num= getAll_num("calendardata");
			   $t=mysql_num_rows($all_num); 
			   $Data_Struct=array();
			   for ($i=0;$i<$t;$i++){
				    $dy=mysql_result($all_num,$i,'Year');
				    $dm=mysql_result($all_num,$i,'Month');
					$dstartDay=mysql_result($all_num,$i,'Day');
					$duser=mysql_result($all_num,$i,'ID');
					$dWorkDays=mysql_result($all_num,$i,'WorkDay');
					$dinfo=mysql_result($all_num,$i,'info');
					$dt=mysql_result($all_num,$i,'Type');
					$dsn=mysql_result($all_num,$i,'sn');
				    if($dy==$y   And $dm==$m  And $duser==$user){
						 //檢查跨月
						 $MonthEnd= getMonthDay($m,$y);
					     $workDays= ReturnWorkDaysV2($y,$dm,$dstartDay, $dWorkDays,$VacationDays);
					     $finDay=$dstartDay+$workDays-1;  
						//  echo "[".$i.$dinfo."sd:".$dstartDay."-".$dWorkDays."fix".$workDays."fd:".$finDay."]";
					 	 if($finDay<=$MonthEnd){
							 $data=array($dinfo,$dstartDay,$workDays,$dWorkDays,$dt,$dsn);
						     array_push( $Data_Struct, $data);
						 }
					     if($finDay>$MonthEnd){
							  $fd=($MonthEnd-$dstartDay)+1;
							  $data=array($dinfo,$dstartDay,$fd,$dWorkDays,$dt,$dsn );
                              array_push( $Data_Struct, $data);
							  //紀錄溢月資料
							  $CrossDays=$finDay-$MonthEnd;
							  $nextM=$m+1;
							  if($nextM>12)$nextM=1;
                              $CrossFinDays=ReturnWorkDaysV2($y,$nextM,1, $CrossDays,$VacationDays);
							  //echo  "</br>".$CrossDays.">".$CrossFinDays;
							  $crossData=array($duser,$nextM,$CrossFinDays,$dinfo,$dWorkDays,$dt,$dsn);
							  array_push($CrossMonthData,$crossData);
						 }
					 }
					}
			    return  $Data_Struct;
	   }
 
       function getMonthUser($y,$m){
		       global $CrossMonthData;
		       $all_num= getAll_num("calendardata");
			   $t=mysql_num_rows($all_num); 
			   $users=array();
               for ($i=0;$i<$t;$i++){
					  if(mysql_result($all_num,$i,'Year')==$y  And  mysql_result($all_num,$i,'Month')==$m){
						  $u=mysql_result($all_num,$i,'ID');
					      if(!in_array($u,$users ))  array_push($users,$u);
					  }
				 }
				//檢查上月
				  for ($i=0;$i<count($CrossMonthData);$i++){
					   $u=$CrossMonthData[$i][0];
					   if($CrossMonthData[$i][1]==$m){
						  if(!in_array($u,$users )) array_push($users,$u);   
					   }
				  }
               return  $users;
	   }
	 
 
	   function getColor($Y,$M,$D ){
		     global  $colorSet_Green;
			 global  $VacationDays;
		     $BgColor= $colorSet_Green[4];
		     if($M==date("m") and $D==date("d"))$BgColor="#c39393";
		     if(in_array(array($Y,$M,$D),$VacationDays )){
			      $BgColor="#f0c9ca"; 
			 }
			 return  $BgColor;
	   }

	 



 
?>
</body>