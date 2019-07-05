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
		$mainDatat= getMysqlDataArray($tableName); 
	    $mainData=filterArray($mainDatat,0,$typeDatacode[$Stype_1]);
		//表單
		global $Lists,$ListSize,$mileStone;
		$ty=$typeDatacode[$Stype_1]."_type";
        $Listst=filterArray($mainDatat,0,$ty);
		$Lists= $Listst[0];
        $ListSizet=filterArray($mainDatat,0,"size");
	    $ListSize=$ListSizet[0];
		$mileStonet= filterArray($typeDatat,0,"milestone");
		$mileStone=returnArraybySort($mileStonet,2);
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
	 //  DrawRect("FP資源索引","22","#ffffff","20","20","1200","30","#000000");
		//分類
        global $typeData,$typeData2;
		global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		$x=20;$y=60;$w=100;$h=20;
		for($i=0;$i<count($typeData);$i++){
			$Link=$BaseURL."?Stype_1=".$i."&Stype_2=".$Stype_2;
			$BgColor="#000000";
			if($Stype_1==$i)$BgColor="#aa2222";
		    DrawLinkRect($typeData[$i],12,"#ffffff",$x,$y,$w,$h,$BgColor,$Link,"1");
			$x+=110;
		}
	}
	function DrawType(){
			global $Lists,$ListSize;
		    $x=20;$y=90;$w=100;$h=20;
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
	         // $x=20;$y=120;$w=100;$h=40;
			  $rect=array(20,120,100,50);
			  $BgColor="#cccccc";
			  global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
 
			  for($i=0;$i<count($mainData);$i++){
				  DrawStateBar($rect,$mainData[$i] );
				  $rect[1]+=55;
			 
			  }
	}
    function DrawStateBar($rect,$tableData ){
	    	 global $Lists,$ListSize,$mileStone;
			 global $ScheduleData;
		     $BgColor="#cccccc";
			 $GDcode="";
	         for($i=2;$i<count($Lists);$i++){
			     switch($Lists[$i]){
				       case "企劃編碼":
					   $GDcode=$tableData[$i];
					  
					   DrawRect($tableData[$i] ,12,"#000000",$rect[0],$rect[1],$ListSize[$i],$rect[3],$BgColor);
					   break;
				       case "中文名":
					   DrawRect($tableData[$i] ,12,"#000000",$rect[0],$rect[1],$ListSize[$i],$rect[3],"#dddddd");
					   break;	
				       case "英文名":
					   DrawRect($tableData[$i] ,12,"#000000",$rect[0],$rect[1],$ListSize[$i],$rect[3],$BgColor);
					   break;
					   case "設定":
					   $h=$rect[3]/2;
					   $y=$rect[1]+$h;
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
	 
					   break;
					   
				 }
			     $rect[0]+=$ListSize[$i]+5;
			 
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
		     }
			 return "#000000";
	}
	
?>

<?php //資料處理
       function findState($GDsn,$type){
	
	            global $ScheduleData,$mainData;
	            $code=GetScheduleMainPlanCode($ScheduleData,$GDsn,3);
 
				if($code==-1)return "null";
				return findCodeAndState($ScheduleData,$code,$type );
				 
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
	   
	   function findCodeAndState($ScheduleData,$code ,$type ){ //比對文件編碼 工作類別回傳 0狀態 1工作開始 2工作結束日 3外包 4百分比
		         for($i=0;$i<count($ScheduleData);$i++){
			        if( $ScheduleData[$i][3]==$code &&  $ScheduleData[$i][5]==$type ){
						 $percentage=0;
						if($ScheduleData[$i][7]=="進行中") $percentage=	getprogress($ScheduleData[$i][2],$ScheduleData[$i][6]);
					  
						return array($ScheduleData[$i][7],$ScheduleData[$i][2],$ScheduleData[$i][6],$ScheduleData[$i][9],$percentage);
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
			//	echo ">".($passDays / $realDays);
				return ($passDays / $realDays);
				 
	   }
?>


<?php //Orther
	function DrawEdit(){
		      global $Edit;
			  if($Edit=="")return;
		      global $tableName,$data_library, $typeData,$typeData2; 
		      global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
			  global $ScheduleData,$mainData,$mileStone;
			  global $colorCodes,$Lists;
		      $x=200;
		   	  $y=100;
			  $w=300;
			  $h=300;
	          DrawPopBG($x,$y,$w,$h,"編輯資源資料" ,"12",$BackURL);
			  $EditData=$mainData[$Edit];
			  $info=$EditData[2].$EditData[3];
			  $BgColor="#ffffff";
			  $y+=30;
			  DrawRect($info,12,"#000000",$x,$y,"150","20",$BgColor);
			  $tables=returnTables($data_library,$tableName);
			  echo   "<form id='EditRes'  name='Show' action='".$BackURL."' method='post'  enctype='multipart/form-data'>";
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
			  $submit ="<input type=submit name=submit value=修改>";
			  $y+=30;
	          DrawInputRect("","12","#ffffff",($x+350),$y ,120,18, $colorCodes[4][2],"top",$submit );
	}
?>