<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引</title>
</head>
<?php //主控台
    include('PubApi.php');
	include('mysqlApi.php');
    include('scheduleApi.php');
	 include('CalendarApi.php');  
	DefineData();
	DefineDate();
	DrawMainUI();
	DrawType();
	DrawList();
	DrawEdit();
	upFile();
?>
<?php //主繪製區
    function DefineData(){
	    //分頁
	  	global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		$BaseURL="ResourceData.php";
		$BackURL= $BaseURL."?Stype_1=".$Stype_1."&Stype_2=".$Stype_2;
		if($Stype_1=="")$Stype_1=0;
		if($Stype_2=="")$Stype_2=0;
	    //資料庫
      	global $tableName,$data_library, $typeData,$typeData2; 
		global $ScheduleData,$mainData;
		
		$data_library= "iggtaiperd2";
		$tableName="fpresdata";//"fpschedule";
		$stmp= getMysqlDataArray("fpschedule");	
		$ScheduleData  =  filterArray($stmp,0,"data");
		$typeDatat = getMysqlDataArray("scheduletype");	
	    $typeDatat2=  filterArray($typeDatat,0,"ResType");
		$typeData= returnArraybySort($typeDatat2,2);
        $typeDatacode= returnArraybySort($typeDatat2,4);
	    global $milestoneSelect;
		$mt=getMysqlDataArray( "scheduletype"); 
	    $mt2=filterArray($mt,0,"milestone"); 
	    $milestoneSelect=returnArraybySort($mt2,2);
		
		$mainDatat= getMysqlDataArray($tableName); 
	    $mainDatat2=filterArray($mainDatat,0,$typeDatacode[$Stype_1]);
		$mainData=GetMileStone($mainDatat2,$milestoneSelect[$Stype_2]);
		
		//表單
		global $Lists,$ListSize ;
		
		$ty=$typeDatacode[$Stype_1]."_type";
        $Listst=filterArray($mainDatat,0,$ty);
		$Lists= $Listst[0];
        $ListSizet=filterArray($mainDatat,0,"size");
	    $ListSize=$ListSizet[0];
 
		//尺寸
		global $GRect;
		$GRect=array("x"=>40,"y"=>40,"w"=>100,"h"=>20);
    }
	function DefineDate(){
	        global $VacationDays; 
            global $YearRange,$MonthRange,$showMonthNum,$UpMonth;
			$showMonthNum=6;
            $UpMonth=-3;
			SetCalendarRange( date(Y),date(m));
            $VacationDays=    getVacationDays($YearRange,$MonthRange)	;
	}
	
    function DrawMainUI(){
	    //主頁
	    //DrawRect("FP資源索引","22","#ffffff","20","20","1200","30","#000000");
		//分類
		global $GRect;
        global $typeData,$typeData2;
		global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
			global $ScheduleData,$mainData;
		$x=$GRect[x];$y=$GRect[y] ;$w=$GRect[w];$h=$GRect[h];
		for($i=0;$i<count($typeData);$i++){
			$Link=$BaseURL."?Stype_1=".$i."&Stype_2=".$Stype_2;
			$BgColor="#000000";
		 
			if($Stype_1==$i){
				$BgColor="#aa2222";
			}
		    DrawLinkRect($typeData[$i],12,"#ffffff",$x,$y,$w,$h,$BgColor,$Link,"1");
	     	if($Stype_1==$i)   DrawLinkRect("X".count($mainData),10,"#ffffff",$x+30,$y+4,40,$h-6,"#000000",$Link,"1");
			
			$x+=110;
		}
		//milestone
	    global $milestoneSelect;
	    $x+=100;
		$w=50;
	    for($i=0;$i<count( $milestoneSelect);$i++){
			$Link=$BaseURL."?Stype_1=".$Stype_1."&Stype_2=".$i;
			$BgColor="#000000";
			if($Stype_2==$i)$BgColor="#aa2222";
		    DrawLinkRect( $milestoneSelect[$i],12,"#ffffff",$x,$y,$w,$h,$BgColor,$Link,"1");
			$x+=60;
		}
	}
	function DrawType(){
			global $Lists,$ListSize;
			global $GRect;
		    $x=$GRect[x];$y=$GRect[y]+50 ;$w=$GRect[w];$h=$GRect[h];
			 $BgColor="#000000";
		    for($i=2;$i<count($Lists);$i++){
				 $w=$ListSize[$i];
			     DrawLinkRect($Lists[$i],12,"#ffffff",$x,$y,$w,$h,$BgColor,$Link,"1");
				 $x+=$ListSize[$i]+5;
			}
	}
	
	function DrawList(){
	          global $ScheduleData,$mainData;
			  global $Lists,$ListSize;
			  $rect=array(40,120,100,40);
			  $BgColor="#cccccc";
			  global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
			  for($i=0;$i<count($mainData);$i++){
				  DrawStateBar($rect,$mainData[$i] );
				  $rect[1]+=$rect[3]+2;
			  }
	}
    function DrawStateBar($rect,$tableData ){
	    	 global $Lists,$ListSize,$mileStone;
			 global $ScheduleData;
			 global $colorCodes;
		     $BgColor="#cccccc";
			 $GDcode=$tableData[2];
		 
 	         if($tableData["mileston"]!=""){
			        $n=substr($tableData["mileston"], 1, 1);
			        $rootBgColor=$colorCodes[11][$n];
			        $milecolor=$colorCodes[10][$n];
				    DrawRect("",12,"#000000",$rect[0]-40,$rect[1]-2,1000,$rect[3]+4,$rootBgColor);
				    DrawRect($tableData["mileston"],12,"#000000",$rect[0]-37,$rect[1]+12,32,$rect[3]-20, $milecolor);
			  }
			  $planCode=$tableData[PlanCode];
			  
	          for($i=2;$i<(count($Lists)-1);$i++){
				  if($i<5 ) DrawRect($tableData[$i] ,12,"#000000",$rect[0],$rect[1],$ListSize[$i],$rect[3],$milecolor);
				  if($i>4) DrawTypeArea($rect,$Lists[$i],$planCode,$GDcode);
 
			     $rect[0]+=$ListSize[$i]+5;
			 }
	}
	function DrawTypeArea($rect,$type,$planCode,$GDcode){
			           global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		               global $ListSize;
	                   $h=$rect[3]/2;
					   $y=$rect[1]+$h;
				       $x=$rect[0];
					   $w=$ListSize[2];
					    //echo $w;
					   $state= findCodeAndState($planCode,$type );// 0狀態 1工作開始 2工作結束日 3外包 4百分比
				       if(count($state)==0)return;
	                   $worker="未定義";
					   $filePaths=getResfilePath($GDcode,$type);
					   $worker= $state[3] ; 
                       if( file_exists( $filePaths[2])){
						   $w=$w/2;
					       $x+=$h+30;
					   }
					   //繪師條
					   DrawRect(  $worker ,12,"#ffffff",$x,$rect[1],$w,$h,"#666666" );//繪師
					   if($state[4]==0){
						  $Link=$BackURL."&Edit=".$GDcode."&Etype=".$type;
						  DrawLinkRect( $state[0],12,"#000000",$x,$y,$w,$h,getColor($state[0]),$Link,"");
					   }
					   if($state[4]!=0){ 
							   $w2=$w*$state[4];
							   $recta=$rect;
							   $recta[1]=$y;
							   $recta[3]=$h;
							   $msg=$state[0]."..(".floor($state[4]*100)."%)";
							   $colors=array("#77aa77","#88ff88","#000000");
							   $per=$state[4];
							   if($per>1){
								   $msg="延誤中..(".floor($state[4]*100)."%)";
								   $per=1;
						       $colors=array("#ffaa77","#ff8888","#000000");
							   }
							   DrawProgress($msg,$per,$recta,$colors,"11");
						 }
					    if( file_exists( $filePaths[2])){
					       DrawLinkPic($filePaths[2], $rect[1],$rect[0],$rect[3],$rect[3],$filePaths[1]);
						   DrawRect( $info ,12,"#000000",$rect[0]+$rect[3],$rect[1],$ListSize[$i]-+$rect[3],$rect[3],getColor($state));
						   $rect[0]+=$ListSize[$i]/2;
						   $w/=2;
					   }  
				
	}
	function getColor($state){
	         switch($state){
				 case "已完成":
				 return "#bbaabb";
				 case "進行中":
				 return "#aaffaa";
				 case "未製作":
				 return "#cccc88";
				 case "未定義":
				 return "#ddaa77";
				 case "":
				 return "#ddcc88";
				 case "已排程":
				 return "#ffaa77";
		     }
			 return "#000000";
	}
	
?>
 
<?php //資料處理
       function GetMainPlanCodeMile($GDCode){ //比對物件名稱包含GDCode 回傳文件編碼
	            global $ScheduleData;
	     	     $a=array();
	            for($i=0;$i<count($ScheduleData);$i++){
				    if(strpos($ScheduleData[$i][3],$GDCode) !== false){ 
					  $a=array("code"=>$ScheduleData[$i][1],"mileston"=>$ScheduleData[$i][15]);
				       return  $a;
				    }
				}
				return -1;
	   }
	   function GetMileStone($mainData,$fillerMilestone){
		        //echo $fillerMilestone;
				$reArray= array();
	            for($i=0;$i<count($mainData);$i++){
			        $tmp=	GetMainPlanCodeMile($mainData[$i][2]);
					if($tmp[mileston]==$fillerMilestone or $fillerMilestone=="m1"){
					 $mainData[$i][11]=$tmp[mileston];
					 $mainData[$i][PlanCode]=$tmp[code];
				     $mainData[$i][mileston]=$tmp[mileston];
				   	 array_push( $reArray,$mainData[$i]);
					}
				   
				}
				return $reArray;
	   }

       function getListScheduleData(){
	            global $ScheduleData,$mainData;
	            for($i=0;$i<count($mainData);$i++){
					$GDsn=$mainData[$i][2];
					//echo $GDsn;
			        $code=GetScheduleMainPlanCode($ScheduleData,$GDsn,3);
				     echo $code;
				}
	   }
	   function GetScheduleMainPlanCode($ScheduleData,$keyStr,$num){ //比對物件名稱包含GDCode 回傳文件編碼
	            for($i=0;$i<count($ScheduleData);$i++){
					//  echo  $i;
					//echo $ScheduleData[$i][$num];
					// echo "</br>".$ScheduleData[$i][$num].">".$keyStr;
				    if(strpos($ScheduleData[$i][$num],$keyStr) !== false){ 
				       return $ScheduleData[$i][1];
				    }
				}
				return -1;
	   }
	   
	   function findCodeAndState( $code ,$type ){ //比對文件編碼 工作類別回傳 0狀態 1工作開始 2工作結束日 3外包 4百分比
	             global $ScheduleData;
		         for($i=0;$i<count($ScheduleData);$i++){
			        if( $ScheduleData[$i][3]==$code &&  $ScheduleData[$i][5]==$type ){
						$percentage=0;
						if($ScheduleData[$i][7]=="進行中") $percentage=	getprogress($ScheduleData[$i][2],$ScheduleData[$i][6]);
					    $principal= $ScheduleData[$i][9];
						if($ScheduleData[$i][9]=="" or $ScheduleData[$i][9]=="未定義")$principal=$ScheduleData[$i][8];
						return array($ScheduleData[$i][7],$ScheduleData[$i][2],$ScheduleData[$i][6],  $principal,$percentage);
					}
				 }
				 return array("null");
	   }
	   function getprogress($startDays,$workDays){
		        global $VacationDays;  
		        $startDay=explode("_",$startDays);
			    $nowDayArray=array(date(Y),date(m),date(d));
				$passDays= getPassDays($startDay,$nowDayArray);
			    $realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2],$workDays,$VacationDays);
		     	//echo "[".$passDays."/".$realDays."=".($passDays / $realDays);
				return (($passDays-1) / $realDays);
				 
	   }
?>
<?php //up
      function upFile(){
		       global $submit;
	           if ($submit=="")return;
			   global $Ecode,$Etype;
			   global $file;
			   UpFiles_Res($Etype,$Ecode,$file);
 
	  }

?>
<?php //Orther
	function DrawEdit(){
		      global $Edit,$Etype;
			  if($Edit=="")return;
		      global $tableName,$data_library, $typeData,$typeData2; 
		      global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
			  global $ScheduleData,$mainData,$mileStone;
			  global $colorCodes,$Lists;
		      $x=200;
		   	  $y=100;
			  $w=300;
			  $h=100;
	          DrawPopBG($x,$y,$w,$h,"編輯".$Edit."[".$Etype."]資料" ,"12",$BackURL);
			  $EditData=$mainData[$Edit];
			  $info=$EditData[2].$EditData[3];
			  $BgColor="#ffffff";
			  $y+=30;
			  $tables=returnTables($data_library,$tableName);
			  echo   "<form id='EditRes'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
			  echo   "<input type=hidden name=Etype value=".$Etype.">";
			  echo   "<input type=hidden name=Ecode value=".$Edit.">";
			  $y+=30;
	          $input="<input type=file name=file 	id=file  size=60   >";
			  DrawInputRect("設定檔案"." ","12","#ffffff", $x  ,$y,420,20, $colorCodes[4][2],"top", $input);
			  $submit ="<input type=submit name=submit value=上傳>";
	          DrawInputRect("","12","#ffffff",($x+250),$y ,120,18, $colorCodes[4][2],"上傳",$submit );
              echo "</form>";

	}
?>
<?php
	   /*
					   $state=findState($GDcode,"設定");
					   $filePaths=getResfilePath($GDcode,"設定");
					   $info="null";
					   $w=$ListSize[$i];
					   $worker="未定義";
					   if(count($state)>1){
					      $info=$state[0];
						  $worker=$state[3];
					   }
					   if( file_exists( $filePaths[2])){
					       DrawLinkPic($filePaths[2], $rect[1],$rect[0],$rect[3],$rect[3],$filePaths[1]);
						   DrawRect( $info ,12,"#000000",$rect[0]+$rect[3],$rect[1],$ListSize[$i]-+$rect[3],$rect[3],getColor($state));
						   $rect[0]+=$ListSize[$i]/2;
						   $w/=2;
					   }  // 0狀態 1工作開始 2工作結束日 3外包 4百分比
					     DrawRect( $worker ,12,"#ffffff",$rect[0],$rect[1],$w,$h,"#666666" );//繪師
					    if($state[4]==0){
						   DrawRect( $info ,12,"#000000",$rect[0],$y,$w,$h,getColor($state[0]));//完成狀況
						  }else{
							   $w2=$w*$state[4];
							   $recta=$rect;
							   $recta[1]=$y;
							   $recta[3]=$h;
							   $msg=$info."..(".floor($state[4]*100)."%)";
							   $colors=array("#77aa77","#88ff88","#000000");
							   $per=$state[4];
							   if($per>1){
								   $msg="延誤中..(".floor($state[4]*100)."%)";
								   $per=1;
						       $colors=array("#ffaa77","#ff8888","#000000");
							   }
							   DrawProgress($msg,$per,$recta,$colors,"11");
						 
						   }
	
	               */
	   /*
       function findState($GDsn,$type){
	            global $ScheduleData,$mainData;
	            $code=GetScheduleMainPlanCode($ScheduleData,$GDsn,3);
				if($code==-1)return "null";
				return findCodeAndState($ScheduleData,$code,$type );
	   }
       */
	         /*
			  for($i=0;$i<count($tables);$i++){
			      echo   "<input type=hidden name=".$tables[$i]." value=".$EditData[$i].">";
		    	 }
		      $select=MakeSelectionV2($mileStone,$EditData[10],"stateCode_6",80);
	          DrawInputRect("里程碑","10","#ffffff", $x+155,$y,220,18, $colorCodes[4][2],"top",  $select);
		      for($i=5;$i<count($tables);$i++){
			     if($Lists[$i]!=""){
				    $input="<input type=file name=".$tables[$i]." 	id=".$tables[$i]."  size=60   >";
				    $y+=30;
				    DrawInputRect($Lists[$i]." ","12","#ffffff", $x  ,$y,420,20, $colorCodes[4][2],"top", $input);
			       }
		      }
		      */
?>
