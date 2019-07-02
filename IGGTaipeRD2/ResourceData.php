<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>FP資源索引</title>
</head>
<?php //主控台
    include('PubApi.php');
	include('mysqlApi.php');
	DefineData();
	DrawMainUI();
	getListScheduleData();
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
    function DrawMainUI(){
	    //主頁
	 //  DrawRect("FP資源索引","22","#ffffff","20","20","1200","30","#000000");
		//分類
        global $typeData,$typeData2;
		global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
		$x=20;$y=60;$w=100;$h=20;
		for($i=0;$i<count($typeData);$i++){
			$Link=$BaseURL;//."?Stype_1=".$i."&Stype_2=".$Stype_2;
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
	          $x=20;$y=120;$w=100;$h=40;
			  $BgColor="#cccccc";
			  global $BaseURL,$BackURL, $Stype_1,$Stype_2,$SelectType_1,$SelectType_2,$stateType; 
			  for($i=0;$i<count($mainData);$i++){
				   $x=20;
				   $w=$ListSize[2];
				   DrawRect($mainData[$i][2],12,"#000000",$x,$y,$w,$h,$BgColor);
				   $x+=$w+5;
				   $w=$ListSize[3];
			       DrawRect($mainData[$i][3],12,"#000000",$x,$y,$w,$h,$BgColor);
				   $x+=$w+5;
                   $w=$ListSize[4];
			       DrawRect($mainData[$i][4],12,"#000000",$x,$y,$w,$h,$BgColor);
		           $x+=$w+5;
				   $w=$ListSize[5];
				   $state=findState($mainData[$i][2],$Lists[5 ]);
				   DrawRect( $state,12,"#000000",$x,$y,$w,$h,$BgColor);
				      $x+=$w+5;
				   $w=$ListSize[6];
				   $state=findState($mainData[$i][2],$Lists[6]);
				   DrawRect( $state,12,"#000000",$x,$y,$w,$h,$BgColor);
				   
				      $Link=$BackURL."&Edit=".$i;
			       // DrawLinkRect("Edit",12,"#ffffff",$x,$y,$w,$h,"#ff9999",$Link,"1");
				     $y+=45;
			  }
	}

	
?>

<?php //資料處理
       function findState($GDsn,$state){
	            global $ScheduleData,$mainData;
	            $code=GetScheduleMainPlanCode($ScheduleData,$GDsn,3);
				if($code==-1)return "null";
				return findCodeAndState($ScheduleData,$code );
				 
	   }
       
       function getListScheduleData(){
	            global $ScheduleData,$mainData;
	            for($i=0;$i<count($mainData);$i++){
					$GDsn=$mainData[$i][2];
					//echo $GDsn;
			        $code=GetScheduleMainPlanCode($ScheduleData,$GDsn,3);
				    //echo $code;
				}
	   }
	   function GetScheduleMainPlanCode($ScheduleData,$keyStr,$num){
	            for($i=0;$i<count($ScheduleData);$i++){
					//  echo  $i;
					//echo $ScheduleData[$i][$num];
					//echo "</br>".$ScheduleData[$i][$num].">".$keyStr;
				    if(strpos($ScheduleData[$i][$num],$keyStr) !== false){ 
				       return $ScheduleData[$i][1];
				    }
				}
				return -1;
	   }
	   
	   function findCodeAndState($ScheduleData,$code ){
		         for($i=0;$i<count($ScheduleData);$i++){
			     if( $ScheduleData[$i][3]==$code)return $ScheduleData[$i][7];
				 }
				 return "null";
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