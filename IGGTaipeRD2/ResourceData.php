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
    TypeGo();
	upFile();
?>
<?php //定義資料區
    function  DefineBaseData(){
	    //分頁
	  	global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		$BaseURL="ResourceData.php";
		$BackURL= $BaseURL."?Stype_1=".$Stype_1."&Stype_2=".$Stype_2;

		$CookieArray=array("Stype_1","Stype_2");
		setcookies($CookieArray,$BaseURL);
		SetGlobalcookieData($CookieArray);
	    CheckCookie($CookieArray);
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
		    DrawLinkRect2sendVal($typeData[$i],12,"#ffffff",$x,$y,$w,$h,$BgColor,$Link,"1");
	     	if($Stype_1==$i)   DrawLinkRect("X".count($mainData),10,"#ffffff",$x+70,$y+4,20,$h-6,"#000000",$Link,"1");
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
		    DrawLinkRect2sendVal( $milestoneSelect[$i],12,"#ffffff",$x,$y,$w,$h,$BgColor,$Link,"1");
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
    function  DrawTypeArea($rect,$type,$planCode,$GDcode){
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
	/*
    function SortList($base,$num){
	      $sorta=array();
		  $nums=array();
		  for($i=0;$i<count($base);$i++){
			 $base[$i][13]= codeRnum($base[$i][2]);
		  }
          $sorta=sortArrays( $base ,13 ,"true");
		  return $sorta;
	}
	*/
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
 
    function GetMainPlanCodeMile($GDCode){ //比對物件名稱包含GDCode 回傳文件編碼
	         global $ScheduleDataTitle;
	         for($i=0;$i<count($ScheduleDataTitle);$i++){
				 if($ScheduleDataTitle[$i][3]!=""){
				    if(strpos($ScheduleDataTitle[$i][3],$GDCode) !== false){ 
				       return  $ScheduleDataTitle[$i][1];
				    }
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

<?php //Orther
 
	function sort_GD($a,$b){
            if($a['sortgd'] == $b['sortgd']) return 0;
           return ($a['sortgd'] > $b['sortgd']) ? 1 : -1;
         }
	/*
	function codeRnum($string){
	        $a= ereg_replace("[a-zA-Z]","",$string);
			return(int) $a;
	}
	*/
    function DrawAddNewOrder(){
	     global $data_library,$tableName,$milestone,$typeData;   
	     global $colorCodes;
		 global $Etype,$Ecode,$planCode,$Edit;
		 global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
	     if($Stype_1=="")$Stype_1=0;
		 $ex=300;
		 $ey=200;
		 $w=400;
		 $h=200;
         $title ="新增[".$Edit."[". $typeData[$Stype_1]."]".$Etype.">".$planCode;
         DrawPopBG($ex,$ey,$w,$h,$title ,"12",$BackURL);
		 $planstmp=getMysqlDataArray($tableName);
		 $plansArray=returnDataArray($planstmp,1,$Ecode);
		 $startDay=explode("_",$plansArray[2]);
     	 $ex+=20;
		 $ey+=20;
		 //From
		 echo   "<form id='AddPlan'  name='Show' action='".$BackURL."' method='post'>";
		 $code=returnDataCode( );
		 echo   "<input type=hidden name=code value=".$code.">";
	 	 echo   "<input type=hidden name=plan value=".$Ecode.">"; 		
         echo   "<input type=hidden name=type value=".$Etype.">";		 
		 echo   "<input type=hidden name=PhpInputType value=upAddType >";
		 echo   "<input type=hidden name=line value=".$plansArray[4]." >";
		 echo   "<input type=hidden name=data_type value=data>"; 
	   //  $selecttype= $SelectType_1[$Stype_1];
	     echo   "<input type=hidden name=selecttype value=".$Etype.">"; 
		 $lastUpdate=date(Y_m_d_H_i,time()+(8*3600));
		 echo   "<input type=hidden name=lastUpdate value=".$lastUpdate.">"; 
         echo   "<input type=hidden name=plan value=".$planCode.">"; 
		 //年
	     $input="<input id='year'  type=text name=year value='".$startDay[0]."'  size=4>年";
	     DrawInputRect("新增","12","#ffffff",($ex),$ey ,120,16, $colorCodes[4][2],"top",$input);
	     //月
	     $input="<input id='month' type=text name=month value='".$startDay[1]."'  size=2>月";
	     DrawInputRect("","12","#ffffff",($ex+80),$ey ,120,16, $colorCodes[4][2],"top",$input);
		 //日
	     $input="<input id='day' type=text name=day value='".$startDay[2]."'  size=2>日".$plansArray[3];
	     DrawInputRect("","14","#ffffff",($ex+130),$ey ,220,16, $colorCodes[4][2],"top",$input);
         //天數
	     $workDayinput="<input id='workingDays' type=text name=workingDays  value='5'  size=2   >";
	     DrawInputRect("天數","12","#ffffff",($ex+240),$ey ,120,18, $colorCodes[4][2],"top",$workDayinput);
		 //外包負責
			 $OutsDatatmp=getMysqlDataArray("outsourcing");
	         $OutsDatatmp2=filterArray($OutsDatatmp,0,"data");
	         $OutsData=returnArraybySort( $OutsDatatmp2,2);
			 $selectTable= MakeSelectionV2($OutsData,$plansArray[9] ,"outsourcing",10);
		     DrawInputRect( "選擇負責外包","10","#ffffff",($ex+160 ),$ey+40 ,220,16, $colorCodes[4][2],"top", $selectTable);
			 //負責人
			 $principaltmp=getMysqlDataArray("members");
			 $principalData=returnArraybySort( $principaltmp,1);
			 $selectTable= MakeSelectionV2( $principalData,$plansArray[8],"principal" ,10);
			 DrawInputRect( "選擇內部負責","10","#ffffff",($ex+160),$ey+60 ,220,16, $colorCodes[4][2],"top", $selectTable);
		 //費用
			 $ey+=30;
			 $ininput="<input type=text name=Price   value='".$plansArray[17]."'  size=10   >";
	         DrawInputRect("費用(美金)","12","#ffffff",($ex+160),$ey+70  ,220,18, $colorCodes[4][2],"top",$ininput);
			  global $stateType;
			 gettypes();
			
		  //狀態
			 $selectTable= MakeSelectionV2( $stateType,$plansArray[7],"state" ,10);
			 DrawInputRect( "目前狀態","10","#ffffff",($ex+160),$ey+90 ,220,16, $colorCodes[4][2],"top", $selectTable);
		 $submitP="<input type=submit name=submit value=新增規畫>";
	     DrawInputRect("",$ey+40  ,"#ffffff",($ex+220),60,120,18, $colorCodes[4][2],"top",$submitP);
		 
         //載入小日曆
		  include('CalendarPlugin.php');
		  DrawSCalender( $ex,$ey+20,"new");
	}
    function TypeGo(){
	         global $EditType;
		     if($EditType=="AddPlan"){
				 DrawAddNewOrder();
			     return;
			  }
			 if($EditType=="Uppic"){
				  DrawEdit();
			     return;
			  }
			 if($EditType=="EditOrder"){
			  	 global $planCode;
				 global $Ecode;
				 global $tableName;
				 $Ecode= trim($planCode);
				  $tableName="fpschedule"; 
				 EditPlanTypeEditor_v2(400,250,400,300);
				
			     return;
			  }
	}
	function DrawEdit(){
		      global $Edit,$Etype,$planCode;
			 // if($Edit=="")return;

		      global $tableName,$data_library, $typeData,$typeData2; 
		      global $BaseURL,$BackURL, $Stype_1,$Stype_2,$Stype_2,$SelectType_2,$stateType; 
			  global $ScheduleData,$mainData,$mileStone;
			  global $colorCodes,$Lists;
		      $x=200;
		   	  $y=100;
			  $w=300;
			  $h=100;
			  $fontSize=12;
			  $fontColor="#ffffff";
			  $title=$SelectType_2[$Stype_2]."[".$Edit."]";
	          DrawPopBG($x,$y,$w,$h,"編輯".$Edit."[".$Etype."]資料" ,"12",$BackURL);
			  DrawRect("[".$BaseCode."]",$fontSize,"#ffffff",$x,$y+20,$w,20,$colorCodes[3][1]);
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
<?php //up
      function upFile(){
		       global $submit;
			//   echo "xx".$submit;
			   global $BackURL;
	           if ($submit=="")return;
			   if($submit=="上傳"){  
			   global $Ecode,$Etype;
			   global $file;
			   UpFiles_Res($Etype,$Ecode,$file);
               echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
			   return;
			   }
			   if($submit=="新增規畫"){  
			    UpAddTypeData( );
				 return;
			   }
			    if($submit=="送出修改"){  
				      global  $tableName;
				 $tableName="fpschedule";
		    
		         UpEditData( );
				 return;
			   }
	  }
      function UpAddTypeData( ){
		       global $data_library,$tableName;
			   global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1;
			   global $year,$month,$day;
			       $tableName="fpschedule";
				echo $data_library;
			       $p=$tableName;
				   $tables=returnTables( $data_library_Base,$p);
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
				    SendCommand($stmt, $data_library);
			  echo " <script language='JavaScript'>window.location.replace('".$BackURL."')</script>";
		      echo $stmt;
	 }

	 
?>

 