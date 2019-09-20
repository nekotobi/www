<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引v2</title>
</head>
<?php //主控台
    include('PubApi.php');
	include('mysqlApi.php');
    include('scheduleApi.php');
	include('CalendarApi.php'); 
	DefineBaseData();
	DefineMysQLData();
    DefineDate();
	DrawMainUI();
	DrawType();
	DrawList();
?>
<?php //定義資料區
    function  DefineBaseData(){
	    //分頁
		 
	  	global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		$BaseURL="ResourceData.php";
		$BackURL= $BaseURL."?Stype_1=".$Stype_1."&Stype_2=".$Stype_2;
		if($Stype_1=="")$Stype_1=0;
		if($Stype_2=="")$Stype_2=0;
	}
	function  DefineMysQLData(){
	    //資料庫
	    global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
      	global $tableName,$data_library, $typeData,$typeData2; 
		global $ScheduleData,$mainData,$mainDatatType,$mainDataBase;
		global $ScheduleDataTitle,$ScheduleDataOpt;
		$data_library= "iggtaiperd2";
		$tableName="fpresdata"; 
		$stmp= getMysqlDataArray("fpschedule");	
		$ScheduleData  =  filterArray($stmp,0,"data");
		$ScheduleDataTitle=filterArray($ScheduleData,5,"工項");
		
		$typeDatat = getMysqlDataArray("scheduletype");	
	    $typeDatat2=  filterArray($typeDatat,0,"ResType");
		$typeData= returnArraybySort($typeDatat2,2);
        $typeDatacode= returnArraybySort($typeDatat2,4);
		
	    global $milestoneSelect;
		$mt=getMysqlDataArray( "scheduletype"); 
	    $mt2=filterArray($mt,0,"milestone"); 
	    $milestoneSelect=returnArraybySort($mt2,2);
		$mainDataBase= getMysqlDataArray($tableName);
		//過濾類別(英雄 mob
	    $mainDatatType=filterArray($mainDataBase,0,$typeDatacode[$Stype_1]); 
		//過濾類別m2 m3 
		if($Stype_2==0) $filterMileStone= $mainDatatType;
	    if($Stype_2!=0){
		 $filterMileStone=filterArray($mainDatatType,12,$milestoneSelect[$Stype_2]);
	    }
	    $SortmainData=SortList($filterMileStone,0);
	  	$mainData=GetMileStoneCode($SortmainData);
		$ScheduleDataOpt= optimizationSchData();
 
		//表單
		global $Lists,$ListSize ;
		$ty=$typeDatacode[$Stype_1]."_type";
        $Listst=filterArray($mainDataBase,0,$ty);
		$Lists= $Listst[0];
		//print_r($Listst);
        $ListSizet=filterArray($mainDataBase,0,"size");
	    $ListSize=$ListSizet[0];
		//尺寸
		global $GRect;
		$GRect=array("x"=>40,"y"=>40,"w"=>100,"h"=>20);
    }
    function  DefineDate(){
	          global $VacationDays; 
              global $YearRange,$MonthRange,$showMonthNum,$UpMonth;
			  $showMonthNum=6;
              $UpMonth=-3;
		   	  SetCalendarRange( date(Y),date(m));
              $VacationDays= getVacationDays($YearRange,$MonthRange)	;
	}
?>
 
<?php  //繪製區
    function  DrawMainUI(){
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
	function  DrawType(){
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
	function  DrawList(){
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
    function  DrawStateBar($rect,$tableData ){
	    	 global $Lists,$ListSize,$mileStone;
			 global $ScheduleData;
			 global $colorCodes;
		     $BgColor="#cccccc";
			 $GDcode=$tableData[2];
			 //milestone;
 	         if($tableData[12]!=""){
			        $n=substr($tableData[12], 1, 1);
			        $rootBgColor=$colorCodes[11][$n];
			        $milecolor=$colorCodes[10][$n];
				    DrawRect("",12,"#000000",$rect[0]-40,$rect[1]-2,1200,$rect[3]+4,$rootBgColor);
				    DrawRect($tableData[12],12,"#000000",$rect[0]-37,$rect[1]+12,32,$rect[3]-20, $milecolor);
			 }
			  //內容
			 $planCode=$tableData[PlanCode];
	         for($i=2;$i<(count($Lists)-1);$i++){
				  if($i<5 )DrawRect($tableData[$i] ,12,"#000000",$rect[0],$rect[1],$ListSize[$i],$rect[3],$milecolor);
				 if($i>4) DrawTypeArea($rect,$Lists[$i],$planCode,$GDcode);
			      $rect[0]+=$ListSize[$i]+5;
			 }
	}
    function DrawTypeArea($rect,$type,$planCode,$GDcode){
		               if($planCode==-1)return;
		               if($type=="")return;
			           global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		               global $ListSize;
	                   $h=$rect[3]/2;
					   $y=$rect[1]+$h;
				       $x=$rect[0];
					   $w=$ListSize[2];
					   $state= findCodeAndState($planCode,$type );// 0狀態 1工作開始 2工作結束日 3外包 4百分比 5code 6selfcode
				       if(count($state)==0)return;
					  
	                   $worker="未定義";
					   $filePaths=getResfilePath($GDcode,$type);
					   $worker= $state[3] ; 
                       if( file_exists( $filePaths[2])){
						   $w=$w/2;
					       $x+=$h+30;
					   }
					   //繪師條
					   $Add="&EditType=Uppic";
					    if($state[0] =="null"){
						   $Add="&EditType=AddPlan";
						   $Link=$BackURL.$Add."&Edit=".$GDcode."&Etype=".$type."&planCode=".$planCode;
					       DrawLinkRect("新增規畫",12,"#993333",$x,$y-20,$w,$h*2,"#662222",$Link,"");
						   return;
					      }
					      DrawRect(  $worker ,12,"#ffffff",$x,$rect[1],$w,$h,"#666666" );//繪師
	                     if($state[0] !="已完成") $Add="&EditType=EditOrder";
						  $Link=$BackURL.$Add."&Edit=".$GDcode."&Etype=".$type."&planCode=".$state[5];
						  DrawLinkRect( $state[0],12,"#000000",$x,$y,$w,$h,getColor($state[0]),$Link,"");
					 
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

?>

<?php //整理資訊
    function SortList($base,$num){
	      $sorta=array();
		  $nums=array();
		  for($i=0;$i<count($base);$i++){
			 $base[$i][13]= codeRnum($base[$i][2]);
		  }
          $sorta=sortArrays( $base ,13 ,"true");
		  return $sorta;
	}
    function findCodeAndState( $code ,$type ){ //比對文件編碼 工作類別回傳 0狀態 1工作開始 2工作結束日 3外包 4百分比 5code
	             global $ScheduleDataOpt;
 
				 $ScheduleData=$ScheduleDataOpt;
		         for($i=0;$i<count($ScheduleData);$i++){
			        if( $ScheduleData[$i][3]==$code &&  $ScheduleData[$i][5]==$type ){
					 
						$percentage=0;
						if($ScheduleData[$i][7]=="進行中") $percentage=	getprogress($ScheduleData[$i][2],$ScheduleData[$i][6]);
					    $principal= $ScheduleData[$i][9];
						if($ScheduleData[$i][9]=="" or $ScheduleData[$i][9]=="未定義")$principal=$ScheduleData[$i][8];
 
						return array($ScheduleData[$i][7],$ScheduleData[$i][2],$ScheduleData[$i][6],
						$principal,$percentage,$ScheduleData[$i][1] );
					}
				 }
				 return array("null");
	}
    function getColor($state){
	         switch($state){
				 case "已完成":
				 return "#bbaabb";
				 case "進行中":
				 return "#77aa77";
				 case "未製作":
				 return "#cccc88";
				 case "未定義":
				 return "#555555";
				 case "":
				 return "#555555";
				 case "已排程":
				 return "#77aa77";
				 case  "暫停":
				 return "#ffaa77";
				  case "待優化":
				 return "#ffaa77";
		     }
			 return "#000000";
	}
	function optimizationSchData(){ //最佳化原始資料
	         global $ScheduleData, $ScheduleDataOpt ;
			 global $mainData;
			 $ScheduleDataOpt=array();
			 for($i=0;$i<count($mainData);$i++){
                 PushCodeSch(  $mainData[$i][PlanCode] );
			 }
			 return $ScheduleDataOpt;
	}
	function PushCodeSch($code ){
		     global $ScheduleData, $ScheduleDataOpt;
			 for($i=0;$i<count($ScheduleData);$i++){
			    if($ScheduleData[$i][3]==$code){
				 	 array_push($ScheduleDataOpt,$ScheduleData[$i]);
				}
			 }		
	}
?>

<?php

    function getprogress($startDays,$workDays){
		        global $VacationDays;  
		        $startDay=explode("_",$startDays);
			    $nowDayArray=array(date(Y),date(m),date(d));
				$passDays= getPassDays($startDay,$nowDayArray);
			    $realDays=ReturnWorkDaysV2($startDay[0],$startDay[1],$startDay[2],$workDays,$VacationDays);
				return   $passDays   / ($realDays+1);
				 
	 }
	function codeRnum($string){
	        $a= ereg_replace("[a-zA-Z]","",$string);
			return(int) $a;
	}
    function GetMainPlanCodeMile($GDCode){ //比對物件名稱包含GDCode 回傳文件編碼
	         global $ScheduleDataTitle;
	         for($i=0;$i<count($ScheduleDataTitle);$i++){
				 if(strpos($ScheduleDataTitle[$i][3],$GDCode) !== false){ 
				    return  $ScheduleDataTitle[$i][1];
				    }
				}
			 return -1;
    }
    function GetMileStoneCode($mainData){
				$reArray= array();
	            for($i=0;$i<count($mainData);$i++){
					 // $mainData[$i][mileston]=$mainData[$i][12];
					 
					   $mainData[$i][PlanCode]=GetMainPlanCodeMile($mainData[$i][2]);
				   	   array_push( $reArray,$mainData[$i]);
				}
				return $reArray;
    }
?>
 